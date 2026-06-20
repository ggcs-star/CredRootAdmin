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
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 hover:shadow-lg transition-shadow">
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
                {{-- Status Badge --}}
                @php
                    $statusColor = match($lead->status->name ?? '') {
                        'New', 'NEW' => 'blue',
                        'In Progress', 'DRAFT' => 'yellow',
                        'Converted', 'APPROVED' => 'green',
                        'Lost', 'REJECTED' => 'red',
                        'Pending', 'PENDING_DOCS' => 'amber',
                        default => 'gray'
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium
                    @if($statusColor == 'blue') bg-blue-100 text-blue-700
                    @elseif($statusColor == 'yellow') bg-yellow-100 text-yellow-700
                    @elseif($statusColor == 'green') bg-green-100 text-green-700
                    @elseif($statusColor == 'red') bg-red-100 text-red-700
                    @elseif($statusColor == 'amber') bg-amber-100 text-amber-700
                    @else bg-slate-100 text-slate-700 @endif">
                    <span class="w-2 h-2 rounded-full 
                        @if($statusColor == 'blue') bg-blue-500
                        @elseif($statusColor == 'yellow') bg-yellow-500
                        @elseif($statusColor == 'green') bg-green-500
                        @elseif($statusColor == 'red') bg-red-500
                        @elseif($statusColor == 'amber') bg-amber-500
                        @else bg-slate-500 @endif">
                    </span>
                    {{ $lead->status->name ?? 'Unknown' }}
                </span>

                {{-- Action Buttons --}}
                <div class="flex gap-2">
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
            <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Status</p>
            <div class="mt-1">
                @if($lead->is_pre_qualified)
                    <span class="text-sm font-bold text-emerald-600 flex items-center gap-1"><i class="fas fa-check-circle"></i> Pre-Qualified</span>
                @else
                    <span class="text-sm font-bold text-slate-500 flex items-center gap-1"><i class="fas fa-clock"></i> Not Qualified</span>
                @endif
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Left Side (Main Content) --}}
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            {{-- Loan Application Details --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar text-blue-500"></i> Loan Requirement
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Loan Type</p>
                        <p class="font-medium text-slate-800 bg-slate-100 px-3 py-1.5 rounded-lg inline-block text-sm">
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

            {{-- Business / Company Details (Crucial for MSME) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-building text-indigo-500"></i> Business Details
                    </h3>
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-md font-medium">
                        {{ $lead->company->entity_type ?? 'Business' }}
                    </span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                    <div class="col-span-1 sm:col-span-2 flex items-center gap-4 border-b border-slate-100 pb-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 text-xl font-bold">
                            <i class="fas fa-store"></i>
                        </div>
                        <div>
                            <p class="text-lg font-bold text-slate-800">{{ $lead->company->company_name ?? 'N/A' }}</p>
                            <p class="text-sm text-slate-500">{{ $lead->company->city ?? '' }}, {{ $lead->company->state ?? '' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">PAN Number</p>
                        <p class="font-medium text-slate-800">{{ $lead->company->pan_number ?? 'Not Provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">GST Number</p>
                        <p class="font-medium text-slate-800">{{ $lead->company->gst_number ?? 'Not Provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Monthly Revenue</p>
                        <p class="font-medium text-slate-800">₹{{ number_format($lead->company->monthly_revenue ?? 0, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Annual Turnover</p>
                        <p class="font-medium text-slate-800">₹{{ number_format($lead->company->turnover ?? 0, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Applicant Information --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-user-circle text-emerald-500"></i> Applicant Information
                    </h3>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Full Name</p>
                        <p class="font-medium text-slate-800">{{ $lead->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Mobile Number</p>
                        <a href="tel:{{ $lead->user->mobile ?? '' }}" class="font-medium text-blue-600 hover:underline">
                            {{ $lead->user->mobile ?? 'N/A' }}
                        </a>
                    </div>
                    <div class="col-span-1 sm:col-span-2">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Email Address</p>
                        <a href="mailto:{{ $lead->user->email ?? '' }}" class="font-medium text-blue-600 hover:underline">
                            {{ $lead->user->email ?? 'N/A' }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Applied Banks --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-university text-purple-500"></i> Applied Banks
                    </h3>
                    <span class="badge bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                        {{ $lead->loanApplications->count() ?? 0 }} Banks
                    </span>
                </div>
                <div class="p-5">
                    @forelse($lead->loanApplications as $application)
                        <div class="flex items-center justify-between p-4 border border-slate-100 rounded-xl mb-3 hover:bg-slate-50 transition last:mb-0">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fas fa-landmark"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $application->bank->name ?? 'Bank Name' }}</p>
                                    <p class="text-xs text-slate-500">Applied on: {{ $application->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium border border-blue-100">
                                Sent
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">
                            <i class="fas fa-university text-4xl mb-3 opacity-20"></i>
                            <p>No Bank Applications Found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Uploaded Documents --}}
           {{-- Uploaded Documents --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-file-pdf text-amber-500"></i> Uploaded Documents
                    </h3>
                    <span class="badge bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                        {{ $lead->documents->count() ?? 0 }} Files
                    </span>
                </div>
                <div class="p-5">
                    @forelse($lead->documents as $document)
                        @php
                            $master = $document->master;
                        @endphp
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-slate-100 rounded-xl mb-3 hover:bg-slate-50 hover:border-slate-200 transition-all last:mb-0 gap-4">
                            
                            <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                                {{-- Document Icon --}}
                                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 flex-shrink-0 mt-1 border border-amber-100">
                                    <i class="fas fa-file-invoice text-lg"></i>
                                </div>
                                
                                {{-- Document Details --}}
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        {{-- Document Name --}}
                                        <p class="font-bold text-slate-800 text-sm sm:text-base truncate">
                                            {{ $master->name ?? ucfirst($document->document_side) . ' Document' }}
                                        </p>
                                        
                                        {{-- Document Code Badge --}}
                                        @if($master)
                                            <span class="text-[10px] px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-semibold tracking-wide border border-slate-200">
                                                {{ $master->document_code }}
                                            </span>
                                            
                                            {{-- Mandatory / Optional Tag --}}
                                            @if($master->is_mandatory)
                                                <span class="text-[10px] px-2 py-0.5 rounded bg-red-50 text-red-600 font-bold tracking-wide border border-red-100">
                                                    MANDATORY
                                                </span>
                                            @else
                                                <span class="text-[10px] px-2 py-0.5 rounded bg-slate-50 text-slate-500 font-bold tracking-wide border border-slate-200">
                                                    OPTIONAL
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                    
                                    {{-- Document Description --}}
                                    @if($master && $master->description)
                                        <p class="text-xs text-slate-500 mb-2 truncate" title="{{ $master->description }}">
                                            {{ $master->description }}
                                        </p>
                                    @endif

                                    {{-- Metadata (Side, Date, Requirements) --}}
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[10px] sm:text-xs text-slate-500 font-medium">
                                        <span class="flex items-center gap-1 bg-white px-2 py-1 rounded border border-slate-100 shadow-sm">
                                            <i class="fas fa-layer-group text-slate-400"></i> Side: <strong class="text-slate-700">{{ ucfirst($document->document_side) }}</strong>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-clock text-slate-400"></i> {{ $document->created_at->format('d M Y, h:i A') }}
                                        </span>
                                        @if($master && $master->sides_required !== null)
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span class="text-slate-400">
                                                Required: {{ $master->sides_required == 2 ? 'Front & Back' : ($master->sides_required == 1 ? 'Front Only' : 'Single File') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Action Button --}}
                            <div class="flex-shrink-0 self-start sm:self-center ml-13 sm:ml-0">
                                <a href="{{ asset('storage/' . $document->file_path) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center gap-1.5 text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-lg text-xs font-bold transition-colors border border-blue-100 hover:border-blue-600 shadow-sm">
                                    <i class="fas fa-external-link-alt"></i> View File
                                </a>
                            </div>
                            
                        </div>
                    @empty
                        <div class="text-center py-10 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm border border-slate-100">
                                <i class="fas fa-folder-open text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-slate-500 font-medium">No Documents Uploaded Yet.</p>
                            <p class="text-xs text-slate-400 mt-1">Files uploaded by the user will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Right Side (Sidebar / Actions) --}}
        <div class="space-y-4 sm:space-y-6">

            {{-- Update Lead Status --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-sync-alt text-blue-500"></i> Manage Status
                    </h3>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('leads.update-status', $lead->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-3">
                            <label class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Current Status</label>
                            <select name="status_id" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ $lead->status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-2.5 rounded-xl font-medium transition flex items-center justify-center gap-2 text-sm">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

      

            {{-- Lead Timeline --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-slate-500"></i> Activity Timeline
                    </h3>
                </div>
                <div class="p-5">
                    <div class="relative border-l border-slate-200 ml-3 space-y-6">
                        
                        <div class="relative pl-6">
                            <span class="absolute -left-1.5 top-1 w-3 h-3 rounded-full bg-blue-500 ring-4 ring-white"></span>
                            <p class="text-sm font-medium text-slate-800">Lead Created</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $lead->created_at->format('d M Y, h:i A') }}</p>
                        </div>

                        @if($lead->updated_at != $lead->created_at)
                        <div class="relative pl-6">
                            <span class="absolute -left-1.5 top-1 w-3 h-3 rounded-full bg-green-500 ring-4 ring-white"></span>
                            <p class="text-sm font-medium text-slate-800">Profile Updated</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $lead->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                        @endif

                        @if($lead->documents->count() > 0)
                        <div class="relative pl-6">
                            <span class="absolute -left-1.5 top-1 w-3 h-3 rounded-full bg-amber-500 ring-4 ring-white"></span>
                            <p class="text-sm font-medium text-slate-800">Documents Uploaded</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $lead->documents->last()->created_at->format('d M Y, h:i A') }}</p>
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
        .no-print, nav, .bg-slate-800 { display: none !important; }
        .bg-white { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        body { background: white !important; }
    }
</style>

@endsection