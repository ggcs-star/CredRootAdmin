@extends('admin.layouts.app')

@section('title', 'Edit Document Master')
@section('page-title', 'Edit Document Master')
@section('page-subtitle', 'Update document requirements')

@section('content')
<div class="max-w-5xl mx-auto">
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5">
        <h2 class="text-2xl font-bold text-white">Edit Document Master</h2>
        <p class="text-orange-100 text-sm mt-1">Modify document rules and UI validations</p>
    </div>

    <form action="{{ route('document-masters.update', $documentMaster->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')

        @php
            $selectedEntities = old('applicable_entities', $documentMaster->applicable_entities ?? []);
            $selectedLoans = old('applicable_loan_types', $documentMaster->applicable_loan_types ?? []);
        @endphp

        <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">1. Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Document Code *</label>
                <input type="text" name="document_code" value="{{ old('document_code', $documentMaster->document_code) }}" 
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 uppercase">
                @error('document_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Document Name *</label>
                <input type="text" name="name" value="{{ old('name', $documentMaster->name) }}" 
                       class="w-full px-4 py-3 rounded-xl border border-slate-300">
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-300">{{ old('description', $documentMaster->description) }}</textarea>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">2. Application Rules</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                <label class="block text-sm font-bold text-slate-800 mb-3">Applicable Entities</label>
                <p class="text-xs text-slate-500 mb-4">Leave all unchecked if it applies to ALL entities.</p>
                
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
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($loanTypes as $loan)
                        <label class="flex items-center p-3 bg-white border border-slate-200 rounded-lg cursor-pointer hover:bg-amber-50 hover:border-amber-200 transition">
                            <input type="checkbox" name="applicable_loan_types[]" value="{{ $loan->id }}" 
                                   class="w-5 h-5 text-amber-500 rounded border-slate-300 focus:ring-amber-500"
                                   {{ in_array($loan->id, $selectedLoans) ? 'checked' : '' }}>
                            <span class="ml-3 text-sm font-medium text-slate-700 line-clamp-1" title="{{ $loan->name }}">{{ $loan->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Collection Stage *</label>
                <select name="collection_stage" class="w-full px-4 py-3 rounded-xl border border-slate-300">
                    <option value="pre_qualification" {{ $documentMaster->collection_stage == 'pre_qualification' ? 'selected' : '' }}>Pre Qualification</option>
                    <option value="final_application" {{ $documentMaster->collection_stage == 'final_application' ? 'selected' : '' }}>Final Application</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Mandatory / Optional *</label>
                <select name="is_mandatory" class="w-full px-4 py-3 rounded-xl border border-slate-300">
                    <option value="1" {{ $documentMaster->is_mandatory ? 'selected' : '' }}>Mandatory</option>
                    <option value="0" {{ !$documentMaster->is_mandatory ? 'selected' : '' }}>Optional</option>
                </select>
            </div>
        </div>

        <h3 class="text-lg font-semibold text-slate-800 mb-4 border-b pb-2">3. UI & Validation Controls</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Sides Required *</label>
                <input type="number" name="sides_required" value="{{ old('sides_required', $documentMaster->sides_required) }}" class="w-full px-4 py-3 rounded-xl border border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Allowed Formats *</label>
                <input type="text" name="allowed_formats" value="{{ old('allowed_formats', $documentMaster->allowed_formats) }}" class="w-full px-4 py-3 rounded-xl border border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Max Size (KB) *</label>
                <input type="number" name="max_size_kb" value="{{ old('max_size_kb', $documentMaster->max_size_kb) }}" class="w-full px-4 py-3 rounded-xl border border-slate-300">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Sample Image</label>
                @if($documentMaster->sample_image_url)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $documentMaster->sample_image_url) }}" alt="Sample" class="h-20 object-contain rounded border border-slate-200">
                    </div>
                @endif
                <input type="file" name="sample_image" accept="image/jpeg,image/png" class="w-full px-4 py-3 rounded-xl border border-slate-300">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status *</label>
                <select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-300">
                    <option value="1" {{ $documentMaster->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $documentMaster->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-200">
            <button type="submit" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl">Update Document Rule</button>
            <a href="{{ route('document-masters.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection