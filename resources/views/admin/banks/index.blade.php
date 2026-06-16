@extends('admin.layouts.app')

@section('title', 'Banks')

@section('page-title', 'Banks')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-slate-200">


<!-- Header -->
<div class="flex items-center justify-between p-6 border-b">

    <div>
        <h2 class="text-xl font-semibold">
            Bank Management
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Manage all business loan partner banks.
        </p>
    </div>

    <a href="{{ route('admin.banks.create') }}"
       class="px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700">
        + Add Bank
    </a>

</div>

<!-- Success Message -->
@if(session('success'))
    <div class="m-6 p-4 rounded-xl bg-green-100 text-green-700">
        {{ session('success') }}
    </div>
@endif

<!-- Table -->
<div class="overflow-x-auto">

    <table class="w-full">

        <thead class="bg-slate-50">

            <tr class="text-left">

                <th class="px-6 py-4 font-semibold">
                    Logo
                </th>

                <th class="px-6 py-4 font-semibold">
                    Bank Name
                </th>

                <th class="px-6 py-4 font-semibold">
                    Contact
                </th>

                <th class="px-6 py-4 font-semibold">
                    Loan Range
                </th>

                <th class="px-6 py-4 font-semibold">
                    Interest Rate
                </th>

                <th class="px-6 py-4 font-semibold">
                    Status
                </th>

                <th class="px-6 py-4 font-semibold text-right">
                    Action
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($banks as $bank)

                <tr class="border-t hover:bg-slate-50">

                    <!-- Logo -->
                    <td class="px-6 py-4">

                        @if($bank->logo)

                            <img
                                src="{{ asset('storage/'.$bank->logo) }}"
                                class="w-12 h-12 rounded-lg object-cover">

                        @else

                            <div class="w-12 h-12 rounded-lg bg-slate-200 flex items-center justify-center">
                                🏦
                            </div>

                        @endif

                    </td>

                    <!-- Name -->
                    <td class="px-6 py-4">

                        <div class="font-semibold">
                            {{ $bank->name }}
                        </div>

                        <div class="text-sm text-slate-500">
                            {{ $bank->code }}
                        </div>

                    </td>

                    <!-- Contact -->
                    <td class="px-6 py-4">

                        <div>
                            {{ $bank->contact_person }}
                        </div>

                        <div class="text-sm text-slate-500">
                            {{ $bank->phone }}
                        </div>

                    </td>

                    <!-- Loan Range -->
                    <td class="px-6 py-4">

                        ₹{{ number_format($bank->min_loan_amount ?? 0) }}

                        -

                        ₹{{ number_format($bank->max_loan_amount ?? 0) }}

                    </td>

                    <!-- Interest -->
                    <td class="px-6 py-4">

                        {{ $bank->interest_rate_from }}%

                        -

                        {{ $bank->interest_rate_to }}%

                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4">

                        @if($bank->status)

                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                                Active
                            </span>

                        @else

                            <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                Inactive
                            </span>

                        @endif

                    </td>

                    <!-- Action -->
                    <td class="px-6 py-4">

                        <div class="flex justify-end gap-2">

                            <a href="{{ route('admin.banks.edit',$bank->id) }}"
                               class="px-4 py-2 bg-amber-500 text-white rounded-lg">
                                Edit
                            </a>

                            <form action="{{ route('admin.banks.destroy',$bank->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this bank?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7"
                        class="text-center py-10 text-slate-500">

                        No banks found.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>

<!-- Pagination -->
<div class="p-6 border-t">
    {{ $banks->links() }}
</div>


</div>

@endsection
