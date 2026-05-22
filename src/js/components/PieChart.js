import * as d3 from "d3";

const PieChart = ({ data, width, description, usedShortLabel }) => {
  const elementApi = window?.wp?.element;
  const useRef = elementApi?.useRef;
  const useEffect = elementApi?.useEffect;
  const createElement = elementApi?.createElement;

  if (typeof useRef !== "function" || typeof useEffect !== "function" || typeof createElement !== "function") {
    return null;
  }

  const chartRef = useRef();

  useEffect(() => {
    if (!Array.isArray(data) || data.length === 0) {
      return undefined;
    }

    const height = Math.min(width, 260);
    const radius = Math.min(width, height) / 2;

    const arc = d3
      .arc()
      .innerRadius(radius * 0.67)
      .outerRadius(radius - 1);

    const pie = d3
      .pie()
      .padAngle(1 / radius)
      .sort(null)
      .value((d) => d.value);

    const highContrastColors = [
      "#04316a",
      "#8ecae6",
      "#2ca02c",
      "#d62728",
      "#9467bd",
      "#8c564b",
      "#e377c2",
      "#7f7f7f",
      "#bcbd22",
      "#17becf",
    ]; // WCAG 2.1 AA compliant colors

    const color = d3
      .scaleOrdinal()
      .domain(data.map((d) => d.name))
      .range(highContrastColors);

    // Clear existing SVG content for updates
    d3.select(chartRef.current).selectAll("*").remove();

    const svg = d3
      .select(chartRef.current)
      .attr("width", width)
      .attr("height", height)
      .attr("viewBox", [-width / 2, -height / 2, width, height])
      .attr("role", "img")
      .attr("aria-label", description || "")
      .attr("style", "max-width: 100%; height: auto; background: none;");

    // Tooltip for showing data on hover
    const tooltip = d3
      .select("body")
      .append("div")
      .attr("class", "tooltip")
      .style("position", "absolute")
      .style("background", "white")
      .style("border", "1px solid #ccc")
      .style("border-radius", "4px")
      .style("padding", "8px")
      .style("pointer-events", "none")
      .style("opacity", 0);

    // Add Patterns to Defs
    const defs = svg.append("defs");
    defs
      .selectAll("pattern")
      .data(data)
      .join("pattern")
      .attr("id", (d, i) => `pattern-${i}`)
      .attr("patternUnits", "userSpaceOnUse")
      .attr("width", 8)
      .attr("height", 8)
      .call((pattern) => {
        pattern
          .append("rect")
          .attr("width", 8)
          .attr("height", 8)
          .attr("fill", (d) => color(d.name)); // Base color

        pattern
          .append("path")
          .attr("d", (d, i) => {
            // Different patterns for each index
            const patterns = [
                "M0 0 L8 8", // Diagonal stripes
                "M8 0 L0 8", // Reverse diagonal
                "M4 0 L4 8 M0 4 L8 4", // Crosshatch
                "M2 2 L6 2 L6 6 L2 6 Z", // Dots
                "M0 4 L8 4", // Horizontal line
                "M4 0 L4 8", // Vertical line
                "M2 2 L6 2 M2 6 L6 6", // Small squares
                "M0 0 L8 8 M8 0 L0 8", // Diagonal cross
                "M0 0 L4 8 L8 0", // Chevron
                "M0 0 L8 8 M8 0 L0 8 M4 0 L4 8", // Dense diagonal
                "M0 0 L4 4 L0 8 Z", // Triangular
                "M2 0 L6 0 L6 8 L2 8 Z", // Rectangle
                "M1 1 L3 1 L3 3 L1 3 Z M5 5 L7 5 L7 7 L5 7 Z", // Grid dots
                "M1 0 L7 0 M1 8 L7 8 M0 1 L0 7 M8 1 L8 7", // Bordered corners
                "M0 0 L4 4 L0 8 Z M4 4 L8 8 L4 0 Z", // Alternating triangles
                "M0 0 L8 0 M0 4 L8 4", // Double horizontal
                "M0 0 L0 8 M4 0 L4 8", // Double vertical
                "M0 0 L8 4 L0 8 Z", // Diamond
                "M4 0 L8 4 L4 8 L0 4 Z", // Hollow diamond
                "M0 0 L8 0 L8 8 L0 8 Z M1 1 L7 1 L7 7 L1 7 Z", // Framed square
                "M0 0 L2 8 L4 0 L6 8 L8 0", // Zigzag vertical
                "M0 4 L2 0 L4 4 L6 0 L8 4", // Zigzag horizontal
                "M0 0 L8 0 L4 8 Z", // Half triangle
                "M4 0 L8 8 L0 8 Z", // Inverted triangle
                "M0 4 L4 0 L8 4 L4 8 Z", // Star-like
                "M0 0 L8 8 M8 0 L0 8 M4 0 L4 8 M0 4 L8 4", // Dense crosshatch
                "M2 0 L2 8 M6 0 L6 8 M0 2 L8 2 M0 6 L8 6", // Grid lines
                "M1 1 L3 1 L3 3 L1 3 Z M5 1 L7 1 L7 3 L5 3 Z M1 5 L3 5 L3 7 L1 7 Z M5 5 L7 5 L7 7 L5 7 Z", // Mini grid
              ];
              
            return patterns[i % patterns.length];
          })
          .attr("stroke", "rgba(0, 0, 0, 0.5)")
          .attr("stroke-width", 1);
      });

    // Pie chart paths
    svg
      .append("g")
      .selectAll("path")
      .data(pie(data))
      .join("path")
      .attr("fill", (d, i) => `url(#pattern-${i})`)
      .attr("stroke", "#fff")
      .attr("stroke-width", 1.5)
      .attr("d", arc)
      .on("mouseover", (event, d) => {
        const summary = d.data.summary || {};
        const used = Number(summary.used ?? d.data.value) || 0;
        const limit = Number(summary.limit ?? 0) || 0;
        const remaining = Number(summary.remaining ?? 0) || 0;
        const rows = [`<strong>${d.data.name}</strong>`, `Value: ${d.data.value.toLocaleString()}`];
        if (limit > 0 && used >= 0) {
          rows.push(`Used: ${used.toLocaleString()} / ${limit.toLocaleString()}`);
        }
        if (limit > 0) {
          rows.push(`Remaining: ${remaining.toLocaleString()}`);
        }
        tooltip
          .style("opacity", 1)
          .html(rows.join("<br/>") || "")
          .style("left", `${event.pageX + 10}px`)
          .style("top", `${event.pageY + 10}px`);
      })
      .on("mousemove", (event) => {
        tooltip
          .style("left", `${event.pageX + 10}px`)
          .style("top", `${event.pageY + 10}px`);
      })
      .on("mouseout", () => {
        tooltip.style("opacity", 0);
      });

    const summary = data[0]?.summary || {};
    const limit = Number(summary.limit ?? 0) || 0;
    const used = Number(summary.used ?? 0) || 0;
    const usedPercentage = limit > 0 ? Math.min(Math.round((used / limit) * 100), 100) : 0;

    svg
      .append("text")
      .attr("y", -4)
      .attr("text-anchor", "middle")
      .attr("font-family", "sans-serif")
      .attr("font-size", 28)
      .attr("font-weight", 700)
      .attr("fill", "#1d2327")
      .text(`${usedPercentage}%`);

    svg
      .append("text")
      .attr("y", 18)
      .attr("text-anchor", "middle")
      .attr("font-family", "sans-serif")
      .attr("font-size", 12)
      .attr("fill", "#646970")
      .text(usedShortLabel || "");

    // Cleanup tooltip on unmount
    return () => tooltip.remove();
  }, [data, width, description, usedShortLabel]);

  return createElement("svg", { ref: chartRef });
};

export default PieChart;
