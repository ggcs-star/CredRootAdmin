@extends('admin.layouts.app')

@section('title', 'Lead Status')
@section('page-title', 'Lead Status')
@section('page-subtitle', 'Manage lead workflow status')

@section('content')

<div class="max-w-7xl mx-auto px-3 sm:px-4">

    {{-- Stats Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Total Status</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $statuses->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-tasks text-indigo-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">System Locked</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $statuses->where('is_system_locked', 1)->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-lock text-amber-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Active Pipeline</p>
                    <p class="text-2xl font-bold text-green-600">{{ $statuses->where('is_system_locked', 0)->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-play-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Last Added</p>
                    <p class="text-sm font-bold text-purple-600 truncate">
                        {{ $statuses->first()?->created_at?->format('d M Y') ?? 'N/A' }}
                    </p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-calendar-plus text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
            <div>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.style.display='none'" class="ml-auto text-green-500 hover:text-green-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 p-4 rounded-xl bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 text-red-700 flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
            <div>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.style.display='none'" class="ml-auto text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg shadow-indigo-500/5 border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-5 sm:px-8 py-5 sm:py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-50 via-blue-50/50 to-indigo-50">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-tasks text-base sm:text-lg"></i>
                    </span>
                    <span>Lead Status</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1 ml-0 sm:ml-14">
                    <i class="fas fa-info-circle text-indigo-400 mr-1"></i>
                    Manage all lead pipeline stages
                </p>
            </div>
            <div class="flex gap-2.5">
                <button onclick="window.location.reload()" 
                        class="px-4 py-2.5 border border-slate-300 text-slate-600 rounded-xl hover:bg-slate-50 transition-all font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    <span class="hidden xs:inline">Refresh</span>
                </button>
                <a href="{{ route('lead-statuses.create') }}"
                   class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center gap-2 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Add Lead Status</span>
                </a>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="px-5 sm:px-8 py-4 border-b border-slate-200 bg-slate-50/50">
            <form method="GET" action="{{ route('lead-statuses.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by status name or code..."
                           class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
                <div class="flex gap-2">
                    <select name="locked" 
                            class="border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all bg-white">
                        <option value="">All Status</option>
                        <option value="1" {{ request('locked') == '1' ? 'selected' : '' }}>System Locked</option>
                        <option value="0" {{ request('locked') == '0' ? 'selected' : '' }}>Active Pipeline</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-medium text-sm">
                        <i class="fas fa-filter mr-1.5"></i> Filter
                    </button>
                    <a href="{{ route('lead-statuses.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl hover:bg-slate-50 transition text-sm flex items-center">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left">
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">#</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Status Details</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Color</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden md:table-cell">Sort Order</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Lock</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($statuses as $status)
                        <tr class="hover:bg-indigo-50/50 transition group">
                            <td class="px-4 sm:px-6 py-4 text-slate-400 font-medium">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: {{ $status->color ?? '#6366f1' }}"></span>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $status->name }}</p>
                                        <p class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                            <i class="fas fa-code text-[10px]"></i>
                                            <span class="font-mono">{{ $status->internal_code }}</span>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full border border-slate-200 shadow-sm" 
                                          style="background-color: {{ $status->color ?? '#6366f1' }}"></span>
                                    <span class="text-xs text-slate-500 font-mono hidden sm:inline">{{ $status->color ?? '#6366f1' }}</span>
                                </div>
                            </td>

                            <td class="px-4 sm:px-6 py-4 hidden md:table-cell">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold text-sm">
                                    {{ $status->sort_order ?? 0 }}
                                </span>
                            </td>

                            <td class="px-4 sm:px-6 py-4">
                                @if($status->is_system_locked)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-medium border border-amber-200">
                                        <i class="fas fa-lock text-[10px]"></i>
                                        <span class="hidden xs:inline">Locked</span>
                                        <span class="xs:hidden">🔒</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium border border-green-200">
                                        <i class="fas fa-unlock text-[10px]"></i>
                                        <span class="hidden xs:inline">Active</span>
                                        <span class="xs:hidden">🔓</span>
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                    <a href="{{ route('lead-statuses.edit', $status->id) }}"
                                       class="px-3 sm:px-4 py-1.5 sm:py-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition font-medium text-xs sm:text-sm flex items-center gap-1">
                                        <i class="fas fa-edit text-xs"></i>
                                        <span class="hidden xs:inline">Edit</span>
                                    </a>
                                    
                                    @if(!$status->is_system_locked)
                                        <form action="{{ route('lead-statuses.destroy', $status->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this status?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 sm:px-4 py-1.5 sm:py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium text-xs sm:text-sm flex items-center gap-1">
                                                <i class="fas fa-trash text-xs"></i>
                                                <span class="hidden xs:inline">Delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-slate-100 text-slate-400 rounded-lg text-xs sm:text-sm flex items-center gap-1 cursor-not-allowed" title="System locked statuses cannot be deleted">
                                            <i class="fas fa-lock text-xs"></i>
                                            <span class="hidden sm:inline">Locked</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-tasks text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-slate-600">No lead status found</p>
                                    <p class="text-sm text-slate-400 mt-1">Start by adding your first status</p>
                                    <a href="{{ route('lead-statuses.create') }}" 
                                       class="mt-4 px-6 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-medium text-sm">
                                        <i class="fas fa-plus mr-2"></i> Add Lead Status
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer with Pagination --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 sm:px-8 py-4 border-t border-slate-200 bg-slate-50/50">
            <div class="text-sm text-slate-500 text-center sm:text-left">
                Showing {{ $statuses->firstItem() ?? 0 }} - {{ $statuses->lastItem() ?? 0 }} of {{ $statuses->total() }} statuses
            </div>
            <div>
                {{ $statuses->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

</div>

<script>
    // Auto-hide success/error messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const messages = document.querySelectorAll('.bg-gradient-to-r');
        messages.forEach(msg => {
            setTimeout(() => {
                msg.style.transition = 'opacity 0.5s ease';
                msg.style.opacity = '0';
                setTimeout(() => {
                    msg.style.display = 'none';
                }, 500);
            }, 5000);
        });
    });
</script>

<style>
    /* Custom Pagination Styling */
    .pagination {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .pagination .page-item {
        display: inline-block;
    }
    .pagination .page-link {
        padding: 6px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        color: #475569;
        transition: all 0.2s ease;
        font-size: 13px;
        background: white;
    }
    .pagination .page-link:hover {
        background: #eef2ff;
        border-color: #6366f1;
        color: #6366f1;
    }
    .pagination .active .page-link {
        background: #6366f1;
        border-color: #6366f1;
        color: white;
    }
    .pagination .disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8fafc;
    }

    /* Extra small screens */
    @media (max-width: 480px) {
        .xs\:inline { display: inline !important; }
        .xs\:hidden { display: none !important; }
        .xs\:flex { display: flex !important; }
    }

    /* Touch device optimizations */
    @media (hover: none) {
        .hover\:shadow-lg:hover { box-shadow: none !important; }
        .hover\:-translate-y-0\.5:hover { transform: none !important; }
        .hover\:bg-slate-50:hover { background: inherit !important; }
        .hover\:bg-indigo-50\/50:hover { background: inherit !important; }
    }

    /* Smooth transitions */
    .transition-all {
        transition: all 0.2s ease;
    }

    /* Safe area support */
    @supports (padding: max(0px)) {
        .px-3 { 
            padding-left: max(0.75rem, env(safe-area-inset-left)); 
            padding-right: max(0.75rem, env(safe-area-inset-right)); 
        }
    }
</style>

@endsection