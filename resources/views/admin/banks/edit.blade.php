@extends('admin.layouts.app')

@section('title', 'Edit Bank')

@section('page-title', 'Edit Bank')

@section('content')

<div class="max-w-5xl mx-auto">

<div class="bg-white rounded-2xl shadow-sm border border-slate-200">

    <div class="p-6 border-b">
        <h2 class="text-xl font-semibold">
            Edit Bank
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Update bank information.
        </p>
    </div>

    <form action="{{ route('admin.banks.update', $bank->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Bank Name *
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $bank->name) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Bank Code
                </label>

                <input type="text"
                       name="code"
                       value="{{ old('code', $bank->code) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Contact Person
                </label>

                <input type="text"
                       name="contact_person"
                       value="{{ old('contact_person', $bank->contact_person) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Email
                </label>

                <input type="email"
                       name="email"
                       value="{{ old('email', $bank->email) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Phone
                </label>

                <input type="text"
                       name="phone"
                       value="{{ old('phone', $bank->phone) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Logo
                </label>

                <input type="file"
                       name="logo"
                       class="w-full border rounded-xl px-4 py-3">

                @if($bank->logo)
                    <img src="{{ asset('storage/'.$bank->logo) }}"
                         class="w-16 h-16 mt-3 rounded-lg object-cover">
                @endif
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Min Loan Amount
                </label>

                <input type="number"
                       name="min_loan_amount"
                       value="{{ old('min_loan_amount', $bank->min_loan_amount) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Max Loan Amount
                </label>

                <input type="number"
                       name="max_loan_amount"
                       value="{{ old('max_loan_amount', $bank->max_loan_amount) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Interest Rate From (%)
                </label>

                <input type="number"
                       step="0.01"
                       name="interest_rate_from"
                       value="{{ old('interest_rate_from', $bank->interest_rate_from) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Interest Rate To (%)
                </label>

                <input type="number"
                       step="0.01"
                       name="interest_rate_to"
                       value="{{ old('interest_rate_to', $bank->interest_rate_to) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Max Tenure (Months)
                </label>

                <input type="number"
                       name="max_tenure_months"
                       value="{{ old('max_tenure_months', $bank->max_tenure_months) }}"
                       class="w-full border rounded-xl px-4 py-3">
            </div>

            <div>
                <label class="block mb-2 text-sm font-medium">
                    Status
                </label>

                <select name="status"
                        class="w-full border rounded-xl px-4 py-3">

                    <option value="1" {{ $bank->status ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0" {{ !$bank->status ? 'selected' : '' }}>
                        Inactive
                    </option>

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
                Update Bank
            </button>

        </div>

    </form>

</div>


</div>

@endsection
