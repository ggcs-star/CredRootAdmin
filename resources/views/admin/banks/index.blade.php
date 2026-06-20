@extends('admin.layouts.app')

@section('title', 'Banks / Lenders')
@section('page-title', 'Bank Management')

@section('content')

<div class="max-w-7xl mx-auto px-3 sm:px-4">

    {{-- Stats Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Total Banks</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $banks->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-university text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $activeBanks ?? $banks->where('status', 1)->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Inactive</p>
                    <p class="text-2xl font-bold text-red-600">{{ $inactiveBanks ?? $banks->where('status', 0)->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Avg Interest</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $avgInterest ?? '11.5' }}%</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-percentage text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-lg shadow-blue-500/5 border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-5 sm:px-8 py-5 sm:py-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 via-indigo-50/50 to-blue-50">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                        <i class="fas fa-university text-sm sm:text-base"></i>
                    </span>
                    <span>Bank Management</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1 ml-0 sm:ml-14">
                    <i class="fas fa-info-circle text-blue-400 mr-1"></i>
                    Manage all business loan partner banks
                </p>
            </div>
            <div class="flex gap-2.5">
                <button onclick="window.location.reload()" 
                        class="px-4 py-2.5 border border-slate-300 text-slate-600 rounded-xl hover:bg-slate-50 transition-all font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    <span class="hidden xs:inline">Refresh</span>
                </button>
                <a href="{{ route('admin.banks.create') }}"
                   class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center gap-2 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Add Bank</span>
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mx-5 sm:mx-8 mt-4 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 flex items-start gap-3">
                <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
                <div>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-green-500 hover:text-green-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        {{-- Search & Filter --}}
        <div class="px-5 sm:px-8 py-4 border-b border-slate-200 bg-slate-50/50">
            <form method="GET" action="{{ route('admin.banks.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by bank name, code, or contact..."
                           class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
                <div class="flex gap-2">
                    <select name="status" 
                            class="border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all bg-white">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium text-sm">
                        <i class="fas fa-filter mr-1.5"></i> Filter
                    </button>
                    <a href="{{ route('admin.banks.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl hover:bg-slate-50 transition text-sm flex items-center">
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
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">
                            <span class="hidden sm:inline">Logo</span>
                            <span class="sm:hidden">Logo</span>
                        </th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">
                            Bank Details
                        </th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden md:table-cell">
                            Contact
                        </th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden lg:table-cell">
                            Loan Range
                        </th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden xl:table-cell">
                            Interest Rate
                        </th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider text-right">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($banks as $bank)
                        <tr class="hover:bg-blue-50/50 transition group">
                            <!-- Logo -->
                            <td class="px-4 sm:px-6 py-4">
                                @if($bank->logo)
                                    <img src="{{ asset('storage/'.$bank->logo) }}"
                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl object-cover shadow-sm ring-1 ring-slate-200"
                                         alt="{{ $bank->name }}">
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-xl shadow-sm ring-1 ring-slate-200">
                                        🏦
                                    </div>
                                @endif
                            </td>

                            <!-- Bank Details -->
                            <td class="px-4 sm:px-6 py-4">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $bank->name }}</p>
                                    <p class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-tag text-[10px]"></i>
                                        {{ $bank->code ?? 'N/A' }}
                                    </p>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="px-4 sm:px-6 py-4 hidden md:table-cell">
                                <div>
                                    <p class="text-slate-700 flex items-center gap-1.5">
                                        <i class="fas fa-user text-slate-400 text-xs"></i>
                                        {{ $bank->contact_person ?? '-' }}
                                    </p>
                                    <p class="text-xs text-slate-400 flex items-center gap-1.5 mt-0.5">
                                        <i class="fas fa-phone text-[10px]"></i>
                                        {{ $bank->phone ?? '-' }}
                                    </p>
                                </div>
                            </td>

                            <!-- Loan Range -->
                            <td class="px-4 sm:px-6 py-4 hidden lg:table-cell">
                                <div>
                                    <p class="font-medium text-slate-800">
                                        ₹{{ number_format($bank->min_loan_amount ?? 0) }}
                                    </p>
                                    <p class="text-xs text-slate-400 flex items-center gap-1">
                                        <i class="fas fa-arrow-right text-[10px]"></i>
                                        ₹{{ number_format($bank->max_loan_amount ?? 0) }}
                                    </p>
                                </div>
                            </td>

                            <!-- Interest Rate -->
                            <td class="px-4 sm:px-6 py-4 hidden xl:table-cell">
                                <div class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 rounded-full">
                                    <span class="font-medium text-blue-700">
                                        {{ $bank->interest_rate_from ?? 0 }}% - {{ $bank->interest_rate_to ?? 0 }}%
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-4 sm:px-6 py-4">
                                @if($bank->status)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex justify-end gap-1.5 sm:gap-2">
                                    <a href="{{ route('admin.banks.edit', $bank->id) }}"
                                       class="px-3 sm:px-4 py-1.5 sm:py-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition font-medium text-xs sm:text-sm flex items-center gap-1">
                                        <i class="fas fa-edit text-xs"></i>
                                        <span class="hidden xs:inline">Edit</span>
                                    </a>
                                    <form action="{{ route('admin.banks.destroy', $bank->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this bank?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 sm:px-4 py-1.5 sm:py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium text-xs sm:text-sm flex items-center gap-1">
                                            <i class="fas fa-trash text-xs"></i>
                                            <span class="hidden xs:inline">Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mb-4">
                                        <i class="fas fa-university text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="text-lg font-semibold text-slate-600">No banks found</p>
                                    <p class="text-sm text-slate-400 mt-1">Start by adding your first bank</p>
                                    <a href="{{ route('admin.banks.create') }}" 
                                       class="mt-4 px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium text-sm">
                                        <i class="fas fa-plus mr-2"></i> Add Bank
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
                Showing {{ $banks->firstItem() ?? 0 }} - {{ $banks->lastItem() ?? 0 }} of {{ $banks->total() }} banks
            </div>
            <div>
                {{ $banks->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

</div>

<script>
    // Auto-hide success message after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.querySelector('.bg-gradient-to-r\\ from-green-50');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease';
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 500);
            }, 5000);
        }
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
        background: #eff6ff;
        border-color: #3b82f6;
        color: #3b82f6;
    }
    .pagination .active .page-link {
        background: #3b82f6;
        border-color: #3b82f6;
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
    }

    /* Touch device optimizations */
    @media (hover: none) {
        .hover\:shadow-lg:hover { box-shadow: none !important; }
        .hover\:-translate-y-0\.5:hover { transform: none !important; }
        .hover\:bg-slate-50:hover { background: inherit !important; }
        .hover\:bg-blue-50\/50:hover { background: inherit !important; }
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