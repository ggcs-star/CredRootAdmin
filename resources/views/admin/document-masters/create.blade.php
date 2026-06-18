@extends('admin.layouts.app')

@section('title', 'Add Document Master')
@section('page-title', 'Add Document Master')
@section('page-subtitle', 'Create document requirements')

@section('content')
<div class="max-w-5xl mx-auto">
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
        <h2 class="text-2xl font-bold text-white">Add Document Master</h2>
        <p class="text-blue-100 text-sm mt-1">Configure dynamic document requirements</p>
    </div>

    <form action="{{ route('document-masters.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf

        <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">1. Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Document Code *</label>
                <input type="text" name="document_code" value="{{ old('document_code') }}" placeholder="e.g. PAN_CARD"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 uppercase">
                @error('document_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Document Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. PAN Card"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300">
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description (Instructions for user)</label>
                <textarea name="description" rows="3" placeholder="Upload clear front picture..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-300">{{ old('description') }}</textarea>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">2. Application Rules</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <label class="block text-sm font-bold text-slate-800 mb-3">Applicable Entities</label>
                <p class="text-xs text-slate-500 mb-4">Leave all unchecked if it applies to ALL entities.</p>
                
                @php $oldEntities = old('applicable_entities', []); @endphp
               <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    @foreach($entityTypes as $entity)
        <label class="flex items-center p-3 bg-white border border-slate-200 rounded-lg cursor-pointer hover:bg-amber-50 hover:border-amber-200 transition">
            
            <input type="checkbox" name="applicable_entities[]" value="{{ $entity }}" 
                   class="w-5 h-5 text-amber-500 rounded border-slate-300 focus:ring-amber-500"
                   {{ in_array($entity, $selectedEntities) ? 'checked' : '' }}>
            
            <span class="ml-3 text-sm font-medium text-slate-700">{{ $entity }}</span>
            
        </label>
    @endforeach
</div>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <label class="block text-sm font-bold text-slate-800 mb-3">Applicable Loan Types</label>
                <p class="text-xs text-slate-500 mb-4">Leave all unchecked if it applies to ALL loans.</p>
                
                @php $oldLoans = old('applicable_loan_types', []); @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($loanTypes as $loan)
                        <label class="flex items-center p-3 bg-white border border-slate-200 rounded-lg cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition">
                            <input type="checkbox" name="applicable_loan_types[]" value="{{ $loan->id }}" 
                                   class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500"
                                   {{ in_array($loan->id, $oldLoans) ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-medium text-slate-700 line-clamp-1" title="{{ $loan->name }}">{{ $loan->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Collection Stage *</label>
                <select name="collection_stage" class="w-full px-4 py-3 rounded-xl border border-slate-300">
                    <option value="pre_qualification">Pre Qualification</option>
                    <option value="final_application">Final Application</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Mandatory / Optional *</label>
                <select name="is_mandatory" class="w-full px-4 py-3 rounded-xl border border-slate-300">
                    <option value="1">Mandatory</option>
                    <option value="0">Optional</option>
                </select>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">3. UI & Validation Controls</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Sides Required *</label>
                <input type="number" name="sides_required" value="{{ old('sides_required', 1) }}" min="0" class="w-full px-4 py-3 rounded-xl border border-slate-300">
                <p class="text-xs text-slate-500 mt-1">1 = Front, 2 = Front+Back, 0 = PDF File</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Allowed Formats *</label>
                <input type="text" name="allowed_formats" value="{{ old('allowed_formats', 'jpg,jpeg,png,pdf') }}" class="w-full px-4 py-3 rounded-xl border border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Max Size (KB) *</label>
                <input type="number" name="max_size_kb" value="{{ old('max_size_kb', 5120) }}" class="w-full px-4 py-3 rounded-xl border border-slate-300">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Sample Image (Optional)</label>
                <input type="file" name="sample_image" accept="image/jpeg,image/png" class="w-full px-4 py-3 rounded-xl border border-slate-300">
                <p class="text-xs text-slate-500 mt-1">Used to guide users on how the document looks.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-300">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-200">
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl">Save Document Rule</button>
            <a href="{{ route('document-masters.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection