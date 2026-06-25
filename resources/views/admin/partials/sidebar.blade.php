<aside class="w-72 bg-[#f3f3f3] border-r border-gray-200 flex flex-col h-screen sticky top-0 flex-shrink-0 overflow-hidden z-40">

    <div class="flex items-center gap-3 px-6 h-20 border-b border-gray-200">
        <div class="w-12 h-12 bg-white border border-gray-200 rounded-2xl flex items-center justify-center shadow-sm">
            <i class="fas fa-shield-halved text-blue-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-black tracking-tight text-slate-800">
                CredRoot
            </h2>
            <p class="text-[10px] text-slate-500 uppercase tracking-[0.2em] font-bold">
                Admin Panel
            </p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-5 sidebar-scroll">

        <a href="{{ route('admin.dashboard.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white text-blue-600 font-bold shadow-sm border border-gray-100 mb-2">
            <i class="fas fa-th-large w-5 text-center text-blue-600"></i>
            <span>Dashboard</span>
            <span class="ml-auto bg-blue-50 px-2.5 py-0.5 rounded-full text-xs text-blue-600 border border-blue-100">
                12
            </span>
        </a>

        <div class="border-t border-gray-200 my-5"></div>

        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold">
            Management
        </p>

        <a href="{{ route('leads.index') }}" class="sidebar-link {{ request()->routeIs('leads.*') ? 'active' : '' }}">
            <i class="fas fa-file-invoice"></i>
            <span>Loan Applications</span>
            <span class="ml-auto bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full text-xs font-bold">
                {{ \App\Models\Lead::count() }}
            </span>
        </a>

        <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Customers</span>
            <span class="ml-auto text-xs font-bold text-slate-400">
                {{ \App\Models\User::role('user')->count() }}
            </span>
        </a>

        <a href="{{ route('admin.banks.index') }}" class="sidebar-link">
            <i class="fas fa-university"></i>
            <span>Lenders</span>
            <span class="ml-auto text-xs font-bold text-slate-400">
                32
            </span>
        </a>

        <div class="border-t border-gray-200 my-5"></div>

        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold">
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

        <div class="border-t border-gray-200 my-5"></div>

        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold">
            Analytics
        </p>

        <a href="#" class="sidebar-link">
            <i class="fas fa-chart-line"></i>
            <span>Reports</span>
        </a>

        <a href="#" class="sidebar-link">
            <i class="fas fa-chart-pie"></i>
            <span>Analytics</span>
            <span class="ml-auto bg-green-50 text-green-600 border border-green-200 px-2 py-0.5 rounded-full text-xs font-bold">
                New
            </span>
        </a>

        <div class="border-t border-gray-200 my-5"></div>

        <p class="px-4 mb-2 text-[10px] uppercase tracking-[0.2em] text-slate-400 font-bold">
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

    <div class="border-t border-gray-200 p-4">
        <div class="bg-white rounded-xl p-3 border border-gray-200 shadow-sm hover:border-blue-300 transition cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-700 border border-blue-200">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 truncate">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </p>
                    <p class="text-[11px] text-slate-500 font-medium truncate">
                        {{ auth()->user()->email ?? 'admin@credroot.com' }}
                    </p>
                </div>
                <button class="text-slate-400 hover:text-blue-600 transition">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Updated for Light Theme */
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 12px;
        color: #64748b; /* slate-500 */
        font-size: 14px;
        font-weight: 600;
        transition: all 0.25s ease;
        margin-bottom: 4px;
        position: relative;
    }

    .sidebar-link:hover {
        background: #ffffff;
        color: #2563eb; /* blue-600 */
        transform: translateX(4px);
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }

    .sidebar-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: #2563eb;
        border-radius: 0 4px 4px 0;
        transition: height 0.3s ease;
    }

    .sidebar-link:hover::before {
        height: 70%;
    }

    .sidebar-link.active {
        background: #ffffff;
        color: #2563eb;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.08);
        border: 1px solid #e2e8f0;
    }

    .sidebar-link.active::before {
        height: 70%;
    }

    .sidebar-link i {
        width: 20px;
        text-align: center;
        color: #94a3b8; /* slate-400 */
        transition: color 0.3s ease;
    }

    .sidebar-link:hover i,
    .sidebar-link.active i {
        color: #2563eb;
    }

    .sidebar-scroll {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .sidebar-scroll::-webkit-scrollbar {
        display: none;
    }
</style>