window.dashboardCharts = function (payload = {}) {
    const readCssColor = (variable, fallback) => {
        const probe = document.createElement("span");
        probe.style.color = `var(${variable})`;
        probe.style.position = "absolute";
        probe.style.pointerEvents = "none";
        document.body.appendChild(probe);
        const value = getComputedStyle(probe).color;
        probe.remove();

        return value && value !== "rgba(0, 0, 0, 0)" ? value : fallback;
    };

    return {
        charts: {},
        data: payload,
        themeObserver: null,

        initCharts() {
            this.watchTheme();
            this.loadAllCharts();
        },

        updateAllCharts(detail = {}) {
            const next = detail?.data ?? detail;

            if (next && (next.semanas || next.meses || next.topProductos)) {
                this.data = next;
            }

            this.loadAllCharts();
        },

        watchTheme() {
            if (this.themeObserver) {
                return;
            }

            this.themeObserver = new MutationObserver(() => this.loadAllCharts());
            this.themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ["class"],
            });
        },

        destroy() {
            this.themeObserver?.disconnect();
            this.themeObserver = null;
            Object.keys(this.charts).forEach((id) => this.removeChart(id));
        },

        removeChart(id) {
            if (!this.charts[id]) {
                return;
            }

            this.charts[id].destroy();
            delete this.charts[id];
        },

        isDark() {
            return document.documentElement.classList.contains("dark");
        },

        palette() {
            const dark = this.isDark();
            const accent = readCssColor("--color-accent", dark ? "rgb(249, 115, 22)" : "rgb(194, 65, 12)");
            const accentSoft = readCssColor("--color-accent-content", dark ? "rgb(251, 146, 60)" : "rgb(194, 65, 12)");

            return {
                dark,
                stroke: dark ? accentSoft : accent,
                fill: dark ? accent : accentSoft,
                label: dark ? "#a3a3a3" : "#737373",
                grid: dark ? "#404040" : "#e5e5e5",
                markerStroke: dark ? "#171717" : "#ffffff",
                bars: dark
                    ? ["#fdba74", "#fb923c", "#f97316", "#fb7185", "#fbbf24"]
                    : ["#c2410c", "#ea580c", "#f97316", "#e11d48", "#d97706"],
            };
        },

        formatBs(value) {
            const amount = Number(value) || 0;

            return "Bs " + amount.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        },

        formatAxisBs(value) {
            const amount = Number(value) || 0;

            if (Math.abs(amount) >= 1000) {
                return "Bs " + (amount / 1000).toLocaleString("en-US", { maximumFractionDigits: 1 }) + "k";
            }

            return "Bs " + amount.toLocaleString("en-US", { maximumFractionDigits: 0 });
        },

        baseOptions(type, height = 300) {
            const colors = this.palette();

            return {
                chart: {
                    type,
                    height,
                    background: "transparent",
                    fontFamily: "Instrument Sans, ui-sans-serif, system-ui, sans-serif",
                    toolbar: { show: false },
                    animations: { enabled: true, easing: "easeinout", speed: 450 },
                    foreColor: colors.label,
                },
                theme: { mode: colors.dark ? "dark" : "light" },
                colors: [colors.stroke],
                grid: {
                    borderColor: colors.grid,
                    strokeDashArray: 4,
                    padding: { top: 4, right: 8, bottom: 0, left: 8 },
                },
                dataLabels: { enabled: false },
                tooltip: { theme: colors.dark ? "dark" : "light" },
                noData: {
                    text: "Sin datos en este período",
                    style: { color: colors.label, fontSize: "13px" },
                },
                xaxis: {
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: colors.label, fontSize: "12px" } },
                },
                yaxis: {
                    labels: { style: { colors: colors.label, fontSize: "12px" } },
                },
                legend: { show: false },
            };
        },

        mount(id, selector, options) {
            this.removeChart(id);

            const element = document.querySelector(selector);

            if (!element || typeof ApexCharts === "undefined") {
                return;
            }

            this.charts[id] = new ApexCharts(element, options);
            this.charts[id].render();
        },

        loadAllCharts() {
            this.renderSemanal();
            this.renderProductos();
            this.renderIngresos();
            this.renderClientes();
        },

        renderSemanal() {
            const colors = this.palette();
            const options = this.baseOptions("line");

            options.series = [{ name: "Ingresos", data: this.data.ventasSemanales || [] }];
            options.stroke = { curve: "smooth", width: 3, colors: [colors.stroke] };
            options.markers = {
                size: 4,
                strokeWidth: 2,
                strokeColors: colors.markerStroke,
                colors: [colors.stroke],
                hover: { size: 6 },
            };
            options.xaxis.categories = this.data.semanas || [];
            options.yaxis.labels.formatter = (value) => this.formatAxisBs(value);
            options.tooltip.y = { formatter: (value) => this.formatBs(value) };

            this.mount("semanal", "#chartSemanal", options);
        },

        renderProductos() {
            const colors = this.palette();
            const options = this.baseOptions("bar");

            options.series = [{ name: "Vendidos", data: this.data.topCantidades || [] }];
            options.colors = colors.bars;
            options.plotOptions = {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    barHeight: "64%",
                    distributed: true,
                },
            };
            options.xaxis.categories = this.data.topProductos || [];
            options.xaxis.labels.formatter = (value) => Math.round(Number(value) || 0).toLocaleString("en-US");
            options.yaxis.labels.maxWidth = 160;
            options.tooltip.y = { formatter: (value) => `${Math.round(Number(value) || 0)} uds.` };

            this.mount("productos", "#chartProductos", options);
        },

        renderIngresos() {
            const colors = this.palette();
            const options = this.baseOptions("area");

            options.series = [{ name: "Ingresos", data: this.data.ingresosMensuales || [] }];
            options.stroke = { curve: "smooth", width: 3, colors: [colors.stroke] };
            options.fill = {
                type: "gradient",
                gradient: {
                    shade: colors.dark ? "dark" : "light",
                    type: "vertical",
                    shadeIntensity: 0.35,
                    opacityFrom: colors.dark ? 0.45 : 0.32,
                    opacityTo: 0.02,
                    stops: [0, 100],
                },
            };
            options.markers = {
                size: 0,
                hover: { size: 5 },
            };
            options.xaxis.categories = this.data.meses || [];
            options.yaxis.labels.formatter = (value) => this.formatAxisBs(value);
            options.tooltip.y = { formatter: (value) => this.formatBs(value) };

            this.mount("ingresos", "#chartIngresos", options);
        },

        renderClientes() {
            const options = this.baseOptions("bar");

            options.series = [{ name: "Nuevos clientes", data: this.data.clientesCantidad || [] }];
            options.plotOptions = {
                bar: {
                    borderRadius: 8,
                    columnWidth: "42%",
                },
            };
            options.xaxis.categories = this.data.clientesMes || [];
            options.yaxis.labels.formatter = (value) => Math.round(Number(value) || 0).toLocaleString("en-US");
            options.tooltip.y = { formatter: (value) => `${Math.round(Number(value) || 0)} clientes` };

            this.mount("clientes", "#chartClientes", options);
        },
    };
};
