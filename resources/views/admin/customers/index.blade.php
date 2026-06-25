@extends('admin.layouts.app')

@section('title', 'Customers Management')
@section('page-title', 'Customers')

@section('content')

<div class="space-y-6">

    {{-- Header & Search --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                <i class="fas fa-users text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Total Customers</h2>
                <p class="text-sm text-slate-500">{{ $totalCustomers }} registered users</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.customers.index') }}" class="w-full sm:w-auto">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." 
                       class="w-full sm:w-80 pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition">
            </div>
        </form>
    </div>

    {{-- Customers Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4 font-semibold text-slate-600 uppercase tracking-wider text-xs">Customer Details</th>
                        <th class="py-3 px-4 font-semibold text-slate-600 uppercase tracking-wider text-xs">Contact Info</th>
                        <th class="py-3 px-4 font-semibold text-slate-600 uppercase tracking-wider text-xs">Onboarding Step</th>
                        <th class="py-3 px-4 font-semibold text-slate-600 uppercase tracking-wider text-xs">Joined Date</th>
                        <th class="py-3 px-4 font-semibold text-slate-600 uppercase tracking-wider text-xs text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold flex-shrink-0 shadow-sm">
                                    {{ strtoupper(substr($customer->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-800">{{ $customer->name }}</p>
                                    <p class="text-xs text-slate-500">ID: #{{ $customer->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="space-y-1">
                                <a href="mailto:{{ $customer->email }}" class="flex items-center gap-1.5 text-slate-600 hover:text-blue-600 transition">
                                    <i class="fas fa-envelope text-slate-400"></i> {{ $customer->email }}
                                </a>
                                <a href="tel:{{ $customer->mobile }}" class="flex items-center gap-1.5 text-slate-600 hover:text-blue-600 transition">
                                    <i class="fas fa-phone-alt text-slate-400"></i> {{ $customer->mobile ?? 'N/A' }}
                                </a>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                Step {{ $customer->current_step ?? 1 }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-500">
                            {{ $customer->created_at->format('d M Y') }}
                            <span class="block text-xs text-slate-400">{{ $customer->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
    <a href="{{ route('admin.customers.show', $customer->id) }}" 
       class="inline-block px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-xs font-bold border border-blue-100">
        <i class="fas fa-eye mr-1"></i> View
    </a>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-slate-200">
                                <i class="fas fa-users text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-lg font-bold text-slate-600">No Customers Found</p>
                            <p class="text-sm mt-1">There are no users registered as customers yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($customers->hasPages())
        <div class="p-4 border-t border-slate-200 bg-white">
            {{ $customers->appends(request()->query())->links('pagination::tailwind') }}
        </div>
        @endif
    </div>
</div>

@endsection