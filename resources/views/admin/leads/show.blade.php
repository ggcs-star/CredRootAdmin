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

    {{-- Header Section --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-10 h-10 sm:w-14 sm:h-14 rounded-xl sm:rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white text-base sm:text-xl font-bold shadow-lg shadow-blue-500/20 flex-shrink-0">
                    {{ strtoupper(substr($lead->lead_number ?? 'L', -2)) }}
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg sm:text-2xl font-bold text-slate-800 flex flex-wrap items-center gap-1 sm:gap-2">
                        <span class="truncate">{{ $lead->lead_number }}</span>
                        <span class="text-xs sm:text-sm font-normal text-slate-400 flex-shrink-0">#{{ $lead->id }}</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 flex flex-wrap items-center gap-2 mt-1">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-calendar-alt text-blue-400 text-xs"></i>
                            {{ $lead->created_at->format('d M Y, h:i A') ?? 'N/A' }}
                        </span>
                        <span class="w-1 h-1 bg-slate-300 rounded-full hidden sm:inline"></span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-user-tie text-indigo-400 text-xs"></i>
                            Agent: <strong class="text-slate-700">{{ $lead->assignedAgent->name ?? 'Unassigned' }}</strong>
                        </span>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                {{-- Dynamic Status Badge from Database Color --}}
                @php
                    $hexColor = $lead->status->color ?? '#64748b';
                    $bgColor = $hexColor . '1A'; // 10% opacity
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold"
                      style="background-color: {{ $bgColor }}; color: {{ $hexColor }}; border: 1px solid {{ $hexColor }}40;">
                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $hexColor }};"></span>
                    {{ $lead->status->name ?? 'Unknown' }}
                </span>

                {{-- Action Buttons --}}
                <div class="flex gap-2 ml-auto lg:ml-0">
                    <button onclick="window.print()" class="px-3 py-1.5 bg-slate-50 text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-100 transition font-medium text-sm flex items-center gap-1.5">
                        <i class="fas fa-print"></i> <span class="hidden sm:inline">Print</span>
                    </button>
                    <a href="{{ route('leads.index') }}" class="px-3 py-1.5 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition font-medium text-sm flex items-center gap-1.5">
                        <i class="fas fa-arrow-left"></i> <span class="hidden sm:inline">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 border-l-4 border-l-blue-500">
            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Requested Amount</p>
            <p class="text-xl font-bold text-slate-800 mt-1">₹{{ number_format($lead->loan_amount ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 border-l-4 {{ ($lead->cibil_score ?? 0) >= 700 ? 'border-l-green-500' : 'border-l-amber-500' }}">
            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">CIBIL Score</p>
            <p class="text-xl font-bold {{ ($lead->cibil_score ?? 0) >= 700 ? 'text-green-600' : 'text-amber-600' }}">
                {{ $lead->cibil_score ?? 'N/A' }}
            </p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 border-l-4 border-l-purple-500">
            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Avg Bank Balance</p>
            <p class="text-xl font-bold text-slate-800 mt-1">₹{{ number_format($lead->average_bank_balance ?? 0, 0) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 border-l-4 border-l-emerald-500">
            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Pre-Qualification</p>
            <div class="mt-1">
                @if($lead->is_pre_qualified)
                    <span class="text-sm font-bold text-emerald-600 flex items-center gap-1"><i class="fas fa-check-circle"></i> Qualified</span>
                @else
                    <span class="text-sm font-bold text-slate-500 flex items-center gap-1"><i class="fas fa-clock"></i> Not Qualified</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- ======================================================== --}}
        {{-- LEFT SIDE: MAIN CONTENT (User, Company, Banks, Docs)     --}}
        {{-- ======================================================== --}}
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            {{-- 1. Loan Requirement --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar text-blue-500"></i>
                    <h3 class="font-semibold text-slate-800">Loan Requirement</h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Loan Type</p>
                        <p class="font-medium text-slate-800 bg-slate-100 px-3 py-1.5 rounded-lg inline-block text-sm border border-slate-200">
                            {{ $lead->loanType->name ?? 'Not Specified' }}
                        </p>
                    </div>
                    @if($lead->is_pre_qualified)
                    <div class="bg-green-50 p-3 rounded-xl border border-green-100">
                        <p class="text-xs text-green-600 uppercase tracking-wider mb-1 font-semibold flex items-center gap-1">
                            <i class="fas fa-certificate"></i> Pre-Approved Range
                        </p>
                        <p class="font-bold text-green-700 text-lg">
                            ₹{{ number_format($lead->pre_approved_min_amount ?? 0) }} - ₹{{ number_format($lead->pre_approved_max_amount ?? 0) }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- 2. Applicant Information --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2">
                    <i class="fas fa-user-circle text-emerald-500"></i>
                    <h3 class="font-semibold text-slate-800">Applicant Information</h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Full Name</p>
                        <p class="font-bold text-slate-800">{{ $lead->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Mobile Number</p>
                        <a href="tel:{{ $lead->user->mobile ?? '' }}" class="font-bold text-blue-600 hover:underline">
                            {{ $lead->user->mobile ?? 'N/A' }}
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Email Address</p>
                        <a href="mailto:{{ $lead->user->email ?? '' }}" class="font-bold text-blue-600 hover:underline truncate block">
                            {{ $lead->user->email ?? 'N/A' }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3. Business Details (Comprehensive) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-building text-indigo-500"></i> Business Details
                    </h3>
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-md font-bold border border-indigo-200">
                        {{ $lead->company->entity_type ?? 'N/A' }}
                    </span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                    <div class="col-span-1 sm:col-span-2 flex items-center gap-4 border-b border-slate-100 pb-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 text-xl font-bold border border-indigo-100">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-slate-800">{{ $lead->company->company_name ?? 'N/A' }}</p>
                            <p class="text-sm text-slate-500">{{ $lead->company->industry_type ?? 'General Business' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">PAN Number</p>
                        <p class="font-bold text-slate-800 uppercase">{{ $lead->company->pan_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">GST Number</p>
                        <p class="font-bold text-slate-800 uppercase">{{ $lead->company->gst_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Udyam Registration</p>
                        <p class="font-bold text-slate-800 uppercase">{{ $lead->company->udyam_registration_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Date of Incorporation</p>
                        <p class="font-bold text-slate-800">
                            {{ $lead->company->date_of_incorporation ? \Carbon\Carbon::parse($lead->company->date_of_incorporation)->format('d M Y') : 'N/A' }}
                        </p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Monthly Revenue</p>
                        <p class="font-bold text-slate-800 text-lg">₹{{ number_format($lead->company->monthly_revenue ?? 0, 2) }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Annual Turnover</p>
                        <p class="font-bold text-slate-800 text-lg">₹{{ number_format($lead->company->turnover ?? 0, 2) }}</p>
                    </div>
                    
                    @if($lead->company->address)
                    <div class="col-span-1 sm:col-span-2">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Registered Address</p>
                        <p class="font-medium text-slate-700">
                            {{ $lead->company->address }}, {{ $lead->company->city }}, {{ $lead->company->state }} - {{ $lead->company->pincode }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- 4. Applicant Bank Account (For Statement & Disbursal) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-piggy-bank text-teal-500"></i> Applicant's Bank Account
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Bank account provided by user for statements and loan disbursal.</p>
                </div>
                <div class="p-5">
                    @if($lead->bankAccount)
                        <div class="flex items-start gap-4 p-4 border border-teal-100 bg-teal-50/30 rounded-xl">
                            <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-xl">
                                <i class="fas fa-money-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-lg">{{ $lead->bankAccount->bank_name }}</h4>
                                <p class="text-sm font-medium text-slate-600 mt-1">A/c Holder: <span class="text-slate-800">{{ $lead->bankAccount->account_holder_name }}</span></p>
                                <div class="flex flex-wrap gap-x-6 gap-y-2 mt-2 text-sm">
                                    <p class="text-slate-500">A/c No: <strong class="text-slate-800 font-mono tracking-wider">{{ $lead->bankAccount->account_number }}</strong></p>
                                    <p class="text-slate-500">IFSC: <strong class="text-slate-800 font-mono uppercase">{{ $lead->bankAccount->ifsc_code }}</strong></p>
                                    <span class="px-2 py-0.5 bg-white border border-slate-200 rounded text-xs font-bold text-slate-600 shadow-sm">{{ $lead->bankAccount->account_type }} Account</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500 italic text-sm">No bank account attached by the applicant.</p>
                    @endif
                </div>
            </div>

            {{-- 5. Lender Banks (Where Loan is Applied) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-university text-purple-500"></i> Lender Banks (Applications)
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Banks where this lead has been forwarded.</p>
                    </div>
                    <span class="badge bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full text-xs font-bold border border-purple-200">
                        {{ $lead->loanApplications->count() ?? 0 }} Banks
                    </span>
                </div>
                <div class="p-5">
                    @forelse($lead->loanApplications as $application)
                        <div class="flex items-center justify-between p-4 border border-slate-200 rounded-xl mb-3 hover:bg-slate-50 transition last:mb-0">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center p-1 shadow-sm overflow-hidden">
                                    @if($application->bank->logo ?? false)
                                        <img src="{{ asset('storage/' . $application->bank->logo) }}" class="w-full h-full object-contain">
                                    @else
                                        <i class="fas fa-landmark text-slate-400"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $application->bank->name ?? 'Bank Name' }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5 font-mono">App No: {{ $application->application_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-lg text-xs font-bold border border-purple-100">
                                    {{ $application->status->name ?? 'Forwarded' }}
                                </span>
                                <p class="text-[10px] text-slate-400 mt-1">{{ $application->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400">
                            <i class="fas fa-file-signature text-3xl mb-2 opacity-30"></i>
                            <p class="text-sm font-medium">Application not forwarded to any bank yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 6. Uploaded Documents --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-folder-open text-amber-500"></i> Uploaded Documents
                    </h3>
                    <span class="badge bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-xs font-bold border border-amber-200">
                        {{ $lead->documents->count() ?? 0 }} Files
                    </span>
                </div>
                <div class="p-5">
                    @forelse($lead->documents as $document)
                        @php $master = $document->master; @endphp
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-slate-200 rounded-xl mb-3 hover:bg-slate-50 hover:border-slate-300 transition-all last:mb-0 gap-4">
                            <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0 mt-1 border border-amber-100">
                                    <i class="fas fa-file-pdf text-lg"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <p class="font-bold text-slate-800 text-sm sm:text-base truncate">
                                            {{ $master->name ?? ucfirst($document->document_side) . ' Document' }}
                                        </p>
                                        
                                        @if($master)
                                            <span class="text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-bold tracking-wide border border-slate-200">
                                                {{ strtoupper($master->document_level) }} LEVEL
                                            </span>
                                            @if($master->is_mandatory)
                                                <span class="text-[10px] px-2 py-0.5 rounded bg-red-50 text-red-600 font-bold tracking-wide border border-red-100">MANDATORY</span>
                                            @endif
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[10px] sm:text-xs text-slate-500 font-medium mt-2">
                                        <span class="flex items-center gap-1 bg-white px-2 py-1 rounded border border-slate-200 shadow-sm">
                                            Side: <strong class="text-slate-800 uppercase">{{ $document->document_side }}</strong>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-clock text-slate-400"></i> {{ $document->created_at->format('d M Y, h:i A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex-shrink-0 self-start sm:self-center ml-13 sm:ml-0 flex gap-2">
                                @if($document->verification_status == 'verified')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-green-100 text-green-700 border border-green-200"><i class="fas fa-check mr-1"></i> Verified</span>
                                @elseif($document->verification_status == 'rejected')
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200"><i class="fas fa-times mr-1"></i> Rejected</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200"><i class="fas fa-clock mr-1"></i> Pending</span>
                                @endif

                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-slate-700 bg-white hover:bg-slate-50 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors border border-slate-300 shadow-sm">
                                    <i class="fas fa-external-link-alt text-blue-500"></i> View
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100">
                                <i class="fas fa-file-excel text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-slate-600 font-bold">No Documents Found</p>
                            <p class="text-xs text-slate-400 mt-1">Files related to this lead will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ======================================================== --}}
        {{-- RIGHT SIDE: SIDEBAR ACTIONS & TIMELINE                   --}}
        {{-- ======================================================== --}}
        <div class="space-y-4 sm:space-y-6">

            {{-- Update Lead Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-sync-alt text-blue-500"></i> Update Lead Status
                    </h3>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('leads.update-status', $lead->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-2 block">Change Status</label>
                                <div class="relative">
                                    <select name="status_id" class="w-full border border-slate-300 rounded-xl pl-4 pr-10 py-3 text-sm font-medium text-slate-700 appearance-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all cursor-pointer bg-white">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $lead->status_id == $status->id ? 'selected' : '' }}>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 text-sm shadow-sm shadow-blue-500/30">
                                <i class="fas fa-save"></i> Save Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

         

            {{-- System Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-slate-500"></i> System Timeline
                    </h3>
                </div>
                <div class="p-5">
                    <div class="relative border-l-2 border-slate-100 ml-3 space-y-6">
                        
                        <div class="relative pl-6">
                            <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-blue-500 ring-4 ring-white shadow-sm"></span>
                            <p class="text-sm font-bold text-slate-800">Lead Created</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $lead->created_at->format('d M Y, h:i A') }}</p>
                            <p class="text-xs text-slate-400 mt-1 italic">Application initiated by {{ $lead->user->name ?? 'User' }}</p>
                        </div>

                        @if($lead->updated_at != $lead->created_at)
                        <div class="relative pl-6">
                            <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-slate-300 ring-4 ring-white shadow-sm"></span>
                            <p class="text-sm font-bold text-slate-800">Last Modified</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $lead->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                        @endif

                        @if($lead->documents->count() > 0)
                        <div class="relative pl-6">
                            <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-amber-500 ring-4 ring-white shadow-sm"></span>
                            <p class="text-sm font-bold text-slate-800">Documents Uploaded</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $lead->documents->last()->created_at->format('d M Y, h:i A') }}</p>
                            <p class="text-xs text-slate-400 mt-1 italic">{{ $lead->documents->count() }} files attached to this profile.</p>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    @media print {
        .no-print, nav, .bg-slate-800, form, button, .col-span-1 { display: none !important; }
        .lg\:col-span-2 { grid-column: span 3 / span 3 !important; }
        .bg-white { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        body { background: white !important; }
    }
</style>

@endsection