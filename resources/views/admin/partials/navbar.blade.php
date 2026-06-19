<header class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 backdrop-blur-md border-b border-blue-400/20 sticky top-0 z-30 shadow-lg shadow-blue-500/10">
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 sm:py-4">

        <!-- Left: Page Title + Breadcrumb -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button 
                onclick="toggleSidebar()" 
                class="lg:hidden text-blue-100 hover:text-white transition-colors"
                aria-label="Toggle sidebar"
            >
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-white tracking-tight">
                    @yield('page-title', 'Dashboard')
                </h1>
                <p class="hidden sm:block text-xs text-blue-200 mt-0.5">
                    @yield('page-subtitle', 'Welcome back, ' . auth()->user()->name)
                </p>
            </div>
        </div>

        <!-- Right: Actions + User -->
        <div class="flex items-center gap-2 sm:gap-4">

            <!-- Quick Actions -->
            <div class="hidden md:flex items-center gap-2">
                <!-- Notifications -->
                <button class="relative p-2 rounded-xl hover:bg-white/10 transition-all text-blue-100 hover:text-white group">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-blue-700 animate-pulse"></span>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-lg shadow-red-500/30">
                        3
                    </span>
                </button>
                
                <!-- Help -->
                <button class="p-2 rounded-xl hover:bg-white/10 transition-all text-blue-100 hover:text-white">
                    <i class="fas fa-question-circle text-lg"></i>
                </button>
                
                <!-- Fullscreen -->
                <button onclick="toggleFullscreen()" class="p-2 rounded-xl hover:bg-white/10 transition-all text-blue-100 hover:text-white">
                    <i class="fas fa-expand text-lg"></i>
                </button>
            </div>

            <!-- Divider -->
            <div class="hidden md:block w-px h-8 bg-white/20"></div>

            <!-- User Info -->
            <div class="flex items-center gap-3">
                <!-- Avatar with online indicator -->
                <div class="relative">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-300 to-white flex items-center justify-center text-blue-700 text-sm font-bold shadow-lg shadow-blue-500/30 ring-2 ring-white/20">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-blue-700 rounded-full"></span>
                </div>

                <!-- User Name (Desktop) -->
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-white leading-tight">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-xs text-blue-200 leading-tight flex items-center gap-1">
                        <i class="fas fa-shield-alt text-[10px]"></i>
                        {{ auth()->user()->email ?? 'admin@example.com' }}
                    </p>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl text-sm font-medium transition-all hover:scale-105 active:scale-95 backdrop-blur-sm border border-white/10"
                        onclick="return confirm('Are you sure you want to logout?')"
                    >
                        <i class="fas fa-sign-out-alt text-xs sm:text-sm"></i>
                        <span class="hidden sm:inline">Logout</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</header>

<!-- Stats Bar (Optional) -->
@hasSection('stats')
<div class="bg-blue-50/80 backdrop-blur-sm border-b border-blue-100/60 px-4 sm:px-6 lg:px-8 py-3">
    <div class="flex flex-wrap items-center gap-4 sm:gap-6">
        @yield('stats')
    </div>
</div>
@endif

<script>
    // Sidebar toggle for mobile
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar') || document.querySelector('aside');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar) {
            sidebar.classList.toggle('-translate-x-full');
            if (overlay) {
                overlay.classList.toggle('hidden');
            }
            document.body.style.overflow = sidebar.classList.contains('-translate-x-full') ? '' : 'hidden';
        }
    }

    // Fullscreen toggle
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }

    // Close sidebar on overlay click
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('sidebarOverlay');
        if (overlay) {
            overlay.addEventListener('click', function() {
                const sidebar = document.getElementById('sidebar') || document.querySelector('aside');
                if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    toggleSidebar();
                }
            });
        }
    });

    // Auto-close sidebar on resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1024) {
            const sidebar = document.getElementById('sidebar') || document.querySelector('aside');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
                if (overlay) overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    });
</script>