@extends('admin.layouts.app')

@section('title', 'Loan Management Dashboard')
@section('page-title', 'Loan Management Overview')

@section('content')
<div class="space-y-6">

    {{-- Welcome & Quick Stats Banner --}}
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/20">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h2 class="text-2xl font-semibold flex items-center gap-2">
                    Welcome back, {{ Auth::user()->name ?? 'Admin' }} 👋
                    <span class="text-xs bg-white/20 px-3 py-1 rounded-full">Admin</span>
                </h2>
                <p class="text-blue-100 mt-1">Here's your loan platform performance overview for today.</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-wrap gap-3">
                <a href="#" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all backdrop-blur-sm hover:scale-105 active:scale-95">
                    <i class="fas fa-plus mr-2"></i> New Loan
                </a>
                <a href="#" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all backdrop-blur-sm hover:scale-105 active:scale-95">
                    <i class="fas fa-user-plus mr-2"></i> Add Lender
                </a>
            </div>
        </div>
    </div>

    {{-- Main Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Total Loan Applications --}}
        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-blue-500 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-slate-500 text-sm font-medium uppercase tracking-wider">
                        <i class="fas fa-file-alt text-blue-500 mr-1"></i> Total Applications
                    </h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">
                        {{ number_format($totalApplications ?? 1250) }}
                    </p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full group-hover:scale-110 transition-transform shadow-lg shadow-blue-500/20">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2 flex-wrap">
                <span class="text-sm text-green-600 bg-green-50 px-3 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i> {{ $applicationGrowth ?? 12 }}% from last month
                </span>
                <span class="text-xs text-slate-400">{{ $newApplicationsToday ?? 8 }} new today</span>
            </div>
        </div>

        {{-- Active Loans (Approved & Disbursed) --}}
        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-green-500 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-slate-500 text-sm font-medium uppercase tracking-wider">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i> Active Loans
                    </h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">
                        {{ number_format($activeLoans ?? 950) }}
                    </p>
                </div>
                <div class="bg-green-100 p-3 rounded-full group-hover:scale-110 transition-transform shadow-lg shadow-green-500/20">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2 flex-wrap">
                <span class="text-sm text-green-600 bg-green-50 px-3 py-1 rounded-full">
                    <i class="fas fa-arrow-up mr-1"></i> {{ $activeLoanGrowth ?? 8 }}% from last month
                </span>
                <span class="text-xs text-slate-400">₹{{ number_format($totalDisbursed ?? 45000000) }} disbursed</span>
            </div>
        </div>

        {{-- Pending Review Loans --}}
        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-yellow-500 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-slate-500 text-sm font-medium uppercase tracking-wider">
                        <i class="fas fa-clock text-yellow-500 mr-1"></i> Pending Review
                    </h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">
                        {{ number_format($pendingLoans ?? 180) }}
                    </p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full group-hover:scale-110 transition-transform shadow-lg shadow-yellow-500/20">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2 flex-wrap">
                <span class="text-sm text-red-600 bg-red-50 px-3 py-1 rounded-full">
                    <i class="fas fa-exclamation-triangle mr-1"></i> {{ $overduePending ?? 5 }} overdue
                </span>
                <span class="text-xs text-slate-400">{{ $pendingToday ?? 12 }} waiting today</span>
            </div>
        </div>

        {{-- Total Lenders --}}
        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300 border-l-4 border-purple-500 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-slate-500 text-sm font-medium uppercase tracking-wider">
                        <i class="fas fa-university text-purple-500 mr-1"></i> Registered Lenders
                    </h3>
                    <p class="text-3xl font-extrabold text-slate-800 mt-2">
                        {{ number_format($totalLenders ?? 25) }}
                    </p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full group-hover:scale-110 transition-transform shadow-lg shadow-purple-500/20">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2 flex-wrap">
                <span class="text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded-full">
                    <i class="fas fa-plus mr-1"></i> +{{ $newLendersThisMonth ?? 3 }} this month
                </span>
                <span class="text-xs text-slate-400">{{ $activeLenders ?? 22 }} active</span>
            </div>
        </div>
    </div>

    {{-- Lender & Loan Detailed Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

        {{-- Top Lenders Chart --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="text-lg font-semibold text-slate-700">
                        <i class="fas fa-trophy text-yellow-500 mr-2"></i> Top Performing Lenders
                    </h4>
                    <p class="text-slate-400 text-sm mt-1">Based on loan disbursement & approval rate</p>
                </div>
                <a href="#" class="text-blue-600 text-sm hover:underline flex items-center gap-1">
                    View All <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <div class="space-y-4">
                @forelse($topLenders ?? [] as $lender)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-blue-50 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md shadow-blue-500/20">
                            {{ substr($lender->name ?? 'L', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-slate-800 group-hover:text-blue-700 transition">{{ $lender->name ?? 'Lender Name' }}</p>
                            <p class="text-xs text-slate-500 flex items-center gap-1">
                                <i class="fas fa-envelope text-[10px]"></i> {{ $lender->email ?? 'lender@example.com' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-slate-800">₹{{ number_format($lender->total_disbursed ?? 0) }}</p>
                        <p class="text-xs text-green-600">
                            <i class="fas fa-handshake mr-1"></i> {{ $lender->loan_count ?? 0 }} loans • {{ $lender->approval_rate ?? 95 }}% rate
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400">
                    <i class="fas fa-building text-4xl mb-2 block opacity-20"></i>
                    <p>No lenders data available</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions & Recent Activity --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow">
                <h4 class="text-lg font-semibold text-slate-700 mb-4">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i> Quick Actions
                </h4>
                <div class="grid grid-cols-2 gap-3">
                    <a href="#" class="bg-blue-50 hover:bg-blue-100 text-blue-700 p-3 rounded-xl text-center transition text-sm font-medium hover:scale-105 active:scale-95">
                        <i class="fas fa-file-invoice mr-1"></i> New Loan
                    </a>
                    <a href="#" class="bg-purple-50 hover:bg-purple-100 text-purple-700 p-3 rounded-xl text-center transition text-sm font-medium hover:scale-105 active:scale-95">
                        <i class="fas fa-user-plus mr-1"></i> Add Lender
                    </a>
                    <a href="#" class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 p-3 rounded-xl text-center transition text-sm font-medium hover:scale-105 active:scale-95">
                        <i class="fas fa-clock mr-1"></i> Review Loans
                    </a>
                    <a href="#" class="bg-green-50 hover:bg-green-100 text-green-700 p-3 rounded-xl text-center transition text-sm font-medium hover:scale-105 active:scale-95">
                        <i class="fas fa-chart-bar mr-1"></i> Reports
                    </a>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow">
                <h4 class="text-lg font-semibold text-slate-700 mb-4">
                    <i class="fas fa-history text-blue-500 mr-2"></i> Recent Activity
                </h4>
                <ul class="space-y-3 text-sm max-h-64 overflow-y-auto">
                    @forelse($recentActivities ?? [] as $activity)
                    <li class="flex items-start gap-3 pb-3 border-b border-slate-100 last:border-0 hover:bg-slate-50 -mx-2 px-2 py-1 rounded-lg transition">
                        <span class="w-2 h-2 mt-1.5 rounded-full 
                            @if($activity->type == 'approved') bg-green-500 
                            @elseif($activity->type == 'pending') bg-yellow-500 
                            @elseif($activity->type == 'rejected') bg-red-500
                            @else bg-blue-500 @endif">
                        </span>
                        <div class="flex-1">
                            <p class="text-slate-700">{{ $activity->description ?? 'Activity log' }}</p>
                            <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() ?? 'Just now' }}</p>
                        </div>
                        @if($activity->type == 'approved')
                        <span class="text-xs text-green-600"><i class="fas fa-check-circle"></i></span>
                        @elseif($activity->type == 'pending')
                        <span class="text-xs text-yellow-600"><i class="fas fa-clock"></i></span>
                        @endif
                    </li>
                    @empty
                    <li class="text-center text-slate-400 py-4">
                        <i class="fas fa-inbox text-2xl block mb-2 opacity-20"></i>
                        No recent activity
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Loan Status Distribution & Lender Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

        {{-- Loan Status Distribution --}}
        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow">
            <h4 class="text-lg font-semibold text-slate-700 mb-4">
                <i class="fas fa-chart-pie text-blue-500 mr-2"></i> Loan Status Distribution
            </h4>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-slate-600"><i class="fas fa-circle text-green-500 mr-1"></i> Approved</span>
                        <span class="font-medium">{{ $approvedPercentage ?? 60 }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-400 to-green-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $approvedPercentage ?? 60 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-slate-600"><i class="fas fa-circle text-yellow-500 mr-1"></i> Pending</span>
                        <span class="font-medium">{{ $pendingPercentage ?? 25 }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $pendingPercentage ?? 25 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-slate-600"><i class="fas fa-circle text-red-500 mr-1"></i> Rejected</span>
                        <span class="font-medium">{{ $rejectedPercentage ?? 10 }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-red-400 to-red-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $rejectedPercentage ?? 10 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-slate-600"><i class="fas fa-circle text-blue-500 mr-1"></i> Disbursed</span>
                        <span class="font-medium">{{ $disbursedPercentage ?? 5 }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-400 to-blue-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $disbursedPercentage ?? 5 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lender Statistics --}}
        <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow">
            <h4 class="text-lg font-semibold text-slate-700 mb-4">
                <i class="fas fa-chart-bar text-purple-500 mr-2"></i> Lender Statistics
            </h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 text-center hover:scale-105 transition-transform">
                    <p class="text-3xl font-bold text-blue-700">{{ $totalLenders ?? 25 }}</p>
                    <p class="text-xs text-slate-500 mt-1"><i class="fas fa-users mr-1"></i> Total Lenders</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 text-center hover:scale-105 transition-transform">
                    <p class="text-3xl font-bold text-green-700">{{ $activeLenders ?? 22 }}</p>
                    <p class="text-xs text-slate-500 mt-1"><i class="fas fa-user-check mr-1"></i> Active Lenders</p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 text-center hover:scale-105 transition-transform">
                    <p class="text-2xl font-bold text-purple-700">₹{{ number_format($avgLoanPerLender ?? 1800000) }}</p>
                    <p class="text-xs text-slate-500 mt-1"><i class="fas fa-money-bill-wave mr-1"></i> Avg Loan Per Lender</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-4 text-center hover:scale-105 transition-transform">
                    <p class="text-3xl font-bold text-yellow-700">{{ $avgApprovalTime ?? 2.5 }}</p>
                    <p class="text-xs text-slate-500 mt-1"><i class="fas fa-clock mr-1"></i> Avg Approval Days</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Loan Applications Table --}}
    <div class="bg-white rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <h4 class="text-lg font-semibold text-slate-700">
                    <i class="fas fa-list text-blue-500 mr-2"></i> Recent Loan Applications
                </h4>
                <p class="text-slate-400 text-sm mt-1">Latest loan requests from borrowers</p>
            </div>
            <div class="flex gap-2">
                <a href="#" class="text-blue-600 text-sm hover:underline flex items-center gap-1">
                    View All <i class="fas fa-arrow-right text-xs"></i>
                </a>
                <button class="text-slate-400 hover:text-slate-600 text-sm">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left py-3 px-3 text-slate-500 font-medium"><i class="fas fa-user mr-1"></i> Borrower</th>
                        <th class="text-left py-3 px-3 text-slate-500 font-medium"><i class="fas fa-rupee-sign mr-1"></i> Amount</th>
                        <th class="text-left py-3 px-3 text-slate-500 font-medium"><i class="fas fa-university mr-1"></i> Lender</th>
                        <th class="text-left py-3 px-3 text-slate-500 font-medium"><i class="fas fa-tag mr-1"></i> Status</th>
                        <th class="text-left py-3 px-3 text-slate-500 font-medium"><i class="fas fa-calendar mr-1"></i> Date</th>
                        <th class="text-left py-3 px-3 text-slate-500 font-medium"><i class="fas fa-cog mr-1"></i> Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLoans ?? [] as $loan)
                    <tr class="border-b border-slate-100 hover:bg-blue-50 transition group">
                        <td class="py-3 px-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ substr($loan->borrower_name ?? 'JD', 0, 2) }}
                                </div>
                                <span class="font-medium text-slate-800">{{ $loan->borrower_name ?? 'John Doe' }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-3 font-semibold text-slate-800">₹{{ number_format($loan->amount ?? 500000) }}</td>
                        <td class="py-3 px-3 text-slate-600">{{ $loan->lender_name ?? 'ABC Finance' }}</td>
                        <td class="py-3 px-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium inline-flex items-center gap-1
                                @if($loan->status == 'approved') bg-green-100 text-green-700
                                @elseif($loan->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($loan->status == 'rejected') bg-red-100 text-red-700
                                @elseif($loan->status == 'disbursed') bg-blue-100 text-blue-700
                                @else bg-slate-100 text-slate-700 @endif">
                                <i class="fas fa-circle text-[6px]"></i>
                                {{ ucfirst($loan->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-slate-500">{{ $loan->created_at->format('d M Y') ?? '2026-06-15' }}</td>
                        <td class="py-3 px-3">
                            <div class="flex items-center gap-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-xs font-medium hover:underline">View</a>
                                <button class="text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400">
                            <i class="fas fa-inbox text-4xl block mb-3 opacity-20"></i>
                            No recent loan applications
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection