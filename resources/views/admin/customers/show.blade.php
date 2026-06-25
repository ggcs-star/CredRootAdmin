@extends('admin.layouts.app')

@section('title', 'Customer Profile - ' . $customer->name)
@section('page-title', 'Customer Profile')

@section('content')

<div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 pb-2 border-b border-slate-200">
        <a href="{{ route('admin.dashboard.index') }}" class="hover:text-blue-600 transition"><i class="fas fa-home"></i></a>
        <span>/</span>
        <a href="{{ route('admin.customers.index') }}" class="hover:text-blue-600 transition">Customers</a>
        <span>/</span>
        <span class="text-slate-800 font-medium">{{ $customer->name }}</span>
    </nav>

    {{-- Header Profile Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden relative">
        <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        <div class="px-6 pb-6 relative">
            <div class="flex flex-col sm:flex-row gap-4 sm:items-end -mt-10 sm:-mt-12 mb-4">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-white bg-slate-100 flex items-center justify-center text-2xl font-bold text-blue-600 shadow-md flex-shrink-0">
                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-slate-800">{{ $customer->name }}</h1>
                    <p class="text-slate-500 font-medium">Customer ID: #{{ $customer->id }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-sm font-bold border border-emerald-100">
                        Step {{ $customer->current_step }} / 6
                    </span>
                    @if($customer->status == 1)
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-sm font-bold border border-blue-100">Active</span>
                    @else
                        <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-sm font-bold border border-red-100">Inactive</span>
                    @endif
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100">
                <div class="flex items-center gap-3 text-slate-600">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400"><i class="fas fa-envelope"></i></div>
                    <a href="mailto:{{ $customer->email }}" class="hover:text-blue-600 font-medium">{{ $customer->email }}</a>
                </div>
                <div class="flex items-center gap-3 text-slate-600">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400"><i class="fas fa-phone"></i></div>
                    <a href="tel:{{ $customer->mobile }}" class="hover:text-blue-600 font-medium">{{ $customer->mobile ?? 'Not Provided' }}</a>
                </div>
                <div class="flex items-center gap-3 text-slate-600">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400"><i class="fas fa-calendar-alt"></i></div>
                    <span class="font-medium">Joined: {{ $customer->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
        
        {{-- ==================== LEFT COLUMN (Main Info) ==================== --}}
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">

            {{-- Company & Compliance Details (New Fields Included) --}}
            @if($company)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-building text-indigo-500"></i> Business Information
                    </h3>
                    <span class="text-xs font-bold px-2 py-1 bg-indigo-50 text-indigo-700 rounded-md border border-indigo-100">
                        {{ $company->entity_type }}
                    </span>
                </div>
                <div class="p-5">
                    <div class="mb-6">
                        <h4 class="text-xl font-bold text-slate-800">{{ $company->company_name }}</h4>
                        <p class="text-sm text-slate-500">{{ $company->industry_type ?? 'Industry Not Specified' }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-8">
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">PAN Number</p>
                            <p class="font-semibold text-slate-800">{{ $company->pan_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">GST Number</p>
                            <p class="font-semibold text-slate-800">{{ $company->gst_number ?? 'N/A' }}</p>
                        </div>
                        @if($company->cin_number)
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">CIN Number</p>
                            <p class="font-semibold text-slate-800">{{ $company->cin_number }}</p>
                        </div>
                        @endif
                        @if($company->udyam_registration_number)
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Udyam Reg. No</p>
                            <p class="font-semibold text-slate-800">{{ $company->udyam_registration_number }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Monthly Revenue</p>
                            <p class="font-bold text-emerald-600">₹{{ number_format($company->monthly_revenue ?? 0, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Annual Turnover</p>
                            <p class="font-bold text-emerald-600">₹{{ number_format($company->turnover ?? 0, 2) }}</p>
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Registered Address</p>
                            <p class="font-medium text-slate-700">
                                {{ $company->address ?? 'N/A' }}<br>
                                {{ $company->city }}, {{ $company->state }} - {{ $company->pincode }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Key Management Personnel (Members) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-users-cog text-teal-500"></i> Key Management / Directors
                    </h3>
                </div>
                <div class="p-0">
                    @forelse($company->members as $member)
                        <div class="p-5 border-b border-slate-100 last:border-0 hover:bg-slate-50 transition">
                            <div class="flex flex-wrap justify-between items-start gap-4 mb-4">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                        {{ $member->name }}
                                        @if($member->is_authorized_signatory)
                                            <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded border border-amber-200"><i class="fas fa-pen-nib"></i> Signatory</span>
                                        @endif
                                    </h4>
                                    <p class="text-sm font-bold text-teal-600">{{ $member->designation }} <span class="text-slate-400 font-normal">({{ $member->ownership_percentage ?? 0 }}% Ownership)</span></p>
                                </div>
                                @if($member->cibil_score)
                                <div class="text-right">
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">CIBIL Score</p>
                                    <p class="text-lg font-black {{ $member->cibil_score >= 700 ? 'text-green-500' : 'text-amber-500' }}">{{ $member->cibil_score }}</p>
                                </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase font-bold">PAN</span>
                                    <span class="font-semibold text-slate-700">{{ $member->pan_number ?? 'N/A' }}</span>
                                </div>
                                @if($member->din_number)
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase font-bold">DIN</span>
                                    <span class="font-semibold text-slate-700">{{ $member->din_number }}</span>
                                </div>
                                @endif
                                <div>
                                    <span class="block text-[10px] text-slate-400 uppercase font-bold">Contact</span>
                                    <span class="font-semibold text-slate-700">{{ $member->mobile }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400">
                            <p>No management details added yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @else
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center text-amber-600">
                <i class="fas fa-exclamation-triangle text-3xl mb-2 opacity-50"></i>
                <h3 class="font-bold text-lg mb-1">No Business Profile</h3>
                <p class="text-sm">This customer has not completed their business profile onboarding step yet.</p>
            </div>
            @endif

        </div>

        {{-- ==================== RIGHT COLUMN (Leads & Security) ==================== --}}
        <div class="space-y-4 sm:space-y-6">

            {{-- Active Loan Application (Lead) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-file-invoice-dollar text-purple-500"></i> Active Lead
                    </h3>
                </div>
                <div class="p-5">
                    @if($activeLead)
                        <div class="text-center mb-4">
                            <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Requested Amount</p>
                            <p class="text-3xl font-black text-slate-800">₹{{ number_format($activeLead->loan_amount) }}</p>
                        </div>
                        
                        <div class="space-y-3 mt-4 pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium">Lead No:</span>
                                <a href="{{ route('leads.show', $activeLead->id ?? '#') }}" class="font-bold text-blue-600 hover:underline">{{ $activeLead->lead_number }}</a>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 font-medium">Status:</span>
                                <span class="font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-xs">{{ $activeLead->status->name ?? 'Processing' }}</span>
                            </div>
                        </div>

                        <a href="{{ route('leads.show', $activeLead->id ?? '#') }}" class="mt-5 w-full block text-center bg-purple-50 hover:bg-purple-100 text-purple-700 py-2.5 rounded-xl text-sm font-bold transition">
                            View Full Lead Details
                        </a>
                    @else
                        <div class="text-center py-4 text-slate-400">
                            <p class="text-sm font-medium">No active loan applications.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Security & Devices (Integrating your Enterprise Auth Logic) --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-red-500"></i> Security & Devices
                    </h3>
                </div>
                <div class="p-0">
                    @forelse($customer->sessions ?? [] as $session)
                        @if($session->device)
                        <div class="p-4 border-b border-slate-100 last:border-0">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                    @if($session->device->device_type == 'DESKTOP') <i class="fas fa-desktop text-slate-400"></i>
                                    @else <i class="fas fa-mobile-alt text-slate-400"></i> @endif
                                    {{ $session->device->browser ?? 'Unknown' }} on {{ $session->device->platform ?? 'Unknown' }}
                                </p>
                                @if($session->device->trust_level == 'VERIFIED')
                                    <span class="w-2 h-2 rounded-full bg-green-500" title="Verified Device"></span>
                                @elseif($session->device->trust_level == 'BLOCKED')
                                    <span class="w-2 h-2 rounded-full bg-red-500" title="Blocked Device"></span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-amber-500" title="Suspicious"></span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-500 font-medium">IP: {{ $session->ip_address }} • {{ $session->device->last_city ?? 'Unknown Loc' }}</p>
                            <p class="text-[10px] text-slate-400 mt-1">Last Active: {{ $session->last_used_at->diffForHumans() }}</p>
                        </div>
                        @endif
                    @empty
                        <div class="p-5 text-center text-slate-400 text-sm">
                            <p>No active sessions found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

@endsection