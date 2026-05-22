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

const FULL_WIDTH_CONTAINER_CLASS = "rrze-search-dashboard-widget__full-width-row";

const ensureFullWidthPlacement = () => {
  const widget = document.getElementById("rrze_search_usage_widget");
  const wrapper = document.getElementById("dashboard-widgets-wrap");

  if (!widget || !wrapper) {
    return;
  }

  let container = wrapper.querySelector(`.${FULL_WIDTH_CONTAINER_CLASS}`);

  if (!container) {
    container = document.createElement("div");
    container.className = FULL_WIDTH_CONTAINER_CLASS;
    wrapper.insertBefore(container, wrapper.firstChild);
  }

  if (widget.parentElement !== container) {
    container.appendChild(widget);
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
  const fallbackPeriod = defaultPeriod && usage[defaultPeriod] ? defaultPeriod : Object.keys(usage)[0] || "";
  const usedLabel = container.dataset.usedLabel || "Used quota";
  const usedShortLabel = container.dataset.usedShortLabel || "used";
  const availableLabel = container.dataset.availableLabel || "Available quota";
  const globalDescription = container.dataset.description || "";

  const chartHosts = container.querySelectorAll(".rrze-search-dashboard-widget__chart");

  if (!chartHosts.length) {
    return;
  }

  const renderChartForHost = (chartHost) => {
    const period = chartHost.dataset.period;
    const stats = usage[period] || usage[fallbackPeriod] || null;

    if (!stats || stats.limit <= 0) {
      chartHost.textContent = emptyMessage;
      chartHost.classList.add("rrze-search-dashboard-widget__empty");
      return;
    }

    chartHost.classList.remove("rrze-search-dashboard-widget__empty");

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

    const fallbackWidth = chartHost.parentElement?.clientWidth || container.clientWidth || 0;
    const maxWidth = Math.max(chartHost.clientWidth || fallbackWidth, 220);
    const width = Math.max(Math.min(Math.round(maxWidth * 0.72), 260), 180);
    const element = createElement(PieChart, { data, width, description, usedShortLabel });

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

  const renderAllCharts = () => {
    chartHosts.forEach((chartHost) => {
      renderChartForHost(chartHost);
    });
  };

  renderAllCharts();

  container.__rrzeSearchUpdate = renderAllCharts;
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
  ensureFullWidthPlacement();
  renderUsageWidgets();
  window.addEventListener("resize", renderUsageWidgets);
});
