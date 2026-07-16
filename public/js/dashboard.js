/**
 * Tanos ERP — Dashboard Charts (ApexCharts)
 */

// ─── Color Palette ───────────────────────────────────────────────────────────
const COLORS = {
    blue:    '#1b3bb6',
    emerald: '#10b981',
    amber:   '#f59e0b',
    purple:  '#8b5cf6',
    slate:   '#94a3b8',
    rose:    '#f43f5e',
    cyan:    '#06b6d4',
    indigo:  '#6366f1',
};

const PALETTE   = [COLORS.blue, COLORS.emerald, COLORS.amber, COLORS.purple, COLORS.slate];
const GRID_CLR  = '#f1f5f9';
const LABEL_CLR = '#94a3b8';
const FONT      = 'Plus Jakarta Sans, ui-sans-serif, system-ui, sans-serif';

const isDark = () => document.documentElement.classList.contains('dark');
const getGridClr = () => isDark() ? '#334155' : '#f1f5f9';
const getLabelClr = () => isDark() ? '#94a3b8' : '#64748b';
const getLegendColor = () => isDark() ? '#cbd5e1' : '#64748b';
const getTooltipTheme = () => isDark() ? 'dark' : 'light';

// ─── Shared Legend Style ──────────────────────────────────────────────────────
const legendStyle = {
    show: true,
    position: 'bottom',
    horizontalAlign: 'center',
    fontSize: '11px',
    fontFamily: FONT,
    fontWeight: 600,
    labels: { colors: '#64748b' },
    markers: { size: 7, shape: 'circle', offsetX: -2 },
    itemMargin: { horizontal: 10, vertical: 4 },
};

// ─── Shared ApexCharts Defaults ───────────────────────────────────────────────
const baseOptions = {
    chart: {
        fontFamily: FONT,
        toolbar:    { show: false },
        zoom:       { enabled: false },
        animations: { enabled: true, speed: 500, animateGradually: { enabled: true, delay: 80 } },
    },
    grid: {
        borderColor:  GRID_CLR,
        strokeDashArray: 4,
        xaxis: { lines: { show: false } },
        yaxis: { lines: { show: true  } },
        padding: { top: 0, right: 8, bottom: 0, left: 8 },
    },
    tooltip: {
        style:  { fontFamily: FONT, fontSize: '12px' },
        theme:  'light',
        marker: { show: true },
    },
    legend: { show: false },
    dataLabels: { enabled: false },
};

// ─── Chart instances (kept globally for update) ───────────────────────────────
let charts = {};

// ─── Helper: update options or create a chart ─────────────────────────────────
function renderChart(id, options) {
    // Dynamically override values for dark mode support
    if (!options.grid) options.grid = {};
    options.grid.borderColor = getGridClr();

    if (!options.tooltip) options.tooltip = {};
    options.tooltip.theme = getTooltipTheme();

    if (options.xaxis && options.xaxis.labels && options.xaxis.labels.style) {
        options.xaxis.labels.style.colors = getLabelClr();
    }

    if (options.yaxis && options.yaxis.labels && options.yaxis.labels.style) {
        options.yaxis.labels.style.colors = getLabelClr();
    }

    if (options.legend && options.legend.labels) {
        options.legend.labels.colors = getLegendColor();
    }

    if (charts[id]) {
        charts[id].updateOptions(options);
    } else {
        const el = document.querySelector('#' + id);
        if (!el) return;
        charts[id] = new ApexCharts(el, options);
        charts[id].render();
    }
}

// ─── 1. Donut: Jumlah Project per Segment ────────────────────────────────────
function initProjectSegmentChart(data) {
    const items  = data || [];
    const labels = items.map(i => i.category);
    const values = items.map(i => i.value);
    renderChart('projectSegmentChart', {
        ...baseOptions,
        chart: { ...baseOptions.chart, type: 'donut', height: 176 },
        series: values,
        labels: labels,
        colors: PALETTE,
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '10px',
                            fontWeight: 700,
                            color: isDark() ? '#94a3b8' : '#64748b',
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0),
                        },
                        value: { fontSize: '16px', fontWeight: 800, color: isDark() ? '#f8fafc' : '#0f172a' },
                    },
                },
            },
        },
        stroke: { width: 2 },
        // Donut uses custom HTML legend in blade, no built-in legend needed
        legend: { show: false },
        dataLabels: { enabled: false },
        tooltip: {
            ...baseOptions.tooltip,
            y: { formatter: (v, { seriesIndex, w }) => `${v} proyek (${((v / w.globals.seriesTotals.reduce((a,b)=>a+b,0))*100).toFixed(1)}%)` },
        },
    });
}

