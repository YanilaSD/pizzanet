// resources/js/dashboard-charts.js
window.dashboardCharts = function () {
    console.log("ApexCharts: dashboard-charts.js cargado OK");

    return {
        charts: {},

        initCharts() {
            console.log("Alpine → initCharts()");
            this.loadAllCharts();
        },

        loadAllCharts() {
            console.log("Renderizando TODOS los charts...");
            this.renderSemanal();
            this.renderProductos();
            this.renderIngresos();
            this.renderClientes();
        },

        destroy(chartId) {
            if (this.charts[chartId]) {
                console.log(`Destruyendo chart: ${chartId}`);
                this.charts[chartId].destroy();
            }
        },

        /* ============================
         *  GRÁFICO 1 — SEMANAL
         * ============================ */
        renderSemanal() {
            this.destroy("semanal");

            const el = document.querySelector("#chartSemanal");
            if (!el) return console.warn("chartSemanal no encontrado");

            console.log("Render → chartSemanal", window.chartSemanalConfig);

            this.charts.semanal = new ApexCharts(el, window.chartSemanalConfig);
            this.charts.semanal.render();
        },

        /* ============================
         *  GRÁFICO 2 — PRODUCTOS
         * ============================ */
        renderProductos() {
            this.destroy("productos");

            const el = document.querySelector("#chartProductos");
            if (!el) return console.warn("chartProductos no encontrado");

            console.log("Render → chartProductos", window.chartProductosConfig);

            this.charts.productos = new ApexCharts(el, window.chartProductosConfig);
            this.charts.productos.render();
        },

        /* ============================
         *  GRÁFICO 3 — INGRESOS
         * ============================ */
        renderIngresos() {
            this.destroy("ingresos");

            const el = document.querySelector("#chartIngresos");
            if (!el) return console.warn("chartIngresos no encontrado");

            console.log("Render → chartIngresos", window.chartIngresosConfig);

            this.charts.ingresos = new ApexCharts(el, window.chartIngresosConfig);
            this.charts.ingresos.render();
        },

        /* ============================
         *  GRÁFICO 4 — CLIENTES
         * ============================ */
        renderClientes() {
            this.destroy("clientes");

            const el = document.querySelector("#chartClientes");
            if (!el) return console.warn("chartClientes no encontrado");

            console.log("Render → chartClientes", window.chartClientesConfig);

            this.charts.clientes = new ApexCharts(el, window.chartClientesConfig);
            this.charts.clientes.render();
        },
    };
};
