<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Professional Enterprise Resource Planning (ERP) Dashboard. Built with Laravel 12, Tailwind CSS, Alpine.js, and Chart.js.">
    <title>@yield('title', 'Tanos ERP Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

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

    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f8fafc] dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased min-h-screen">

    <div class="flex min-h-screen" x-data="{ 
        sidebarOpen: false, 
        sidebarHidden: localStorage.getItem('sidebarHidden') === 'true',
        toggleSidebar() {
            this.sidebarHidden = !this.sidebarHidden;
            localStorage.setItem('sidebarHidden', this.sidebarHidden);
        },
        darkMode: localStorage.getItem('darkMode') === 'true',
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            if (window.updateDashboardCharts && this.chartData) {
                window.updateDashboardCharts(this.chartData);
            }
        },
        selectedMonth: '{{ $currentMonth ?? '' }}', 
        selectedRegional: '{{ $currentRegional ?? '' }}', 
        selectedSegment: '{{ $currentSegment ?? '' }}',
        stats: {{ json_encode($initialData['stats'] ?? []) }},
        chartData: {{ json_encode($initialData['charts'] ?? []) }},
        async fetchData() {
            if(!this.selectedMonth) return;
            try {
                const res = await fetch(`{{ route('dashboard.api') }}?month=${this.selectedMonth}&regional=${this.selectedRegional}&segment=${this.selectedSegment}`);
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
        <x-sidebar />

        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300" :class="sidebarHidden ? 'pl-0' : 'pl-[250px]'">
            <x-navbar :months="$months ?? []" :regionals="$regionals ?? []" :segments="$segments ?? []" />

            <main class="flex-1 p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Pakai JSON.parse dibungkus kutip biar text editor lu gak pusing membaca syntax Laravel
        window.__initialChartData = JSON.parse('{!! json_encode($initialData['charts'] ?? []) !!}');
    </script>

    @if(request()->is('/'))
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @endif


    
    @include('components.profile-modals')
</body>

</html>