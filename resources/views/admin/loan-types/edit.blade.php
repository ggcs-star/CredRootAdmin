@extends('admin.layouts.app')

@section('title', 'Edit Loan Type')
@section('page-title', 'Edit Loan Type')
@section('page-subtitle', 'Update loan product details')

@section('content')

<div class="max-w-5xl mx-auto">

<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
        <h2 class="text-2xl font-bold text-white">
            Edit Loan Type
        </h2>
        <p class="text-blue-100 text-sm mt-1">
            Update loan type information and settings
        </p>
    </div>

    <!-- Form -->
    <form action="{{ route('loan-types.update', $loanType->id) }}"
          method="POST"
          class="p-6">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">

            <!-- Loan Type Name -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Loan Type Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $loanType->name) }}"
                       placeholder="e.g. Working Capital Loan"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">

                @error('name')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Description
                </label>

                <textarea name="description"
                          rows="5"
                          placeholder="Enter loan type description..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">{{ old('description', $loanType->description) }}</textarea>
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Status
                </label>

                <select name="status"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">

                    <option value="1"
                        {{ old('status', $loanType->status) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('status', $loanType->status) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>
            </div>

        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-200">

            <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md transition">
                Update Loan Type
            </button>

            <a href="{{ route('loan-types.index') }}"
               class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition">
                Cancel
            </a>

        </div>

    </form>

</div>


</div>

@endsection
