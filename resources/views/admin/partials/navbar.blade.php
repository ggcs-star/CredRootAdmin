<header class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30">
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 sm:py-4">

        <!-- Left: Page Title + Breadcrumb -->
        <div class="flex items-center gap-4">
            <!-- Mobile Menu Toggle -->
            <button 
                onclick="toggleSidebar()" 
                class="lg:hidden text-slate-600 hover:text-slate-900 transition-colors"
                aria-label="Toggle sidebar"
            >
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                    @yield('page-title', 'Dashboard')
                </h1>
                <p class="hidden sm:block text-xs text-slate-400 mt-0.5">
                    @yield('page-subtitle', 'Welcome back, ' . auth()->user()->name)
                </p>
            </div>
        </div>

        <!-- Right: Actions + User -->
        <div class="flex items-center gap-2 sm:gap-4">

            <!-- Quick Actions -->
            <div class="hidden md:flex items-center gap-2">
                <button class="p-2 rounded-xl hover:bg-slate-100 transition-colors text-slate-500 hover:text-slate-700 relative">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
                <button class="p-2 rounded-xl hover:bg-slate-100 transition-colors text-slate-500 hover:text-slate-700">
                    <i class="fas fa-question-circle text-lg"></i>
                </button>
            </div>

            <!-- Divider -->
            <div class="hidden md:block w-px h-8 bg-slate-200"></div>

            <!-- User Info -->
            <div class="flex items-center gap-3">
                <!-- Avatar -->
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-md shadow-blue-500/20">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>

                <!-- User Name (Desktop) -->
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-slate-700 leading-tight">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-xs text-slate-400 leading-tight">
                        {{ auth()->user()->email ?? 'admin@example.com' }}
                    </p>
                </div>

                <!-- Logout Button -->
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl text-sm font-medium transition-all hover:scale-105 active:scale-95"
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
<div class="bg-white/60 backdrop-blur-sm border-b border-slate-200/60 px-4 sm:px-6 lg:px-8 py-3">
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
</script>