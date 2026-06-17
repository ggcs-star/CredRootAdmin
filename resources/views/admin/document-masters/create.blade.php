@extends('admin.layouts.app')

@section('title', 'Add Document Master')
@section('page-title', 'Add Document Master')
@section('page-subtitle', 'Create document requirements')

@section('content')

<div class="max-w-5xl mx-auto">


<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">

    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
        <h2 class="text-2xl font-bold text-white">
            Add Document Master
        </h2>
        <p class="text-blue-100 text-sm mt-1">
            Configure document requirements for entities
        </p>
    </div>

    <form action="{{ route('document-masters.store') }}"
          method="POST"
          class="p-6">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Document Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Enter document name"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300">

                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Entity Type
                </label>

                <select name="entity_type"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300">

                    <option value="">Select Entity Type</option>
                    <option value="Proprietorship">Proprietorship</option>
                    <option value="Partnership">Partnership</option>
                    <option value="LLP">LLP</option>
                    <option value="Pvt Ltd">Private Limited</option>

                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Collection Stage
                </label>

                <select name="collection_stage"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300">

                    <option value="pre_qualification">
                        Pre Qualification
                    </option>

                    <option value="final_application">
                        Final Application
                    </option>

                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Mandatory
                </label>

                <select name="is_mandatory"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300">

                    <option value="1">Yes</option>
                    <option value="0">No</option>

                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Status
                </label>

                <select name="status"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300">

                    <option value="1">Active</option>
                    <option value="0">Inactive</option>

                </select>
            </div>

        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-200">

            <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">
                Save Document
            </button>

            <a href="{{ route('document-masters.index') }}"
               class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl">
                Cancel
            </a>

        </div>

    </form>

</div>


</div>

@endsection
