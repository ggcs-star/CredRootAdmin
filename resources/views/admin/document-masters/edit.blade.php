@extends('admin.layouts.app')

@section('title', 'Edit Document Master')
@section('content')

<div class="max-w-6xl mx-auto px-3 sm:px-4">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-6">
        <div class="px-5 py-5 border-b border-slate-200 bg-amber-50">
            <h2 class="text-xl font-bold text-slate-800">Edit Document Master</h2>
        </div>

        <form action="{{ route('document-masters.update', $documentMaster->id) }}" method="POST" enctype="multipart/form-data" class="p-5 sm:p-8">
            @csrf
            @method('PUT')

            {{-- Section 1: Basic Information --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">1. Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Document Code <span class="text-red-500">*</span></label>
                        <input type="text" name="document_code" value="{{ old('document_code', $documentMaster->document_code) }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm uppercase">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Document Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $documentMaster->name) }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                        <textarea name="description" rows="2" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm">{{ old('description', $documentMaster->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Section 2: Application Rules --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">2. Application Rules</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    
                    {{-- NEW: Document Level --}}
                    <div class="md:col-span-2 bg-amber-50/50 rounded-xl p-4 border border-amber-100">
                        <label class="block text-sm font-bold text-amber-800 mb-2">
                            <i class="fas fa-layer-group mr-1.5"></i> Document Level (Scope) <span class="text-red-500">*</span>
                        </label>
                        <select name="document_level" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-white">
                            <option value="lead" {{ old('document_level', $documentMaster->document_level) == 'lead' ? 'selected' : '' }}>📄 Lead Level (Requested for every new Loan Application)</option>
                            <option value="company" {{ old('document_level', $documentMaster->document_level) == 'company' ? 'selected' : '' }}>🏢 Company Level (e.g. GST, Udyam - Uploaded once per company)</option>
                            <option value="user" {{ old('document_level', $documentMaster->document_level) == 'user' ? 'selected' : '' }}>👤 User Profile Level (e.g. Personal Aadhaar, PAN - Uploaded once per user)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Collection Stage <span class="text-red-500">*</span></label>
                        <select name="collection_stage" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-white">
                            <option value="pre_qualification" {{ old('collection_stage', $documentMaster->collection_stage) == 'pre_qualification' ? 'selected' : '' }}>Pre Qualification</option>
                            <option value="final_application" {{ old('collection_stage', $documentMaster->collection_stage) == 'final_application' ? 'selected' : '' }}>Final Application</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Requirement Type <span class="text-red-500">*</span></label>
                        <select name="is_mandatory" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-white">
                            <option value="1" {{ old('is_mandatory', $documentMaster->is_mandatory) == 1 ? 'selected' : '' }}>Mandatory</option>
                            <option value="0" {{ old('is_mandatory', $documentMaster->is_mandatory) == 0 ? 'selected' : '' }}>Optional</option>
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
                        <input type="number" name="sides_required" value="{{ old('sides_required', $documentMaster->sides_required) }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Allowed Formats <span class="text-red-500">*</span></label>
                        <input type="text" name="allowed_formats" value="{{ old('allowed_formats', $documentMaster->allowed_formats) }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Max Size (KB) <span class="text-red-500">*</span></label>
                        <input type="number" name="max_size_kb" value="{{ old('max_size_kb', $documentMaster->max_size_kb) }}" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm bg-white">
                            <option value="1" {{ old('status', $documentMaster->status) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $documentMaster->status) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-7 py-2.5 bg-amber-600 text-white rounded-xl font-medium">Update Document Rule</button>
            </div>
        </form>
    </div>
</div>
@endsection