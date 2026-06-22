@extends('admin.layouts.app')

@section('title', 'Add Document Master')
@section('content')

<div class="max-w-6xl mx-auto px-3 sm:px-4">
    <div class="bg-white rounded-2xl shadow-lg shadow-indigo-500/5 border border-slate-200 overflow-hidden mb-6">
        <div class="px-5 py-5 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-indigo-50">
            <h2 class="text-xl font-bold text-slate-800">Add Document Master</h2>
        </div>

        <form action="{{ route('document-masters.store') }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-8">
            @csrf

            {{-- Section 1: Basic Information --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">1. Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Document Code <span class="text-red-500">*</span></label>
                        <input type="text" name="document_code" value="{{ old('document_code') }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm uppercase focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Document Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:border-indigo-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                        <textarea name="description" rows="2" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:border-indigo-500">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Section 2: Application Rules --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">2. Application Rules</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    
                    {{-- NEW: Document Level --}}
                    <div class="md:col-span-2 bg-indigo-50/50 rounded-xl p-4 border border-indigo-100">
                        <label class="block text-sm font-bold text-indigo-800 mb-2">
                            <i class="fas fa-layer-group mr-1.5"></i> Document Level (Scope) <span class="text-red-500">*</span>
                        </label>
                        <select name="document_level" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 bg-white">
                            <option value="lead" {{ old('document_level') == 'lead' ? 'selected' : '' }}>📄 Lead Level (Requested for every new Loan Application)</option>
                            <option value="company" {{ old('document_level') == 'company' ? 'selected' : '' }}>🏢 Company Level (e.g. GST, Udyam - Uploaded once per company)</option>
                            <option value="user" {{ old('document_level') == 'user' ? 'selected' : '' }}>👤 User Profile Level (e.g. Personal Aadhaar, PAN - Uploaded once per user)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Collection Stage <span class="text-red-500">*</span></label>
                        <select name="collection_stage" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-white">
                            <option value="pre_qualification">Pre Qualification</option>
                            <option value="final_application">Final Application</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Requirement Type <span class="text-red-500">*</span></label>
                        <select name="is_mandatory" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-white">
                            <option value="1">Mandatory</option>
                            <option value="0">Optional</option>
                        </select>
                    </div>
                    
                    </div>
            </div>

            {{-- Section 3: UI & Validation Controls --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">3. Validation Controls</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sides Required <span class="text-red-500">*</span></label>
                        <input type="number" name="sides_required" value="{{ old('sides_required', 1) }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Allowed Formats <span class="text-red-500">*</span></label>
                        <input type="text" name="allowed_formats" value="{{ old('allowed_formats', 'jpg,jpeg,png,pdf') }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Max Size (KB) <span class="text-red-500">*</span></label>
                        <input type="number" name="max_size_kb" value="{{ old('max_size_kb', 5120) }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-white">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-7 py-2.5 bg-indigo-600 text-white rounded-xl font-medium">Save Document Rule</button>
            </div>
        </form>
    </div>
</div>
@endsection