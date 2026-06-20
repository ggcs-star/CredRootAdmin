@extends('admin.layouts.app')

@section('title', 'Lead Details - ' . ($lead->lead_number ?? 'N/A'))
@section('page-title', 'Lead Details')

@section('content')

<div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 px-3 sm:px-4 lg:px-0">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 overflow-x-auto whitespace-nowrap pb-1">
        <a href="{{ route('admin.dashboard.index') }}" class="hover:text-blue-600 transition flex-shrink-0">
            <i class="fas fa-home"></i>
        </a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <a href="{{ route('leads.index') }}" class="hover:text-blue-600 transition flex-shrink-0">Leads</a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <span class="text-slate-700 font-medium truncate">{{ $lead->lead_number ?? 'Lead Details' }}</span>
    </nav>

    {{-- Header --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 hover:shadow-lg transition-shadow">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-base sm:text-xl font-bold shadow-lg shadow-blue-500/20 flex-shrink-0">
                    {{ strtoupper(substr($lead->lead_number ?? 'L', -2)) }}
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg sm:text-2xl font-bold text-slate-800 flex flex-wrap items-center gap-1 sm:gap-2">
                        <span class="truncate">{{ $lead->lead_number }}</span>
                        <span class="text-xs sm:text-sm font-normal text-slate-400 flex-shrink-0">#{{ $lead->id }}</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 flex flex-wrap items-center gap-1 sm:gap-2 mt-0.5 sm:mt-0">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-calendar-alt text-blue-400 text-xs"></i>
                            <span class="hidden xs:inline">Created:</span>
                            {{ $lead->created_at->format('d M Y') ?? 'N/A' }}
                        </span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full hidden xs:inline"></span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-user text-blue-400 text-xs"></i>
                            <span class="truncate max-w-[100px] sm:max-w-[200px]">{{ $lead->user->name ?? 'Unknown User' }}</span>
                        </span>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                {{-- Status Badge --}}
                @php
                    $statusColor = match($lead->status->name ?? '') {
                        'New' => 'blue',
                        'In Progress' => 'yellow',
                        'Converted' => 'green',
                        'Lost' => 'red',
                        'Rejected' => 'red',
                        'Approved' => 'green',
                        'Pending' => 'amber',
                        default => 'gray'
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-4 py-1 sm:py-2 rounded-full text-xs sm:text-sm font-medium flex-shrink-0
                    @if($statusColor == 'blue') bg-blue-100 text-blue-700
                    @elseif($statusColor == 'yellow') bg-yellow-100 text-yellow-700
                    @elseif($statusColor == 'green') bg-green-100 text-green-700
                    @elseif($statusColor == 'red') bg-red-100 text-red-700
                    @elseif($statusColor == 'amber') bg-amber-100 text-amber-700
                    @else bg-slate-100 text-slate-700 @endif">
                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full flex-shrink-0
                        @if($statusColor == 'blue') bg-blue-500
                        @elseif($statusColor == 'yellow') bg-yellow-500
                        @elseif($statusColor == 'green') bg-green-500
                        @elseif($statusColor == 'red') bg-red-500
                        @elseif($statusColor == 'amber') bg-amber-500
                        @else bg-slate-500 @endif">
                    </span>
                    {{ $lead->status->name ?? 'Unknown' }}
                </span>

                {{-- Pre-qualified Badge --}}
                @if($lead->is_pre_qualified)
                    <span class="px-2.5 sm:px-4 py-1 sm:py-2 bg-emerald-100 text-emerald-700 rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 flex-shrink-0">
                        <i class="fas fa-star text-xs"></i>
                        <span class="hidden xs:inline">Pre-Qualified</span>
                        <span class="xs:hidden">Qualified</span>
                    </span>
                @else
                    <span class="px-2.5 sm:px-4 py-1 sm:py-2 bg-slate-100 text-slate-600 rounded-full text-xs sm:text-sm font-medium flex items-center gap-1 flex-shrink-0">
                        <i class="fas fa-clock text-xs"></i>
                        <span class="hidden xs:inline">Not Qualified</span>
                        <span class="xs:hidden">Pending</span>
                    </span>
                @endif

                {{-- Action Buttons --}}
                <div class="flex gap-1.5 sm:gap-2 ml-0 sm:ml-2">
                    <a href="{{ route('leads.edit', $lead->id) }}" 
                       class="px-2.5 sm:px-4 py-1.5 sm:py-2 bg-amber-50 text-amber-600 rounded-lg sm:rounded-xl hover:bg-amber-100 transition font-medium text-xs sm:text-sm flex items-center gap-1">
                        <i class="fas fa-edit text-xs"></i>
                        <span class="hidden sm:inline">Edit</span>
                    </a>
                    <button onclick="window.print()" 
                            class="px-2.5 sm:px-4 py-1.5 sm:py-2 bg-slate-50 text-slate-600 rounded-lg sm:rounded-xl hover:bg-slate-100 transition font-medium text-xs sm:text-sm flex items-center gap-1">
                        <i class="fas fa-print text-xs"></i>
                        <span class="hidden sm:inline">Print</span>
                    </button>
                    <a href="{{ route('leads.index') }}" 
                       class="px-2.5 sm:px-4 py-1.5 sm:py-2 border border-slate-200 text-slate-600 rounded-lg sm:rounded-xl hover:bg-slate-50 transition font-medium text-xs sm:text-sm flex items-center gap-1">
                        <i class="fas fa-arrow-left text-xs"></i>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    {{-- Quick Stats Row --}}
    <div class="grid grid-cols-2 xs:grid-cols-4 gap-2 sm:gap-4">
        <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm border border-slate-200">
            <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Loan Amount</p>
            <p class="text-sm sm:text-xl font-bold text-blue-600 truncate">₹{{ number_format($lead->loan_amount ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm border border-slate-200">
            <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">CIBIL</p>
            <p class="text-sm sm:text-xl font-bold {{ ($lead->cibil_score ?? 0) >= 700 ? 'text-green-600' : ($lead->cibil_score ? 'text-red-600' : 'text-slate-400') }}">
                {{ $lead->cibil_score ?? 'N/A' }}
            </p>
        </div>
        <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm border border-slate-200">
            <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Applications</p>
            <p class="text-sm sm:text-xl font-bold text-purple-600">{{ $lead->loanApplications->count() ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg sm:rounded-xl p-3 sm:p-4 shadow-sm border border-slate-200">
            <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Documents</p>
            <p class="text-sm sm:text-xl font-bold text-amber-600">{{ $lead->documents->count() ?? 0 }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Left Side --}}
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            {{-- Lead Info --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl sm:rounded-t-2xl">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-info-circle text-blue-500"></i>
                        Lead Information
                    </h3>
                </div>

                <div class="p-4 sm:p-6 grid grid-cols-1 xs:grid-cols-2 gap-4 sm:gap-6">

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Company</p>
                        <p class="font-semibold text-slate-800 mt-1 text-sm sm:text-base">
                            {{ $lead->company->company_name ?? '-' }}
                        </p>
                        @if($lead->company)
                            <p class="text-xs text-slate-400 mt-0.5">{{ $lead->company->city ?? '' }}{{ ($lead->company->city && $lead->company->state) ? ', ' : '' }}{{ $lead->company->state ?? '' }}</p>
                        @endif
                    </div>

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Loan Type</p>
                        <p class="font-semibold text-slate-800 mt-1">
                            <span class="px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-700 rounded-lg text-xs sm:text-sm inline-block">
                                {{ $lead->loanType->name ?? '-' }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Loan Amount</p>
                        <p class="font-bold text-lg sm:text-2xl text-green-600 mt-1">
                            ₹{{ number_format($lead->loan_amount ?? 0, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Avg Bank Balance</p>
                        <p class="font-semibold text-slate-800 mt-1 text-sm sm:text-base">
                            ₹{{ number_format($lead->average_bank_balance ?? 0, 2) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">CIBIL Score</p>
                        <p class="font-semibold text-slate-800 mt-1 flex flex-wrap items-center gap-1 sm:gap-2 text-sm sm:text-base">
                            {{ $lead->cibil_score ?? '-' }}
                            @if(($lead->cibil_score ?? 0) >= 700)
                                <span class="text-[10px] sm:text-xs bg-green-100 text-green-700 px-1.5 sm:px-2 py-0.5 rounded">Good</span>
                            @elseif(($lead->cibil_score ?? 0) >= 600)
                                <span class="text-[10px] sm:text-xs bg-yellow-100 text-yellow-700 px-1.5 sm:px-2 py-0.5 rounded">Average</span>
                            @elseif(($lead->cibil_score ?? 0) > 0)
                                <span class="text-[10px] sm:text-xs bg-red-100 text-red-700 px-1.5 sm:px-2 py-0.5 rounded">Low</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Pre Qualified</p>
                        <div class="mt-1">
                            @if($lead->is_pre_qualified)
                                <span class="px-2.5 sm:px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs sm:text-sm flex items-center gap-1 w-fit">
                                    <i class="fas fa-check-circle text-xs"></i> Yes
                                </span>
                            @else
                                <span class="px-2.5 sm:px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs sm:text-sm flex items-center gap-1 w-fit">
                                    <i class="fas fa-times-circle text-xs"></i> No
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Additional Fields --}}
                    @if($lead->message)
                    <div class="col-span-1 xs:col-span-2">
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Message / Notes</p>
                        <p class="text-slate-700 mt-1 bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-200 text-sm">
                            {{ $lead->message }}
                        </p>
                    </div>
                    @endif

                </div>

            </div>

            {{-- Customer Information --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-xl sm:rounded-t-2xl">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-user-circle text-green-500"></i>
                        Customer Information
                    </h3>
                </div>

                <div class="p-4 sm:p-6 grid grid-cols-1 xs:grid-cols-2 gap-4 sm:gap-6">

                    <div class="flex items-center gap-3 col-span-1 xs:col-span-2">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-gradient-to-r from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm sm:text-lg flex-shrink-0">
                            {{ strtoupper(substr($lead->user->name ?? 'U', 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm sm:text-base">{{ $lead->user->name ?? '-' }}</p>
                            <p class="text-[10px] sm:text-xs text-slate-400">Customer</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Email</p>
                        <p class="font-semibold text-slate-800 mt-1 text-sm sm:text-base truncate">
                            <a href="mailto:{{ $lead->user->email ?? '' }}" class="text-blue-600 hover:underline">
                                {{ $lead->user->email ?? '-' }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Mobile</p>
                        <p class="font-semibold text-slate-800 mt-1 text-sm sm:text-base">
                            <a href="tel:{{ $lead->user->mobile ?? '' }}" class="text-blue-600 hover:underline">
                                {{ $lead->user->mobile ?? '-' }}
                            </a>
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] sm:text-xs text-slate-400 uppercase tracking-wider">Joined</p>
                        <p class="font-semibold text-slate-800 mt-1 text-sm sm:text-base">
                            {{ $lead->user->created_at->format('d M Y') ?? '-' }}
                        </p>
                    </div>

                </div>

            </div>

            {{-- Applied Banks --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200 bg-gradient-to-r from-purple-50 to-pink-50 rounded-t-xl sm:rounded-t-2xl flex flex-wrap justify-between items-center gap-2">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-university text-purple-500"></i>
                        Applied Banks
                    </h3>
                    <span class="text-[10px] sm:text-xs bg-purple-100 text-purple-700 px-2 py-0.5 sm:py-1 rounded-full">
                        {{ $lead->loanApplications->count() ?? 0 }} applications
                    </span>
                </div>

                <div class="p-4 sm:p-6">

                    @forelse($lead->loanApplications as $application)
                        <div class="border border-slate-200 rounded-lg sm:rounded-xl p-3 sm:p-4 mb-2 sm:mb-3 hover:border-blue-300 transition last:mb-0">
                            <div class="flex flex-col xs:flex-row xs:justify-between xs:items-start gap-2">
                                <div class="min-w-0">
                                    <h4 class="font-semibold text-slate-800 flex items-center gap-2 text-sm sm:text-base">
                                        <i class="fas fa-building text-blue-500 text-xs sm:text-sm"></i>
                                        <span class="truncate">{{ $application->bank->name ?? 'N/A' }}</span>
                                    </h4>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5 sm:mt-1">
                                        <i class="fas fa-calendar-alt text-[10px]"></i>
                                        Applied: {{ $application->created_at->format('d M Y') ?? 'N/A' }}
                                    </p>
                                    @if($application->remarks)
                                        <p class="text-xs sm:text-sm text-slate-600 mt-1.5 sm:mt-2 bg-slate-50 p-1.5 sm:p-2 rounded-lg border border-slate-100">
                                            <i class="fas fa-comment text-slate-400 text-xs"></i>
                                            {{ $application->remarks }}
                                        </p>
                                    @endif
                                </div>
                                <span class="px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-700 rounded-lg text-[10px] sm:text-xs font-medium whitespace-nowrap flex-shrink-0">
                                    Applied
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 sm:py-8 text-slate-400">
                            <i class="fas fa-building text-3xl sm:text-4xl block mb-2 sm:mb-3 opacity-20"></i>
                            <p class="text-sm sm:text-base">No Bank Applications Found.</p>
                        </div>
                    @endforelse

                </div>

            </div>

            {{-- Documents --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200 bg-gradient-to-r from-amber-50 to-orange-50 rounded-t-xl sm:rounded-t-2xl flex flex-wrap justify-between items-center gap-2">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-file-alt text-amber-500"></i>
                        Uploaded Documents
                    </h3>
                    <span class="text-[10px] sm:text-xs bg-amber-100 text-amber-700 px-2 py-0.5 sm:py-1 rounded-full">
                        {{ $lead->documents->count() ?? 0 }} documents
                    </span>
                </div>

                <div class="p-4 sm:p-6">

                    @forelse($lead->documents as $document)
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-slate-100 py-2.5 sm:py-3 last:border-0 hover:bg-slate-50 -mx-2 px-2 rounded-lg transition gap-2 sm:gap-0">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 flex-shrink-0">
                                    <i class="fas fa-file-pdf text-base sm:text-xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-800 text-sm sm:text-base truncate">{{ $document->document_name }}</p>
                                    <p class="text-[10px] sm:text-xs text-slate-400">
                                        <i class="fas fa-clock"></i>
                                        {{ $document->created_at->diffForHumans() ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ asset($document->file_path) }}"
                               target="_blank"
                               class="text-blue-600 hover:text-blue-800 font-medium text-xs sm:text-sm flex items-center gap-1 flex-shrink-0 ml-11 sm:ml-0">
                                <i class="fas fa-eye"></i> <span class="hidden xs:inline">View</span>
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-6 sm:py-8 text-slate-400">
                            <i class="fas fa-file text-3xl sm:text-4xl block mb-2 sm:mb-3 opacity-20"></i>
                            <p class="text-sm sm:text-base">No Documents Uploaded.</p>
                        </div>
                    @endforelse

                </div>

            </div>

        </div>

        {{-- Right Side - Actions --}}
        <div class="space-y-4 sm:space-y-6">

            {{-- Update Status --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200">

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200 bg-gradient-to-r from-green-50 to-emerald-50 rounded-t-xl sm:rounded-t-2xl">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-exchange-alt text-green-500"></i>
                        Update Status
                    </h3>
                </div>

                <div class="p-4 sm:p-6">

                    <form method="POST"
                          action="{{ route('leads.update-status', $lead->id) }}">

                        @csrf
                        @method('PUT')

                        <div class="space-y-3">
                            <label class="text-xs sm:text-sm text-slate-600 font-medium">Current Status</label>
                            <select name="status_id"
                                    class="w-full border border-slate-300 rounded-lg sm:rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition">

                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ $lead->status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach

                            </select>

                            <button type="submit"
                                    class="w-full mt-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-2.5 sm:py-3 rounded-lg sm:rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-green-500/20 active:scale-95 flex items-center justify-center gap-2 text-sm sm:text-base">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </div>

                    </form>

                </div>

            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200">

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl sm:rounded-t-2xl">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-bolt text-blue-500"></i>
                        Quick Actions
                    </h3>
                </div>

                <div class="p-3 sm:p-4 space-y-1.5 sm:space-y-2">
                    <a href="{{ route('leads.edit', $lead->id) }}" 
                       class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-amber-50 text-amber-700 rounded-lg sm:rounded-xl hover:bg-amber-100 transition font-medium text-xs sm:text-sm">
                        <i class="fas fa-edit text-xs sm:text-sm"></i>
                        <span class="truncate">Edit Lead Details</span>
                    </a>
                    <a href="#" 
                       class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-blue-50 text-blue-700 rounded-lg sm:rounded-xl hover:bg-blue-100 transition font-medium text-xs sm:text-sm">
                        <i class="fas fa-file-invoice text-xs sm:text-sm"></i>
                        <span class="truncate">Create Application</span>
                    </a>
                    <a href="#" 
                       class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-purple-50 text-purple-700 rounded-lg sm:rounded-xl hover:bg-purple-100 transition font-medium text-xs sm:text-sm">
                        <i class="fas fa-upload text-xs sm:text-sm"></i>
                        <span class="truncate">Upload Documents</span>
                    </a>
                    <button onclick="if(confirm('Are you sure you want to delete this lead?')) { document.getElementById('delete-form').submit(); }"
                            class="w-full flex items-center gap-2 sm:gap-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-red-50 text-red-700 rounded-lg sm:rounded-xl hover:bg-red-100 transition font-medium text-xs sm:text-sm">
                        <i class="fas fa-trash text-xs sm:text-sm"></i>
                        <span class="truncate">Delete Lead</span>
                    </button>
                    <form id="delete-form" action="{{ route('leads.destroy', $lead->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

            </div>

            {{-- Lead Timeline --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200">

                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 rounded-t-xl sm:rounded-t-2xl">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2 text-sm sm:text-base">
                        <i class="fas fa-history text-slate-500"></i>
                        Activity Timeline
                    </h3>
                </div>

                <div class="p-3 sm:p-4 max-h-48 sm:max-h-60 overflow-y-auto">
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 mt-1.5 sm:mt-2 bg-blue-500 rounded-full flex-shrink-0"></div>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm text-slate-700">Lead created</p>
                                <p class="text-[10px] sm:text-xs text-slate-400">{{ $lead->created_at->diffForHumans() ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($lead->updated_at != $lead->created_at)
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 mt-1.5 sm:mt-2 bg-green-500 rounded-full flex-shrink-0"></div>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm text-slate-700">Last updated</p>
                                <p class="text-[10px] sm:text-xs text-slate-400">{{ $lead->updated_at->diffForHumans() ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endif
                        @if($lead->status)
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 mt-1.5 sm:mt-2 bg-amber-500 rounded-full flex-shrink-0"></div>
                            <div class="min-w-0">
                                <p class="text-xs sm:text-sm text-slate-700">Status: {{ $lead->status->name ?? 'Unknown' }}</p>
                                <p class="text-[10px] sm:text-xs text-slate-400">Current status</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    // Print functionality
    function printLead() {
        window.print();
    }

    // Auto-refresh status on change (optional)
    document.querySelector('select[name="status_id"]')?.addEventListener('change', function() {
        this.closest('form').querySelector('button[type="submit"]').style.opacity = '0.7';
    });

    // Touch-friendly improvements
    document.addEventListener('DOMContentLoaded', function() {
        // Add active state for touch devices
        document.querySelectorAll('.hover\\:bg-\\[color\\]').forEach(el => {
            el.addEventListener('touchstart', function() {
                this.classList.add('active');
            }, { passive: true });
        });
    });
</script>

<style>
    @media print {
        .no-print { display: none !important; }
        .bg-white { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        .sticky { position: static !important; }
    }

    /* Custom scrollbar */
    .max-h-48::-webkit-scrollbar,
    .max-h-60::-webkit-scrollbar {
        width: 3px;
    }
    .max-h-48::-webkit-scrollbar-track,
    .max-h-60::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .max-h-48::-webkit-scrollbar-thumb,
    .max-h-60::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .max-h-48::-webkit-scrollbar-thumb:hover,
    .max-h-60::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Extra small screens (xs) */
    @media (max-width: 480px) {
        .xs\:inline { display: inline !important; }
        .xs\:hidden { display: none !important; }
    }

    /* Touch device optimizations */
    @media (hover: none) {
        .hover\:shadow-lg:hover { box-shadow: none !important; }
        .hover\:scale-105:hover { transform: none !important; }
    }

    /* Safe area for notch phones */
    @supports (padding: max(0px)) {
        .px-3 { padding-left: max(0.75rem, env(safe-area-inset-left)); padding-right: max(0.75rem, env(safe-area-inset-right)); }
    }
</style>

@endsection