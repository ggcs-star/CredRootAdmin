<header class="w-full z-50 border-b border-gray-200 bg-[#f3f3f3] relative sticky top-0">
    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 sm:py-4">

        <div class="flex items-center gap-4">
            <button 
                onclick="toggleSidebar()" 
                class="lg:hidden text-slate-500 hover:text-blue-600 transition-colors"
                aria-label="Toggle sidebar"
            >
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <div>
                <h1 class="text-lg sm:text-xl font-black text-slate-800 tracking-tight">
                    @yield('page-title', 'Dashboard')
                </h1>
                <p class="hidden sm:block text-xs text-slate-500 font-medium mt-0.5">
                    @yield('page-subtitle', 'Welcome back, ' . auth()->user()->name)
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-4">

            <div class="hidden md:flex items-center gap-1">
                <button class="relative p-2.5 rounded-xl hover:bg-white hover:shadow-sm border border-transparent hover:border-gray-200 transition-all text-slate-500 hover:text-blue-600 group">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-sm">
                        3
                    </span>
                </button>
                
                <button class="p-2.5 rounded-xl hover:bg-white hover:shadow-sm border border-transparent hover:border-gray-200 transition-all text-slate-500 hover:text-blue-600">
                    <i class="fas fa-question-circle text-lg"></i>
                </button>
                
                <button onclick="toggleFullscreen()" class="p-2.5 rounded-xl hover:bg-white hover:shadow-sm border border-transparent hover:border-gray-200 transition-all text-slate-500 hover:text-blue-600">
                    <i class="fas fa-expand text-lg"></i>
                </button>
            </div>

            <div class="hidden md:block w-px h-8 bg-gray-300 mx-2"></div>

            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-sm font-bold border border-blue-200">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#f3f3f3] rounded-full"></span>
                </div>

                <div class="hidden sm:block">
                    <p class="text-sm font-bold text-slate-800 leading-tight">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-[11px] text-slate-500 font-medium leading-tight mt-0.5">
                        {{ auth()->user()->email ?? 'admin@example.com' }}
                    </p>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST" class="inline ml-1">
                    @csrf
                    <button 
                        type="submit" 
                        class="flex items-center gap-2 px-3 sm:px-4 py-2 bg-white hover:bg-red-50 text-slate-600 hover:text-red-600 rounded-xl text-sm font-bold transition-all border border-gray-200 hover:border-red-200 shadow-sm"
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