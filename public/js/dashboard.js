/**
 * Tanos ERP - Dashboard JavaScript File
 * Handles Chart.js initialization, custom value labels plugin, and dynamic updates.
 */

(function () {
    let charts = {};

    // Standard Theme Colors consistent across components
    const COLORS = {
        blue: '#1b3bb6',
        green: '#10b981',
        orange: '#f59e0b',
        purple: '#8b5cf6',
        slate: '#94a3b8'
    };

    // Maps to Segment/Regional category indices
    const CATEGORY_COLORS = [
        COLORS.blue,   // Index 0: Enterprise / Jawa Barat
        COLORS.green,  // Index 1: Corporate / Jawa Timur
        COLORS.orange, // Index 2: Government / Jawa Tengah
        COLORS.purple, // Index 3: SME / Sumatera
        COLORS.slate   // Index 4: Retail / Kalimantan (Lainnya)
    ];

    /**
     * Helper to format raw values into standard Indonesian business display.
     */
    function formatRupiahTick(val) {
        if (val >= 1000000000) {
            return (val / 1000000000).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + 'M';
        }
        if (val >= 1000000) {
            return (val / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + 'Jt';
        }
        return val.toLocaleString('id-ID');
    }

    /**
     * Format values as complete Indonesian Rupiah for tooltips.
     */
    function formatRupiahTooltip(val) {
        return 'Rp ' + Number(val).toLocaleString('id-ID');
    }

    /**
     * Create linear gradient for canvas background fills.
     */
    function createGradient(ctx, color, opacityStart = '44', opacityEnd = '00') {
        const grad = ctx.createLinearGradient(0, 0, 0, ctx.canvas.clientHeight || 200);
        grad.addColorStop(0, color + opacityStart);
        grad.addColorStop(1, color + opacityEnd);
        return grad;
    }

    /**
     * Custom Chart.js Plugin to draw value labels on top of bar nodes and line points
     * exactly matches the style of the reference image.
     */
    const valueLabelsPlugin = {
        id: 'valueLabels',
        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            ctx.save();
            ctx.font = 'bold 9px "Plus Jakarta Sans", sans-serif';
            ctx.fillStyle = '#475569'; // slate-600
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';

            chart.data.datasets.forEach((dataset, datasetIndex) => {
                const meta = chart.getDatasetMeta(datasetIndex);
                if (meta.hidden) return;

                meta.data.forEach((element, index) => {
                    const val = dataset.data[index];
                    if (val === undefined || val === null || val === 0) return;

                    let formattedVal;
                    const id = chart.canvas.id;

                    if (id.includes('cost') || id.includes('tagihan')) {
                        // Format currency values (e.g. 32,4M or 18,45M)
                        if (val >= 1000000000) {
                            formattedVal = (val / 1000000000).toLocaleString('id-ID', { 
                                minimumFractionDigits: 1, 
                                maximumFractionDigits: 2 
                            }) + 'M';
                        } else if (val >= 1000000) {
                            formattedVal = (val / 1000000).toLocaleString('id-ID', { 
                                minimumFractionDigits: 1, 
                                maximumFractionDigits: 2 
                            }) + 'Jt';
                        } else {
                            formattedVal = val.toLocaleString('id-ID');
                        }
                    } else {
                        // Format count values (e.g. 1.356)
                        formattedVal = val.toLocaleString('id-ID');
                    }

                    // Position text slightly above the bar/point element
                    ctx.fillText(formattedVal, element.x, element.y - 6);
                });
            });
            ctx.restore();
        }
    };

    /**
     * Initialize all 7 dashboard charts.
     */
    function initializeCharts() {
        const root = document.querySelector('[x-data]');
        if (!root) return;

        // Fetch initial chart data stored in Alpine
        const alpineData = window.Alpine.$data(root);
        const chartData = alpineData.chartData;

        // Common configurations
        const fontConfig = {
            family: "'Plus Jakarta Sans', sans-serif",
            size: 9,
            weight: '600'
        };

        const gridConfig = {
            color: '#f1f5f9',
            drawTicks: false
        };

        // 1. Doughnut: Jumlah Project per Segment
        const segmentCtx = document.getElementById('projectSegmentChart').getContext('2d');
        charts.projectSegment = new Chart(segmentCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.projectsPerSegment.map(i => i.category),
                datasets: [{
                    data: chartData.projectsPerSegment.map(i => i.value),
                    backgroundColor: CATEGORY_COLORS,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        usePointStyle: true,
                        callbacks: {
                            label: function (context) {
                                return ` ${context.label}: ${context.raw} Project`;
                            }
                        }
                    }
                }
            }
        });

        // 2. Doughnut: Jumlah Project per Regional
        const regionalCtx = document.getElementById('projectRegionalChart').getContext('2d');
        charts.projectRegional = new Chart(regionalCtx, {
            type: 'doughnut',
            data: {
                labels: chartData.projectsPerRegional.map(i => i.category),
                datasets: [{
                    data: chartData.projectsPerRegional.map(i => i.value),
                    backgroundColor: CATEGORY_COLORS,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        usePointStyle: true,
                        callbacks: {
                            label: function (context) {
                                return ` ${context.label}: ${context.raw} Project`;
                            }
                        }
                    }
                }
            }
        });

        // 3. Bar: Total Pegawai per Regional (Value labels enabled)
        const pegawaiCtx = document.getElementById('pegawaiRegionalChart').getContext('2d');
        charts.pegawaiRegional = new Chart(pegawaiCtx, {
            type: 'bar',
            data: {
                labels: chartData.pegawaiPerRegional.map(i => i.category),
                datasets: [{
                    data: chartData.pegawaiPerRegional.map(i => i.value),
                    backgroundColor: CATEGORY_COLORS.map(c => createGradient(pegawaiCtx, c, 'ff', 'cc')),
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 18 } }, // padding for top value labels
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        callbacks: {
                            label: function (context) {
                                return ` ${context.raw} Pegawai`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: fontConfig, color: '#94a3b8' }
                    },
                    y: {
                        grid: gridConfig,
                        border: { display: false },
                        ticks: {
                            font: fontConfig,
                            color: '#94a3b8',
                            callback: function (value) { return value.toLocaleString('id-ID'); }
                        }
                    }
                }
            },
            plugins: [valueLabelsPlugin]
        });

        // 4. Line: Total Tagihan per Bulan (Value labels enabled)
        const tagihanCtx = document.getElementById('tagihanBulanChart').getContext('2d');
        charts.tagihanBulan = new Chart(tagihanCtx, {
            type: 'line',
            data: {
                labels: chartData.tagihanPerBulan.map(i => i.category),
                datasets: [{
                    label: 'Tagihan',
                    data: chartData.tagihanPerBulan.map(i => i.value),
                    borderColor: COLORS.blue,
                    borderWidth: 3.5,
                    pointBackgroundColor: COLORS.blue,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.35,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 18 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        callbacks: {
                            label: function (context) {
                                return ` Tagihan: ${formatRupiahTooltip(context.raw)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: fontConfig, color: '#94a3b8' }
                    },
                    y: {
                        grid: gridConfig,
                        border: { display: false },
                        ticks: {
                            font: fontConfig,
                            color: '#94a3b8',
                            callback: formatRupiahTick
                        }
                    }
                }
            },
            plugins: [valueLabelsPlugin]
        });

        // 5. Bar: Total Cost per Regional (Value labels enabled)
        const costRegCtx = document.getElementById('costRegionalChart').getContext('2d');
        charts.costRegional = new Chart(costRegCtx, {
            type: 'bar',
            data: {
                labels: chartData.costPerRegional.map(i => i.category),
                datasets: [{
                    data: chartData.costPerRegional.map(i => i.value),
                    backgroundColor: CATEGORY_COLORS.map(c => createGradient(costRegCtx, c, 'ff', 'aa')),
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 22
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 18 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        callbacks: {
                            label: function (context) {
                                return ` Cost: ${formatRupiahTooltip(context.raw)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: fontConfig, color: '#94a3b8' }
                    },
                    y: {
                        grid: gridConfig,
                        border: { display: false },
                        ticks: {
                            font: fontConfig,
                            color: '#94a3b8',
                            callback: formatRupiahTick
                        }
                    }
                }
            },
            plugins: [valueLabelsPlugin]
        });

        // 6. Bar: Total Cost per Segment (Value labels enabled)
        const costSegCtx = document.getElementById('costSegmentChart').getContext('2d');
        charts.costSegment = new Chart(costSegCtx, {
            type: 'bar',
            data: {
                labels: chartData.costPerSegment.map(i => i.category),
                datasets: [{
                    data: chartData.costPerSegment.map(i => i.value),
                    backgroundColor: CATEGORY_COLORS.map(c => createGradient(costSegCtx, c, 'ff', 'aa')),
                    borderRadius: 6,
                    borderSkipped: false,
                    maxBarThickness: 22
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 18 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        callbacks: {
                            label: function (context) {
                                return ` Cost: ${formatRupiahTooltip(context.raw)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: fontConfig, color: '#94a3b8' }
                    },
                    y: {
                        grid: gridConfig,
                        border: { display: false },
                        ticks: {
                            font: fontConfig,
                            color: '#94a3b8',
                            callback: formatRupiahTick
                        }
                    }
                }
            },
            plugins: [valueLabelsPlugin]
        });

        // 7. Area: Trend Cost per Bulan (Value labels enabled)
        const costBulanCtx = document.getElementById('costBulanChart').getContext('2d');
        const costBulanGradient = createGradient(costBulanCtx, COLORS.green, '44', '00');
        charts.costBulan = new Chart(costBulanCtx, {
            type: 'line',
            data: {
                labels: chartData.costPerBulan.map(i => i.category),
                datasets: [{
                    label: 'Cost',
                    data: chartData.costPerBulan.map(i => i.value),
                    borderColor: COLORS.green,
                    borderWidth: 3.5,
                    pointBackgroundColor: COLORS.green,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.35,
                    fill: true,
                    backgroundColor: costBulanGradient
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 18 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#ffffff',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        cornerRadius: 12,
                        callbacks: {
                            label: function (context) {
                                return ` Cost: ${formatRupiahTooltip(context.raw)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: fontConfig, color: '#94a3b8' }
                    },
                    y: {
                        grid: gridConfig,
                        border: { display: false },
                        ticks: {
                            font: fontConfig,
                            color: '#94a3b8',
                            callback: formatRupiahTick
                        }
                    }
                }
            },
            plugins: [valueLabelsPlugin]
        });
    }

    /**
     * Expose global helper to update all chart datasets dynamically.
     * Triggers smooth Chart.js update transitions.
     */
    window.updateDashboardCharts = function (filteredData) {
        if (!charts.projectSegment) return; // not initialized yet

        // 1. Projects per Segment (Doughnut)
        charts.projectSegment.data.datasets[0].data = filteredData.projectsPerSegment.map(i => i.value);
        charts.projectSegment.update();

        // 2. Projects per Regional (Doughnut)
        charts.projectRegional.data.datasets[0].data = filteredData.projectsPerRegional.map(i => i.value);
        charts.projectRegional.update();

        // 3. Pegawai per Regional (Bar)
        charts.pegawaiRegional.data.datasets[0].data = filteredData.pegawaiPerRegional.map(i => i.value);
        charts.pegawaiRegional.update();

        // 4. Tagihan per Bulan (Line)
        charts.tagihanBulan.data.datasets[0].data = filteredData.tagihanPerBulan.map(i => i.value);
        charts.tagihanBulan.update();

        // 5. Cost per Regional (Bar)
        charts.costRegional.data.datasets[0].data = filteredData.costPerRegional.map(i => i.value);
        charts.costRegional.update();

        // 6. Cost per Segment (Bar)
        charts.costSegment.data.datasets[0].data = filteredData.costPerSegment.map(i => i.value);
        charts.costSegment.update();

        // 7. Cost per Bulan (Area)
        charts.costBulan.data.datasets[0].data = filteredData.costPerBulan.map(i => i.value);
        charts.costBulan.update();
    };

    // Wait for Alpine and Chart.js to be available on window
    const checkDependencies = setInterval(() => {
        if (window.Chart && window.Alpine) {
            clearInterval(checkDependencies);
            initializeCharts();
        }
    }, 100);
})();
