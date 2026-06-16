@extends('admin.layouts.app')

@section('title', 'Add Bank')

@section('page-title', 'Add Bank')

@section('content')

<div class="max-w-5xl mx-auto">


<div class="bg-white rounded-2xl shadow-sm border border-slate-200">

    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold">
            Add New Bank
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Create a new business loan lender bank.
        </p>
    </div>

    <form action="{{ route('admin.banks.store') }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Bank Name -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Bank Name *
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="w-full border rounded-xl px-4 py-3"
                       placeholder="HDFC Bank">

                @error('name')
                    <span class="text-red-500 text-sm">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <!-- Bank Code -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Bank Code
                </label>

                <input type="text"
                       name="code"
                       value="{{ old('code') }}"
                       class="w-full border rounded-xl px-4 py-3"
                       placeholder="HDFC">
            </div>

            <!-- Contact Person -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Contact Person
                </label>

                <input type="text"
                       name="contact_person"
                       value="{{ old('contact_person') }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Email
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Phone -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Phone
                </label>

                <input type="text"
                       name="phone"
                       value="{{ old('phone') }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Logo -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Logo
                </label>

                <input type="file"
                       name="logo"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Min Loan -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Min Loan Amount
                </label>

                <input type="number"
                       name="min_loan_amount"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Max Loan -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Max Loan Amount
                </label>

                <input type="number"
                       name="max_loan_amount"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Interest From -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Interest Rate From (%)
                </label>

                <input type="number"
                       step="0.01"
                       name="interest_rate_from"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Interest To -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Interest Rate To (%)
                </label>

                <input type="number"
                       step="0.01"
                       name="interest_rate_to"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Tenure -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Max Tenure (Months)
                </label>

                <input type="number"
                       name="max_tenure_months"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <!-- Status -->
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Status
                </label>

                <select name="status"
                        class="w-full border rounded-xl px-4 py-3">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

        </div>

        <div class="border-t p-6 flex justify-end gap-3">

            <a href="{{ route('admin.banks.index') }}"
               class="px-5 py-3 border rounded-xl">
                Cancel
            </a>

            <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl">
                Save Bank
            </button>

        </div>

    </form>

</div>


</div>

@endsection
