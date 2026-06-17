@extends('admin.layouts.app')

@section('title', 'Loan Types')
@section('page-title', 'Loan Types')
@section('page-subtitle', 'Manage available loan products')

@section('content')

<div class="max-w-7xl mx-auto">

@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">

        <div>
            <h2 class="text-xl font-bold text-slate-800">
                Loan Types
            </h2>
            <p class="text-sm text-slate-500">
                Manage all available loan products
            </p>
        </div>

        <a href="{{ route('loan-types.create') }}"
           class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md transition">
            + Add Loan Type
        </a>

    </div>

    <!-- Table -->
    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-slate-50 border-b border-slate-200">

                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        #
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Loan Type
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Description
                    </th>

                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">
                        Status
                    </th>

                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                        Actions
                    </th>
                </tr>

            </thead>

            <tbody>

            @forelse($loanTypes as $loanType)

                <tr class="border-b border-slate-100 hover:bg-slate-50">

                    <td class="px-6 py-4 text-slate-600">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="font-medium text-slate-800">
                            {{ $loanType->name }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-slate-600 max-w-md">
                        {{ $loanType->description ?? 'N/A' }}
                    </td>

                    <td class="px-6 py-4">

                        @if($loanType->status)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                Inactive
                            </span>
                        @endif

                    </td>

                    <td class="px-6 py-4">

                        <div class="flex items-center justify-center gap-2">

                            <a href="{{ route('loan-types.edit', $loanType->id) }}"
                               class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition">
                                Edit
                            </a>

                            <form action="{{ route('loan-types.destroy', $loanType->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this loan type?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5"
                        class="text-center py-10 text-slate-500">
                        No Loan Types Found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-slate-200">
        {{ $loanTypes->links() }}
    </div>

</div>


</div>

@endsection
