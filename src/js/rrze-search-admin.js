import domReady from "@wordpress/dom-ready";
import PieChart from "./components/PieChart";

const USAGE_WIDGET_SELECTOR = ".rrze-search-dashboard-widget";
const roots = new WeakMap();

const getElementApi = () => window?.wp?.element || null;

/**
 * Removes a multisearch resource via AJAX and reloads the settings page.
 *
 * @param {number} resourceId Index of the resource to remove.
 * @return {void}
 */
const rrzeResourceRemoval = (resourceId) => {
  const $ = window.jQuery;
  if (!$) {
    return;
  }

  const data = {
    action: "resourceRemoval",
    resource_id: resourceId,
  };

  $.post(ajaxurl, data, (success) => {
    if (success) {
      window.location.reload();
    }
  });
};

window.rrze_resource_removal = rrzeResourceRemoval;

const bindResourceFormEvents = () => {
  const $ = window.jQuery;
  if (!$) {
    return;
  }

  $(document).ready(() => {
    $("#rrze_search_add_resource_form").on("click", () => {
      const count = $("#rrze_search_resource_count").val();
      const uId = `rrze_${Math.random()}`;
      const template = document.getElementsByTagName("template")[0];
      const partial = template.innerHTML
        .replace(/index/g, count)
        .replace(/uid/g, uId);

      $("#rrze_search_resource_form tbody").append(partial);
      $("#rrze_search_resource_count").val(parseInt(count, 10) + 1);
    });
  });
};

const parseUsageData = (dataset) => {
  if (!dataset) {
    return {};
  }

  try {
    const parsed = JSON.parse(dataset);
    return typeof parsed === "object" && parsed !== null ? parsed : {};
  } catch (error) {
    return {};
  }
};

const mountUsageWidget = (container) => {
  const elementApi = getElementApi();
  if (!elementApi || typeof elementApi.createElement !== "function") {
    return;
  }

  const legacyRender = elementApi.render;
  const modernCreateRoot = elementApi.createRoot;
  const { createElement } = elementApi;

  const usage = parseUsageData(container.dataset.usage);
  const emptyMessage = container.dataset.empty || container.textContent || "";
  const defaultPeriod = container.dataset.defaultPeriod || "";
  const usedLabel = container.dataset.usedLabel || "Used quota";
  const availableLabel = container.dataset.availableLabel || "Available quota";
  const globalDescription = container.dataset.description || "";

  const select = container.querySelector(".rrze-search-dashboard-widget__period");
  const chartHost = container.querySelector(".rrze-search-dashboard-widget__chart");

  if (!chartHost) {
    return;
  }

  const getSelectedPeriod = () => {
    if (select && select.value) {
      return select.value;
    }

    if (defaultPeriod && usage[defaultPeriod]) {
      return defaultPeriod;
    }

    const keys = Object.keys(usage);
    return keys[0] || "";
  };

  const renderForCurrentPeriod = () => {
    const currentPeriod = getSelectedPeriod();
    const stats = usage[currentPeriod];

    if (!stats || stats.limit <= 0) {
      chartHost.textContent = emptyMessage;
      chartHost.classList.add("rrze-search-dashboard-widget__empty");
      return;
    }

    const limit = Number(stats.limit) || 0;
    const total = Number(stats.total) || 0;
    const used = Math.max(Math.min(total, limit || total), 0);
    const remaining = limit > 0 ? Math.max(limit - used, 0) : 0;
    const summary = { limit, used, remaining };
    const data = [
      { name: usedLabel, value: used, summary },
      { name: availableLabel, value: remaining, summary },
    ];

    const description = stats.label
      ? `${stats.label} (${used.toLocaleString()} / ${limit.toLocaleString()})`
      : globalDescription;

    const width = Math.max(chartHost.clientWidth || container.clientWidth || 0, 320);
    const element = createElement(PieChart, { data, width, description });

    if (typeof modernCreateRoot === "function") {
      let root = roots.get(chartHost);
      if (!root) {
        root = modernCreateRoot(chartHost);
        roots.set(chartHost, root);
      }
      root.render(element);
      return;
    }

    if (typeof legacyRender === "function") {
      legacyRender(element, chartHost);
    }
  };

  renderForCurrentPeriod();

  if (select) {
    select.addEventListener("change", renderForCurrentPeriod);
  }

  container.__rrzeSearchUpdate = renderForCurrentPeriod;
};

const renderUsageWidgets = () => {
  const containers = document.querySelectorAll(USAGE_WIDGET_SELECTOR);

  if (!containers.length) {
    return;
  }

  containers.forEach((container) => {
    if (!container.__rrzeSearchUpdate) {
      mountUsageWidget(container);
      return;
    }

    container.__rrzeSearchUpdate();
  });
};

bindResourceFormEvents();

domReady(() => {
  renderUsageWidgets();
  window.addEventListener("resize", renderUsageWidgets);
});