// ─── 2. Donut: Jumlah Project per Regional ───────────────────────────────────
function initProjectRegionalChart(data) {
    const items  = data || [];
    const labels = items.map(i => i.category);
    const values = items.map(i => i.value);
    renderChart('projectRegionalChart', {
        ...baseOptions,
        chart: { ...baseOptions.chart, type: 'donut', height: 176 },
        series: values,
        labels: labels,
        colors: PALETTE,
        plotOptions: {
            pie: {
                donut: {
                    size: '68%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '10px',
                            fontWeight: 700,
                            color: isDark() ? '#94a3b8' : '#64748b',
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0),
                        },
                        value: { fontSize: '16px', fontWeight: 800, color: isDark() ? '#f8fafc' : '#0f172a' },
                    },
                },
            },
        },
        stroke: { width: 2 },
        legend: { show: false },
        dataLabels: { enabled: false },
        tooltip: {
            ...baseOptions.tooltip,
            y: { formatter: (v, { seriesIndex, w }) => `${v} proyek (${((v / w.globals.seriesTotals.reduce((a,b)=>a+b,0))*100).toFixed(1)}%)` },
        },
    });
}

// ─── 3. Bar: Total Pegawai per Regional ──────────────────────────────────────
function initPegawaiRegionalChart(data) {
    const items  = (data && data.pegawaiPerRegional) ? data.pegawaiPerRegional : [];
    const labels = items.map(i => i.category);
    const values = items.map(i => i.value);
    renderChart('pegawaiRegionalChart', {
        ...baseOptions,
        chart:  { ...baseOptions.chart, type: 'bar', height: '100%' },
        series: [{ name: 'Jumlah Pegawai', data: values }],
        xaxis: {
            categories: labels,
            labels: { style: { colors: LABEL_CLR, fontSize: '11px', fontFamily: FONT } },
            axisBorder: { show: false }, axisTicks: { show: false },
        },
        yaxis: { labels: { style: { colors: LABEL_CLR, fontSize: '11px', fontFamily: FONT } } },
        colors: [COLORS.purple],
        plotOptions: {
            bar: { borderRadius: 6, columnWidth: '55%' },
        },
        dataLabels: { enabled: false },
        fill: {
            type: 'gradient',
            gradient: { shade: 'light', type: 'vertical', shadeIntensity: 0.2,
                         gradientToColors: [COLORS.indigo], stops: [0, 100] },
        },
        // Legend: show series name at top
        legend: { ...legendStyle },
        tooltip: {
            ...baseOptions.tooltip,
            y: { formatter: v => v.toLocaleString('id-ID') + ' orang' },
        },
    });
}

// ─── 4. Area: Total Tagihan per Bulan ────────────────────────────────────────
function initTagihanBulanChart(data) {
    const items  = (data && data.tagihanPerBulan) ? data.tagihanPerBulan : [];
    const labels = items.map(i => i.category);
    const vals   = items.map(i => i.value);
    renderChart('tagihanBulanChart', {
        ...baseOptions,
        chart:  { ...baseOptions.chart, type: 'area', height: '100%' },
        series: [{ name: 'Total Tagihan', data: vals }],
        xaxis: {
            categories: labels,
            labels: { style: { colors: LABEL_CLR, fontSize: '11px', fontFamily: FONT } },
            axisBorder: { show: false }, axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                style: { colors: LABEL_CLR, fontSize: '11px', fontFamily: FONT },
                formatter: v => {
                    if (v >= 1e9) return (v / 1e9).toFixed(1) + 'M';
                    if (v >= 1e6) return (v / 1e6).toFixed(1) + 'jt';
                    return v.toLocaleString('id-ID');
                },
            },
        },
        colors: [COLORS.blue],
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0.02, stops: [0, 95] },
        },
        // Legend top-left
        legend: { ...legendStyle },
        markers: { size: 4, hover: { size: 6 } },
        tooltip: {
            ...baseOptions.tooltip,
            y: { formatter: v => 'Rp ' + v.toLocaleString('id-ID') },
        },
    });
}

