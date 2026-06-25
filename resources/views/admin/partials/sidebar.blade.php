<aside
    class="w-72 bg-gradient-to-b from-blue-700 via-blue-800 to-blue-900 text-white shadow-2xl flex flex-col h-screen sticky top-0 flex-shrink-0 overflow-hidden">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 h-20 border-b border-white/10">

        <div
            class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
            <i class="fas fa-shield-halved text-blue-200 text-xl"></i>
        </div>

        <div>
            <h2 class="text-xl font-bold tracking-tight text-white">
                CredRoot
            </h2>

            <p class="text-[10px] text-blue-200/60 uppercase tracking-[0.2em]">
                Admin Panel
            </p>
        </div>

    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-5 sidebar-scroll">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/20 text-white font-medium shadow-lg shadow-blue-500/20 mb-2">

            <i class="fas fa-th-large w-5 text-center text-blue-200"></i>

            <span>Dashboard</span>

            <span class="ml-auto bg-white/20 px-2.5 py-0.5 rounded-full text-xs text-white">
                12
            </span>
        </a>

        <div class="border-t border-white/10 my-5"></div>

        <!-- Management -->
        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-blue-200/50 font-semibold">
            Management
        </p>

        <a href="{{ route('leads.index') }}" class="sidebar-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
            <i class="fas fa-file-invoice"></i>
            <span>Loan Applications</span>

            <span class="ml-auto bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded-full text-xs">
                {{ \App\Models\Lead::count() }}
            </span>
        </a>

        <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
    <i class="fas fa-users"></i>
    <span>Customers</span>

    <span class="ml-auto text-xs text-blue-200/50">
        {{ \App\Models\User::role('user')->count() }}
    </span>
</a>

        <a href="{{ route('admin.banks.index') }}" class="sidebar-link">
            <i class="fas fa-university"></i>
            <span>Lenders</span>

            <span class="ml-auto text-xs text-blue-200/50">
                32
            </span>
        </a>

        <div class="border-t border-white/10 my-5"></div>

        <!-- Master Data -->
        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-blue-200/50 font-semibold">
            Master Data
        </p>

        <a href="{{ route('loan-types.index') }}" class="sidebar-link">
            <i class="fas fa-money-check-alt"></i>
            <span>Loan Types</span>
        </a>

        <a href="{{ route('lead-statuses.index') }}" class="sidebar-link">
            <i class="fas fa-stream"></i>
            <span>Lead Status</span>
        </a>

        <a href="{{ route('document-masters.index') }}" class="sidebar-link">
            <i class="fas fa-file-alt"></i>
            <span>Document Masters</span>
        </a>

        <div class="border-t border-white/10 my-5"></div>

        <!-- Analytics -->
        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-blue-200/50 font-semibold">
            Analytics
        </p>

        <a href="#" class="sidebar-link">
            <i class="fas fa-chart-line"></i>
            <span>Reports</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="fas fa-chart-pie"></i>
            <span>Analytics</span>

            <span class="ml-auto bg-green-400/20 text-green-300 px-2 py-0.5 rounded-full text-xs">
                New
            </span>
        </a>

        <div class="border-t border-white/10 my-5"></div>

        <!-- System -->
        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-blue-200/50 font-semibold">
            System
        </p>

        <a href="#" class="sidebar-link">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="fas fa-question-circle"></i>
            <span>Help Center</span>
        </a>

    </nav>

    <!-- Footer -->
    <div class="border-t border-white/10 p-4">

        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3">

            <div class="flex items-center gap-3">

                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-blue-500/30">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </p>

                    <p class="text-[11px] text-blue-200/50 truncate">
                        {{ auth()->user()->email ?? 'admin@credroot.com' }}
                    </p>
                </div>

                <button class="text-blue-200/40 hover:text-white transition">
                    <i class="fas fa-chevron-right"></i>
                </button>

            </div>

        </div>

    </div>

</aside>

<style>
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        font-weight: 500;
        transition: all 0.25s ease;
        margin-bottom: 4px;
        position: relative;
    }

    .sidebar-link:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        transform: translateX(4px);
    }

    .sidebar-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: linear-gradient(to bottom, #60a5fa, #3b82f6);
        border-radius: 0 4px 4px 0;
        transition: height 0.3s ease;
    }

    .sidebar-link:hover::before {
        height: 70%;
    }

    .sidebar-link.active {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
    }

    .sidebar-link.active::before {
        height: 70%;
    }

    .sidebar-link i {
        width: 20px;
        text-align: center;
        color: rgba(255, 255, 255, 0.5);
        transition: color 0.3s ease;
    }

    .sidebar-link:hover i {
        color: #93c5fd;
    }

    .sidebar-link.active i {
        color: #93c5fd;
    }

    .sidebar-scroll {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .sidebar-scroll::-webkit-scrollbar {
        display: none;
    }

    /* Active state indicator pulse animation */
    @keyframes pulse-dot {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    .status-pulse {
        animation: pulse-dot 2s ease-in-out infinite;
    }
</style>