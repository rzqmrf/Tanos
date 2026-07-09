<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Professional Enterprise Resource Planning (ERP) Dashboard. Built with Laravel 12, Tailwind CSS, Alpine.js, and Chart.js.">
    <title>@yield('title', 'Tanos ERP Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- CSS Custom overrides (Scrollbar styling, font bindings) -->
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar for modern looks */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <!-- Tailwind CSS and Alpine.js / Chart.js bundler (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen">

    <!-- Parent Container with Alpine.js State -->
    <div class="flex min-h-screen" x-data="{ 
        sidebarOpen: false, 
        selectedMonth: '{{ $currentMonth }}', 
        selectedRegional: '{{ $currentRegional }}', 
        selectedSegment: '{{ $currentSegment }}',
        stats: @js($initialData['stats']),
        chartData: @js($initialData['charts']),
        async fetchData() {
            try {
                const res = await fetch(`/api/dashboard-data?month=${this.selectedMonth}&regional=${this.selectedRegional}&segment=${this.selectedSegment}`);
                const data = await res.json();
                
                this.stats = data.stats;
                this.chartData = data.charts;
                
                if (window.updateDashboardCharts) {
                    window.updateDashboardCharts(data.charts);
                }
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
            }
        }
    }">
        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Wrapper (accounts for fixed sidebar of 250px) -->
        <div class="flex-1 flex flex-col min-w-0 pl-[250px] transition-all duration-300">
            <!-- Navbar Component -->
            <x-navbar :months="$months" :regionals="$regionals" :segments="$segments" />

            <!-- Main Content Container -->
            <main class="flex-1 p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Inject initial chart data for ApexCharts boot -->
    <script>
        window.__initialChartData = @js($initialData['charts'] ?? []);
    </script>

    <!-- Dashboard Charts (ApexCharts) -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
