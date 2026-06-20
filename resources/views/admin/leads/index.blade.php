@extends('admin.layouts.app')

@section('title', 'Leads Management')
@section('page-title', 'Leads Management')

@section('content')

<div class="space-y-6">

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-blue-500">
            <p class="text-xs text-slate-400 uppercase tracking-wider">Total Leads</p>
            <p class="text-2xl font-bold text-slate-800">{{ $leads->total() }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-green-500">
            <p class="text-xs text-slate-400 uppercase tracking-wider">New Leads</p>
            <p class="text-2xl font-bold text-slate-800">{{ $newLeads ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-yellow-500">
            <p class="text-xs text-slate-400 uppercase tracking-wider">In Progress</p>
            <p class="text-2xl font-bold text-slate-800">{{ $inProgressLeads ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-purple-500">
            <p class="text-xs text-slate-400 uppercase tracking-wider">Converted</p>
            <p class="text-2xl font-bold text-slate-800">{{ $convertedLeads ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border-l-4 border-red-500">
            <p class="text-xs text-slate-400 uppercase tracking-wider">Lost</p>
            <p class="text-2xl font-bold text-slate-800">{{ $lostLeads ?? 0 }}</p>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('leads.index') }}" class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by lead number, user, company..."
                           class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
            </div>
            <div class="w-48">
                <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    <option value="">All Statuses</option>
                    @foreach($statuses ?? [] as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-40">
                <select name="loan_type" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                    <option value="">All Loan Types</option>
                    @foreach($loanTypes ?? [] as $type)
                        <option value="{{ $type->id }}" {{ request('loan_type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('leads.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    <i class="fas fa-undo"></i>
                </a>
            </div>
            <div class="ml-auto flex gap-2">
              
                <button onclick="exportLeads()" class="px-4 py-2 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    <i class="fas fa-file-export"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- Leads Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Table Header --}}
        <div class="p-4 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-users text-blue-600"></i>
                    Leads Management
                </h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Showing {{ $leads->firstItem() ?? 0 }} - {{ $leads->lastItem() ?? 0 }} of {{ $leads->total() }} leads
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400">
                    Last updated: {{ now()->format('d M Y, h:i A') }}
                </span>
                <button onclick="refreshTable()" class="text-blue-600 hover:text-blue-800 transition">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            <a href="{{ route('leads.index', array_merge(request()->query(), ['sort' => 'lead_number', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-blue-600 flex items-center gap-1">
                                Lead No
                                @if(request('sort') == 'lead_number')
                                    <i class="fas fa-chevron-{{ request('direction') == 'asc' ? 'up' : 'down' }} text-xs"></i>
                                @endif
                            </a>
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            User
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Company
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Loan Type
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="text-left py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Created
                        </th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($leads as $lead)
                    <tr class="hover:bg-blue-50/50 transition group">
                        <td class="py-3 px-4">
                            <input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        </td>
                        <td class="py-3 px-4 font-medium text-blue-600">
                            <span class="inline-flex items-center gap-1">
                                <i class="fas fa-hashtag text-xs text-slate-400"></i>
                                {{ $lead->lead_number }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($lead->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $lead->user->name ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ $lead->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div>
                                <p class="text-slate-800">{{ $lead->company->company_name ?? '-' }}</p>
                                <p class="text-xs text-slate-400">{{ $lead->company->city ?? '' }}</p>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                {{ $lead->loanType->name ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800">
                            ₹{{ number_format($lead->loan_amount) }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                                $statusColor = match($lead->status->name ?? '') {
                                    'New' => 'blue',
                                    'In Progress' => 'yellow',
                                    'Converted' => 'green',
                                    'Lost' => 'red',
                                    'Rejected' => 'red',
                                    'Approved' => 'green',
                                    default => 'gray'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium
                                @if($statusColor == 'blue') bg-blue-100 text-blue-700
                                @elseif($statusColor == 'yellow') bg-yellow-100 text-yellow-700
                                @elseif($statusColor == 'green') bg-green-100 text-green-700
                                @elseif($statusColor == 'red') bg-red-100 text-red-700
                                @else bg-slate-100 text-slate-700 @endif">
                                <span class="w-1.5 h-1.5 rounded-full
                                    @if($statusColor == 'blue') bg-blue-500
                                    @elseif($statusColor == 'yellow') bg-yellow-500
                                    @elseif($statusColor == 'green') bg-green-500
                                    @elseif($statusColor == 'red') bg-red-500
                                    @else bg-slate-500 @endif">
                                </span>
                                {{ $lead->status->name ?? 'Unknown' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500 text-xs">
                            <div class="flex flex-col">
                                <span>{{ $lead->created_at->format('d M Y') }}</span>
                                <span class="text-slate-400">{{ $lead->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('leads.show', $lead->id) }}"
                                   class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-xs font-medium">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('leads.edit', $lead->id) }}"
                                   class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition text-xs font-medium">
                                    <i class="fas fa-edit mr-1"></i>
                                </a>
                                <button onclick="deleteLead({{ $lead->id }})"
                                        class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-xs font-medium">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-slate-400">
                            <i class="fas fa-inbox text-4xl block mb-3 opacity-20"></i>
                            <p class="text-lg font-medium text-slate-600">No leads found</p>
                            <p class="text-sm mt-1">Try adjusting your search or filters</p>
                            <a href="{{ route('leads.create') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i> Create your first lead
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Table Footer --}}
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="text-sm text-slate-500">
                Showing {{ $leads->firstItem() ?? 0 }} - {{ $leads->lastItem() ?? 0 }} of {{ $leads->total() }} entries
            </div>
            <div class="flex items-center gap-2">
                {{ $leads->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

    {{-- Export Modal --}}
    <div id="exportModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-xl">
            <h3 class="text-xl font-bold text-slate-800 mb-2">Export Leads</h3>
            <p class="text-sm text-slate-500 mb-4">Choose your export format</p>
            <div class="space-y-3">
                <button onclick="exportFormat('csv')" class="w-full px-4 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition text-left flex items-center justify-between">
                    <span><i class="fas fa-file-csv text-green-600 mr-3"></i> CSV</span>
                    <span class="text-xs text-slate-400">Download</span>
                </button>
                <button onclick="exportFormat('excel')" class="w-full px-4 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition text-left flex items-center justify-between">
                    <span><i class="fas fa-file-excel text-green-700 mr-3"></i> Excel</span>
                    <span class="text-xs text-slate-400">Download</span>
                </button>
                <button onclick="exportFormat('pdf')" class="w-full px-4 py-3 border border-slate-200 rounded-xl hover:bg-slate-50 transition text-left flex items-center justify-between">
                    <span><i class="fas fa-file-pdf text-red-600 mr-3"></i> PDF</span>
                    <span class="text-xs text-slate-400">Download</span>
                </button>
            </div>
            <button onclick="closeExportModal()" class="w-full mt-4 px-4 py-2 border border-slate-200 rounded-xl hover:bg-slate-50 transition text-sm">
                Cancel
            </button>
        </div>
    </div>

</div>

<script>
    // Delete lead with confirmation
    function deleteLead(id) {
        if (confirm('Are you sure you want to delete this lead?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/leads/${id}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Export functions
    function exportLeads() {
        document.getElementById('exportModal').classList.remove('hidden');
        document.getElementById('exportModal').classList.add('flex');
    }

    function closeExportModal() {
        document.getElementById('exportModal').classList.add('hidden');
        document.getElementById('exportModal').classList.remove('flex');
    }

    function exportFormat(format) {
        window.location.href = `{{ route('leads.index') }}?export=${format}&${new URLSearchParams(window.location.search).toString()}`;
        closeExportModal();
    }

    // Refresh table
    function refreshTable() {
        window.location.reload();
    }

    // Close modal on outside click
    document.getElementById('exportModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeExportModal();
        }
    });

    // Select all checkboxes
    document.querySelector('thead input[type="checkbox"]')?.addEventListener('change', function() {
        document.querySelectorAll('tbody input[type="checkbox"]').forEach(cb => {
            cb.checked = this.checked;
        });
    });
</script>

<style>
    /* Custom pagination styling */
    .pagination {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
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
    }
</style>

@endsection