@extends('admin.layouts.app')

@section('title', 'Edit Document Master')
@section('page-title', 'Edit Document Master')
@section('page-subtitle', 'Update document requirements')

@section('content')

<div class="max-w-5xl">

```
<div class="bg-white rounded-xl shadow-sm border border-slate-200">

    <div class="px-6 py-5 border-b border-slate-200">
        <h3 class="text-lg font-semibold text-slate-800">
            Edit Document Master
        </h3>
    </div>

    <form action="{{ route('document-masters.update', $documentMaster->id) }}"
          method="POST"
          class="p-6">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Document Name
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $documentMaster->name) }}"
                       class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                @error('name')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Entity Type
                </label>

                <select name="entity_type"
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg">

                    <option value="Proprietorship"
                        {{ old('entity_type', $documentMaster->entity_type) == 'Proprietorship' ? 'selected' : '' }}>
                        Proprietorship
                    </option>

                    <option value="Partnership"
                        {{ old('entity_type', $documentMaster->entity_type) == 'Partnership' ? 'selected' : '' }}>
                        Partnership
                    </option>

                    <option value="LLP"
                        {{ old('entity_type', $documentMaster->entity_type) == 'LLP' ? 'selected' : '' }}>
                        LLP
                    </option>

                    <option value="Pvt Ltd"
                        {{ old('entity_type', $documentMaster->entity_type) == 'Pvt Ltd' ? 'selected' : '' }}>
                        Pvt Ltd
                    </option>

                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Collection Stage
                </label>

                <select name="collection_stage"
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg">

                    <option value="pre_qualification"
                        {{ old('collection_stage', $documentMaster->collection_stage) == 'pre_qualification' ? 'selected' : '' }}>
                        Pre Qualification
                    </option>

                    <option value="final_application"
                        {{ old('collection_stage', $documentMaster->collection_stage) == 'final_application' ? 'selected' : '' }}>
                        Final Application
                    </option>

                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Mandatory
                </label>

                <select name="is_mandatory"
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg">

                    <option value="1"
                        {{ old('is_mandatory', $documentMaster->is_mandatory) == 1 ? 'selected' : '' }}>
                        Yes
                    </option>

                    <option value="0"
                        {{ old('is_mandatory', $documentMaster->is_mandatory) == 0 ? 'selected' : '' }}>
                        No
                    </option>

                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Status
                </label>

                <select name="status"
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg">

                    <option value="1"
                        {{ old('status', $documentMaster->status) == 1 ? 'selected' : '' }}>
                        Active
                    </option>

                    <option value="0"
                        {{ old('status', $documentMaster->status) == 0 ? 'selected' : '' }}>
                        Inactive
                    </option>

                </select>
            </div>

        </div>

        <div class="mt-8 flex gap-3">

            <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Update Document
            </button>

            <a href="{{ route('document-masters.index') }}"
               class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg">
                Cancel
            </a>

        </div>

    </form>

</div>


</div>

@endsection
