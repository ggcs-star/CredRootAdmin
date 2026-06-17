@extends('admin.layouts.app')

@section('title', 'Add Lead Status')
@section('page-title', 'Add Lead Status')
@section('page-subtitle', 'Create lead pipeline status')

@section('content')

<div class="max-w-4xl mx-auto">


<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-6 py-5">
        <h2 class="text-2xl font-bold text-white">
            Add Lead Status
        </h2>
        <p class="text-green-100 text-sm mt-1">
            Create and manage lead pipeline stages
        </p>
    </div>

    <!-- Form -->
    <form action="{{ route('lead-statuses.store') }}"
          method="POST"
          class="p-6">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Status Name -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Status Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Enter status name"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none transition">

                @error('name')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Color -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Status Color
                </label>

                <input type="color"
                       name="color"
                       value="{{ old('color','#10b981') }}"
                       class="w-24 h-14 border border-slate-300 rounded-xl cursor-pointer">
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Sort Order
                </label>

                <input type="number"
                       name="sort_order"
                       value="{{ old('sort_order',0) }}"
                       placeholder="0"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100 outline-none">
            </div>

        </div>

        <!-- Preview -->
        <div class="mt-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
            <p class="text-sm text-slate-500 mb-2">
                Status Preview
            </p>

            <span class="inline-flex items-center px-4 py-2 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                New Lead Status
            </span>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-200">

            <button type="submit"
                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-medium shadow-md transition">
                Save Status
            </button>

            <a href="{{ route('lead-statuses.index') }}"
               class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition">
                Back
            </a>

        </div>

    </form>

</div>


</div>

@endsection
