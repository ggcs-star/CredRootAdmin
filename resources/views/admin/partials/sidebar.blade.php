<aside class="w-72 bg-gradient-to-b from-slate-900 via-slate-900 to-slate-800 text-white shadow-2xl flex flex-col h-screen sticky top-0 flex-shrink-0 overflow-hidden">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-6 h-20 border-b border-white/10">

        <div class="w-12 h-12 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
            <i class="fas fa-shield-halved text-blue-400 text-xl"></i>
        </div>

        <div>
            <h2 class="text-xl font-bold tracking-tight">
                CredRoot
            </h2>

            <p class="text-[10px] text-white/50 uppercase tracking-[0.2em]">
                Admin Panel
            </p>
        </div>

    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-5 sidebar-scroll">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 text-white font-medium shadow-lg shadow-blue-500/10 mb-2">

            <i class="fas fa-th-large w-5 text-center text-blue-400"></i>

            <span>Dashboard</span>

            <span class="ml-auto bg-white/20 px-2.5 py-0.5 rounded-full text-xs">
                12
            </span>
        </a>

        <div class="border-t border-white/10 my-5"></div>

        <!-- Management -->
        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-white/30 font-semibold">
            Management
        </p>

        <a href="#"
           class="sidebar-link">
            <i class="fas fa-file-invoice"></i>
            <span>Loan Applications</span>

            <span class="ml-auto bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded-full text-xs">
                5
            </span>
        </a>

        <a href="#"
           class="sidebar-link">
            <i class="fas fa-users"></i>
            <span>Customers</span>

            <span class="ml-auto text-xs text-white/30">
                248
            </span>
        </a>

        <a href="{{ route('admin.banks.index') }}"
           class="sidebar-link">
            <i class="fas fa-university"></i>
            <span>Lenders</span>

            <span class="ml-auto text-xs text-white/30">
                32
            </span>
        </a>

        <div class="border-t border-white/10 my-5"></div>

        <!-- Master Data -->
        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-white/30 font-semibold">
            Master Data
        </p>

        <a href="{{ route('loan-types.index') }}"
           class="sidebar-link">
            <i class="fas fa-money-check-alt"></i>
            <span>Loan Types</span>
        </a>

        <a href="{{ route('lead-statuses.index') }}"
           class="sidebar-link">
            <i class="fas fa-stream"></i>
            <span>Lead Statuses</span>
        </a>

        <a href="{{ route('document-masters.index') }}"
           class="sidebar-link">
            <i class="fas fa-file-alt"></i>
            <span>Document Masters</span>
        </a>

        <div class="border-t border-white/10 my-5"></div>

        <!-- Analytics -->
        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-white/30 font-semibold">
            Analytics
        </p>

        <a href="#" class="sidebar-link">
            <i class="fas fa-chart-line"></i>
            <span>Reports</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="fas fa-chart-pie"></i>
            <span>Analytics</span>

            <span class="ml-auto bg-green-500/20 text-green-400 px-2 py-0.5 rounded-full text-xs">
                New
            </span>
        </a>

        <div class="border-t border-white/10 my-5"></div>

        <!-- System -->
        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-white/30 font-semibold">
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

        <div class="bg-white/5 rounded-xl p-3">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A',0,2)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </p>

                    <p class="text-[11px] text-white/40 truncate">
                        {{ auth()->user()->email ?? 'admin@credroot.com' }}
                    </p>
                </div>

                <button class="text-white/30 hover:text-white transition">
                    <i class="fas fa-chevron-right"></i>
                </button>

            </div>

        </div>

    </div>

</aside>

<style>
.sidebar-link{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 16px;
    border-radius:12px;
    color:rgba(255,255,255,.65);
    font-size:14px;
    font-weight:500;
    transition:.25s;
    margin-bottom:4px;
}

.sidebar-link:hover{
    background:rgba(255,255,255,.05);
    color:#fff;
}

.sidebar-link i{
    width:20px;
    text-align:center;
    color:rgba(255,255,255,.4);
}

.sidebar-link:hover i{
    color:#60a5fa;
}

.sidebar-scroll{
    scrollbar-width:none;
    -ms-overflow-style:none;
}

.sidebar-scroll::-webkit-scrollbar{
    display:none;
}
</style>