// ─── 5. Bar: Total Cost per Regional ─────────────────────────────────────────
function initCostRegionalChart(data) {
    const items  = (data && data.costPerRegional) ? data.costPerRegional : [];
    const labels = items.map(i => i.category);
    const values = items.map(i => i.value);
    renderChart('costRegionalChart', {
        ...baseOptions,
        chart:  { ...baseOptions.chart, type: 'bar', height: '100%' },
        series: [{ name: 'Total Cost', data: values }],
        xaxis: {
            categories: labels,
            labels: { style: { colors: LABEL_CLR, fontSize: '10px', fontFamily: FONT },
                      rotate: -30, trim: true },
            axisBorder: { show: false }, axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                style: { colors: LABEL_CLR, fontSize: '10px', fontFamily: FONT },
                formatter: v => {
                    if (v >= 1e9) return (v / 1e9).toFixed(1) + 'M';
                    if (v >= 1e6) return (v / 1e6).toFixed(1) + 'jt';
                    return v;
                },
            },
        },
        colors: [COLORS.blue],
        plotOptions: { bar: { borderRadius: 5, columnWidth: '55%' } },
        fill: {
            type: 'gradient',
            gradient: { shade: 'light', type: 'vertical', shadeIntensity: 0.2,
                         gradientToColors: [COLORS.cyan], stops: [0, 100] },
        },
        legend: { ...legendStyle },
        tooltip: {
            ...baseOptions.tooltip,
            y: { formatter: v => 'Rp ' + v.toLocaleString('id-ID') },
        },
    });
}

// ─── 6. Bar: Total Cost per Segment ──────────────────────────────────────────
function initCostSegmentChart(data) {
    const items  = (data && data.costPerSegment) ? data.costPerSegment : [];
    const labels = items.map(i => i.category);
    const values = items.map(i => i.value);
    renderChart('costSegmentChart', {
        ...baseOptions,
        chart:  { ...baseOptions.chart, type: 'bar', height: '100%' },
        series: [{ name: 'Total Cost', data: values }],
        xaxis: {
            categories: labels,
            labels: { style: { colors: LABEL_CLR, fontSize: '10px', fontFamily: FONT } },
            axisBorder: { show: false }, axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                style: { colors: LABEL_CLR, fontSize: '10px', fontFamily: FONT },
                formatter: v => {
                    if (v >= 1e9) return (v / 1e9).toFixed(1) + 'M';
                    if (v >= 1e6) return (v / 1e6).toFixed(1) + 'jt';
                    return v;
                },
            },
        },
        colors: [COLORS.amber],
        plotOptions: { bar: { borderRadius: 5, columnWidth: '55%' } },
        fill: {
            type: 'gradient',
            gradient: { shade: 'light', type: 'vertical', shadeIntensity: 0.2,
                         gradientToColors: [COLORS.rose], stops: [0, 100] },
        },
        legend: { ...legendStyle },
        tooltip: {
            ...baseOptions.tooltip,
            y: { formatter: v => 'Rp ' + v.toLocaleString('id-ID') },
        },
    });
}

// ─── 7. Area: Trend Cost per Bulan ───────────────────────────────────────────
function initCostBulanChart(data) {
    const items  = (data && data.costPerBulan) ? data.costPerBulan : [];
    const labels = items.map(i => i.category);
    const values = items.map(i => i.value);
    renderChart('costBulanChart', {
        ...baseOptions,
        chart:  { ...baseOptions.chart, type: 'area', height: '100%' },
        series: [{ name: 'Trend Cost', data: values }],
        xaxis: {
            categories: labels,
            labels: { style: { colors: LABEL_CLR, fontSize: '10px', fontFamily: FONT } },
            axisBorder: { show: false }, axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                style: { colors: LABEL_CLR, fontSize: '10px', fontFamily: FONT },
                formatter: v => {
                    if (v >= 1e9) return (v / 1e9).toFixed(1) + 'M';
                    if (v >= 1e6) return (v / 1e6).toFixed(1) + 'jt';
                    return v;
                },
            },
        },
        colors: [COLORS.indigo],
        stroke: { curve: 'smooth', width: 2.5 },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.02, stops: [0, 95] },
        },
        legend: {
            ...legendStyle,
            position: 'top',
            horizontalAlign: 'left',
        },
        markers: { size: 4, hover: { size: 6 } },
        tooltip: {
            ...baseOptions.tooltip,
            y: { formatter: v => 'Rp ' + v.toLocaleString('id-ID') },
        },
    });
}

// ─── Init all charts ──────────────────────────────────────────────────────────
function initDashboardCharts(chartData) {
    initProjectSegmentChart(chartData.projectsPerSegment);
    initProjectRegionalChart(chartData.projectsPerRegional);
    initPegawaiRegionalChart(chartData);
    initTagihanBulanChart(chartData);
    initCostRegionalChart(chartData);
    initCostSegmentChart(chartData);
    initCostBulanChart(chartData);
}

// ─── Update on Alpine filter change ──────────────────────────────────────────
window.updateDashboardCharts = function(chartData) {
    initDashboardCharts(chartData);
};

// ─── Boot on DOMContentLoaded ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    if (window.__initialChartData) {
        initDashboardCharts(window.__initialChartData);
    }
});
