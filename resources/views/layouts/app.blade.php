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
        localStorage.removeItem('sidebarHidden');
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

        <div class="flex-1 flex flex-col min-w-0 pl-[250px]">
            <x-navbar :months="$months ?? []" :regionals="$regionals ?? []" :segments="$segments ?? []" />

            <main id="main-content" class="flex-1 p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        window.__initialChartData = JSON.parse('{!! json_encode($initialData['charts'] ?? []) !!}');
    </script>

    <script src="{{ asset('js/dashboard.js') }}"></script>

    <!-- Instant SPA Navigation Engine -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainContent = document.getElementById('main-content');

            async function navigateTo(url, pushState = true) {
                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) {
                        window.location.href = url;
                        return;
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Update Title
                    const newTitle = doc.querySelector('title');
                    if (newTitle) document.title = newTitle.innerText;

                    // Update Main Content INSTANTLY
                    const newMain = doc.querySelector('#main-content');
                    if (newMain && mainContent) {
                        mainContent.innerHTML = newMain.innerHTML;
                    }

                    // Update Navbar Title Header
                    const newNavbarTitle = doc.querySelector('header h2');
                    const currentNavbarTitle = document.querySelector('header h2');
                    if (newNavbarTitle && currentNavbarTitle) {
                        currentNavbarTitle.innerHTML = newNavbarTitle.innerHTML;
                    }

                    // Update Sidebar Active Links
                    const newSidebar = doc.querySelector('aside');
                    const currentSidebar = document.querySelector('aside');
                    if (newSidebar && currentSidebar) {
                        currentSidebar.innerHTML = newSidebar.innerHTML;
                    }

                    // Push State
                    if (pushState) {
                        history.pushState(null, '', url);
                    }

                    // Scroll to top
                    window.scrollTo(0, 0);

                    // Re-init scripts if navigating to dashboard
                    const parsedUrl = new URL(url, window.location.origin);
                    if (parsedUrl.pathname === '/' || parsedUrl.pathname === '/dashboard') {
                        if (window.initDashboardCharts && window.__initialChartData) {
                            setTimeout(() => {
                                window.initDashboardCharts(window.__initialChartData);
                            }, 50);
                        }
                    }

                    // Re-init Alpine v3 components safely
                    if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                        window.Alpine.initTree(mainContent);
                        if (currentSidebar) window.Alpine.initTree(currentSidebar);
                    }

                } catch (err) {
                    console.error('SPA navigation error:', err);
                }
            }

            // Intercept internal link clicks
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.getAttribute('target') === '_blank') {
                    return;
                }

                const targetUrl = new URL(href, window.location.origin);
                if (targetUrl.origin === window.location.origin && !link.hasAttribute('download') && !link.classList.contains('no-smooth')) {
                    e.preventDefault();
                    navigateTo(targetUrl.href);
                }
            });

            // Handle browser Back / Forward buttons
            window.addEventListener('popstate', () => {
                navigateTo(window.location.href, false);
            });
        });
    </script>

    @include('components.profile-modals')
</body>

</html>