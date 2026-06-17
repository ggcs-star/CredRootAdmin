<aside class="w-72 bg-gradient-to-b from-slate-900 to-slate-800 text-white shadow-2xl flex flex-col h-screen sticky top-0 overflow-y-auto">
    
    <!-- Sidebar Header -->
    <div class="flex items-center gap-3 px-6 h-20 border-b border-white/10 flex-shrink-0">
        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
            <i class="fas fa-shield-halved text-blue-400 text-xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold tracking-tight">CredRoot</h2>
            <p class="text-[10px] text-white/50 uppercase tracking-wider">Admin Panel</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium bg-white/10 text-white shadow-lg shadow-blue-500/10">
            <i class="fas fa-th-large w-5 text-center text-blue-400"></i>
            <span>Dashboard</span>
            <span class="ml-auto bg-white/20 px-2.5 py-0.5 rounded-full text-xs font-semibold">12</span>
        </a>

        <div class="my-4 border-t border-white/10"></div>

        <!-- Management Section -->
        <p class="px-4 text-[10px] font-semibold text-white/30 uppercase tracking-wider mt-6 mb-2">Management</p>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
            <i class="fas fa-file-invoice w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
            <span>Loan Applications</span>
            <span class="ml-auto bg-amber-500/20 text-amber-400 px-2.5 py-0.5 rounded-full text-xs font-semibold">5</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
            <i class="fas fa-users w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
            <span>Customers</span>
            <span class="ml-auto text-white/30 text-xs">248</span>
        </a>

        <a href="{{ route('admin.banks.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
            <i class="fas fa-university w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
            <span>Lenders</span>
            <span class="ml-auto text-white/30 text-xs">32</span>
        </a>



        <div class="my-4 border-t border-white/10"></div>

<p class="px-4 text-[10px] font-semibold text-white/30 uppercase tracking-wider mt-6 mb-2">
    Master Data
</p>

<a href="{{ route('loan-types.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
    <i class="fas fa-money-check-alt w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
    <span>Loan Types</span>
</a>

<a href="{{ route('lead-statuses.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
    <i class="fas fa-stream w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
    <span>Lead Statuses</span>
</a>

<a href="{{ route('document-masters.index') }}"
   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
    <i class="fas fa-file-alt w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
    <span>Document Masters</span>
</a>



        <div class="my-4 border-t border-white/10"></div>

        <!-- Analytics Section -->
        <p class="px-4 text-[10px] font-semibold text-white/30 uppercase tracking-wider mt-6 mb-2">Analytics</p>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
            <i class="fas fa-chart-line w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
            <span>Reports</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
            <i class="fas fa-chart-pie w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
            <span>Analytics</span>
            <span class="ml-auto bg-green-500/20 text-green-400 px-2.5 py-0.5 rounded-full text-xs font-semibold">New</span>
        </a>

        <div class="my-4 border-t border-white/10"></div>

        <!-- System Section -->
        <p class="px-4 text-[10px] font-semibold text-white/30 uppercase tracking-wider mt-6 mb-2">System</p>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
            <i class="fas fa-cog w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
            <span>Settings</span>
        </a>

        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 transition-all group">
            <i class="fas fa-question-circle w-5 text-center text-white/40 group-hover:text-blue-400 transition-colors"></i>
            <span>Help Center</span>
        </a>

    </nav>

    <!-- Sidebar Footer -->
    <div class="border-t border-white/10 p-4 flex-shrink-0">
        <div class="bg-white/5 rounded-xl p-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-lg">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-white/40 truncate">{{ auth()->user()->email ?? 'admin@credroot.com' }}</p>
                </div>
                <form action="#" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="text-white/30 hover:text-white/60 transition-colors" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

</aside>

<style>
    /* Custom scrollbar */
    aside::-webkit-scrollbar {
        width: 4px;
    }
    aside::-webkit-scrollbar-track {
        background: transparent;
    }
    aside::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 10px;
    }
    aside::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.25);
    }
</style>