@extends('admin.layouts.app')

@section('title', 'Add Document Master')
@section('page-title', 'Add Document Master')
@section('page-subtitle', 'Create document requirements')

@section('content')

<div class="max-w-6xl mx-auto px-3 sm:px-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs sm:text-sm text-slate-500 overflow-x-auto whitespace-nowrap pb-4">
        <a href="{{ route('admin.dashboard.index') }}" class="hover:text-indigo-600 transition flex-shrink-0">
            <i class="fas fa-home"></i>
        </a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <a href="{{ route('document-masters.index') }}" class="hover:text-indigo-600 transition flex-shrink-0">Document Masters</a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <span class="text-slate-700 font-medium truncate">Add New Document</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-lg shadow-indigo-500/5 border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-50 via-blue-50/50 to-indigo-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-3">
                        <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-file-alt text-base sm:text-lg"></i>
                        </span>
                        <span>Add Document Master</span>
                    </h2>
                    <p class="text-sm text-slate-500 mt-1 ml-0 sm:ml-14">
                        <i class="fas fa-info-circle text-indigo-400 mr-1"></i>
                        Configure dynamic document requirements for loan applications
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="bg-red-100 text-red-600 px-2.5 py-1 rounded-lg flex items-center gap-1.5">
                        <i class="fas fa-asterisk text-[8px]"></i> 
                        <span class="hidden xs:inline">Required fields</span>
                        <span class="xs:hidden">Required</span>
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('document-masters.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="p-5 sm:p-8">

            @csrf

            {{-- Section 1: Basic Information --}}
            <div class="mb-8">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-indigo-500/20">
                        1
                    </span>
                    <h3 class="text-lg font-bold text-slate-800">Basic Information</h3>
                    <span class="text-xs text-slate-400 ml-2">Document identification</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    {{-- Document Code --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Document Code <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-code text-slate-400 text-sm"></i>
                            </div>
                            <input type="text" 
                                   name="document_code" 
                                   value="{{ old('document_code') }}" 
                                   placeholder="e.g. PAN_CARD"
                                   class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm uppercase focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('document_code') border-red-500 ring-2 ring-red-500/20 @enderror">
                        </div>
                        @error('document_code')
                            <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Document Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Document Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-file text-slate-400 text-sm"></i>
                            </div>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="e.g. PAN Card"
                                   class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('name') border-red-500 ring-2 ring-red-500/20 @enderror">
                        </div>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-align-left text-indigo-500 mr-1.5"></i> Description (Instructions for user)
                        </label>
                        <div class="relative">
                            <textarea name="description" 
                                      rows="3" 
                                      placeholder="e.g. Upload a clear front picture of your PAN card. Ensure all details are visible."
                                      class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('description') border-red-500 ring-2 ring-red-500/20 @enderror">{{ old('description') }}</textarea>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            Instructions that will be shown to users when uploading this document
                        </p>
                    </div>
                </div>
            </div>

            {{-- Section 2: Application Rules --}}
            <div class="mb-8">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-indigo-500/20">
                        2
                    </span>
                    <h3 class="text-lg font-bold text-slate-800">Application Rules</h3>
                    <span class="text-xs text-slate-400 ml-2">Where this document applies</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    {{-- Applicable Entities --}}
                    <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200">
                        <label class="block text-sm font-bold text-slate-800 mb-2">
                            <i class="fas fa-users text-indigo-500 mr-1.5"></i> Applicable Entities
                        </label>
                        <p class="text-xs text-slate-500 mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Leave all unchecked if it applies to ALL entities
                        </p>
                        
                        @php $oldEntities = old('applicable_entities', []); @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($entityTypes as $entity)
                                <label class="flex items-center p-2.5 bg-white border border-slate-200 rounded-lg cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/50 transition-all group">
                                    <input type="checkbox" 
                                           name="applicable_entities[]" 
                                           value="{{ $entity }}" 
                                           class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 focus:ring-2 transition"
                                           {{ in_array($entity, $oldEntities) ? 'checked' : '' }}>
                                    <span class="ml-2.5 text-sm font-medium text-slate-700 group-hover:text-indigo-700 transition">{{ $entity }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Applicable Loan Types --}}
                    <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200">
                        <label class="block text-sm font-bold text-slate-800 mb-2">
                            <i class="fas fa-handshake text-blue-500 mr-1.5"></i> Applicable Loan Types
                        </label>
                        <p class="text-xs text-slate-500 mb-3">
                            <i class="fas fa-info-circle mr-1"></i>
                            Leave all unchecked if it applies to ALL loans
                        </p>
                        
                        @php $oldLoans = old('applicable_loan_types', []); @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto pr-1">
                            @foreach($loanTypes as $loan)
                                <label class="flex items-center p-2.5 bg-white border border-slate-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50/50 transition-all group">
                                    <input type="checkbox" 
                                           name="applicable_loan_types[]" 
                                           value="{{ $loan->id }}" 
                                           class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 focus:ring-2 transition"
                                           {{ in_array($loan->id, $oldLoans) ? 'checked' : '' }}>
                                    <span class="ml-2.5 text-sm font-medium text-slate-700 group-hover:text-blue-700 transition truncate" title="{{ $loan->name }}">{{ $loan->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Collection Stage --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-clock text-purple-500 mr-1.5"></i> Collection Stage <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-layer-group text-slate-400 text-sm"></i>
                            </div>
                            <select name="collection_stage" 
                                    class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all appearance-none bg-white">
                                <option value="pre_qualification" {{ old('collection_stage') == 'pre_qualification' ? 'selected' : '' }}>
                                    🔍 Pre Qualification
                                </option>
                                <option value="final_application" {{ old('collection_stage') == 'final_application' ? 'selected' : '' }}>
                                    📋 Final Application
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-slate-400 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            When should this document be collected from the user?
                        </p>
                    </div>

                    {{-- Mandatory / Optional --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-asterisk text-red-500 mr-1.5"></i> Requirement Type <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-check-circle text-slate-400 text-sm"></i>
                            </div>
                            <select name="is_mandatory" 
                                    class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all appearance-none bg-white">
                                <option value="1" {{ old('is_mandatory') == '1' ? 'selected' : '' }}>
                                    ⚠️ Mandatory
                                </option>
                                <option value="0" {{ old('is_mandatory') == '0' ? 'selected' : '' }}>
                                    ✅ Optional
                                </option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-slate-400 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            Is this document required for application completion?
                        </p>
                    </div>
                </div>
            </div>

            {{-- Section 3: UI & Validation Controls --}}
            <div class="mb-8">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-lg shadow-indigo-500/20">
                        3
                    </span>
                    <h3 class="text-lg font-bold text-slate-800">UI &amp; Validation Controls</h3>
                    <span class="text-xs text-slate-400 ml-2">Document validation rules</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                    {{-- Sides Required --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-copy text-purple-500 mr-1.5"></i> Sides Required <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-layer-group text-slate-400 text-sm"></i>
                            </div>
                            <input type="number" 
                                   name="sides_required" 
                                   value="{{ old('sides_required', 1) }}" 
                                   min="0" 
                                   class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('sides_required') border-red-500 ring-2 ring-red-500/20 @enderror">
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            1 = Front, 2 = Front+Back, 0 = PDF File
                        </p>
                        @error('sides_required')
                            <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Allowed Formats --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-file-code text-blue-500 mr-1.5"></i> Allowed Formats <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-tag text-slate-400 text-sm"></i>
                            </div>
                            <input type="text" 
                                   name="allowed_formats" 
                                   value="{{ old('allowed_formats', 'jpg,jpeg,png,pdf') }}" 
                                   class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('allowed_formats') border-red-500 ring-2 ring-red-500/20 @enderror">
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            Comma-separated formats (e.g., jpg,jpeg,png,pdf)
                        </p>
                        @error('allowed_formats')
                            <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Max Size --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-weight-hanging text-green-500 mr-1.5"></i> Max Size (KB) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-database text-slate-400 text-sm"></i>
                            </div>
                            <input type="number" 
                                   name="max_size_kb" 
                                   value="{{ old('max_size_kb', 5120) }}" 
                                   class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('max_size_kb') border-red-500 ring-2 ring-red-500/20 @enderror">
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            Maximum file size in KB (e.g., 5120 = 5MB)
                        </p>
                        @error('max_size_kb')
                            <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Sample Image --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-image text-purple-500 mr-1.5"></i> Sample Image (Optional)
                        </label>
                        <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-4 text-center hover:border-indigo-400 transition-all group @error('sample_image') border-red-500 @enderror">
                            <input type="file" 
                                   name="sample_image" 
                                   id="sample_image"
                                   accept="image/jpeg,image/png"
                                   class="absolute inset-0 opacity-0 cursor-pointer z-10"
                                   onchange="previewSample(event)">
                            <div class="flex flex-col items-center">
                                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center group-hover:bg-indigo-50 transition-all">
                                    <i class="fas fa-cloud-upload-alt text-xl text-slate-400 group-hover:text-indigo-500 transition-all"></i>
                                </div>
                                <p class="mt-1.5 text-sm font-medium text-slate-700">Click to upload sample image</p>
                                <p class="text-xs text-slate-400">JPG, PNG (Max 2MB)</p>
                                <div id="samplePreview" class="mt-2 hidden">
                                    <img id="samplePreviewImg" src="#" alt="Sample Preview" class="h-16 w-auto object-contain rounded-lg shadow-md">
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            Used to guide users on how the document should look
                        </p>
                        @error('sample_image')
                            <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            <i class="fas fa-toggle-on text-indigo-500 mr-1.5"></i> Status <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-circle text-slate-400 text-[10px]"></i>
                            </div>
                            <select name="status" 
                                    class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all appearance-none bg-white">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>🟢 Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>🔴 Inactive</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-slate-400 text-sm"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            Active documents will be shown to users
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 mt-8 pt-6 border-t border-slate-200">
                <div class="text-xs text-slate-400 text-center sm:text-left">
                    <i class="fas fa-shield-alt text-indigo-400 mr-1.5"></i> 
                    All information is secure and encrypted
                </div>
                <div class="flex flex-wrap gap-2.5 justify-center sm:justify-end">
                    <a href="{{ route('document-masters.index') }}" 
                       class="flex-1 sm:flex-none px-5 py-2.5 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all font-medium text-sm text-center">
                        <i class="fas fa-arrow-left mr-1.5"></i> Cancel
                    </a>
                    <button type="reset" 
                            class="flex-1 sm:flex-none px-5 py-2.5 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all font-medium text-sm text-center">
                        <i class="fas fa-undo mr-1.5"></i> Reset
                    </button>
                    <button type="submit" 
                            class="flex-1 sm:flex-none px-7 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-save"></i>
                        <span>Save Document Rule</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

    {{-- Quick Tips --}}
    <div class="mt-5 bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-4 flex flex-col sm:flex-row items-start gap-3">
        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-lightbulb text-indigo-600"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-indigo-800">💡 Quick Tips</p>
            <ul class="text-sm text-indigo-700 space-y-0.5 mt-1">
                <li class="flex items-start gap-2">
                    <span class="text-indigo-400 mt-0.5">•</span>
                    <span><strong>Document Code</strong> should be unique and descriptive (e.g., PAN_CARD)</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-indigo-400 mt-0.5">•</span>
                    <span><strong>Applicable Entities/Loans</strong> control where the document appears</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-indigo-400 mt-0.5">•</span>
                    <span><strong>Collection Stage</strong> determines when the document is requested</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-indigo-400 mt-0.5">•</span>
                    <span><strong>Sample Image</strong> helps users understand the required format</span>
                </li>
            </ul>
        </div>
    </div>

</div>

<script>
    // Sample image preview
    function previewSample(event) {
        const input = event.target;
        const previewDiv = document.getElementById('samplePreview');
        const previewImg = document.getElementById('samplePreviewImg');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.classList.remove('hidden');
                previewDiv.classList.add('animate-fadeIn');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Reset confirmation
    document.querySelector('button[type="reset"]')?.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to reset all fields?')) {
            e.preventDefault();
        }
    });

    // Auto-generate document code from name
    document.querySelector('input[name="name"]')?.addEventListener('input', function() {
        const codeField = document.querySelector('input[name="document_code"]');
        if (codeField && !codeField.value) {
            const name = this.value.trim();
            if (name) {
                const code = name.toUpperCase().replace(/[^A-Z0-9]/g, '_');
                codeField.placeholder = 'Auto: ' + code;
            }
        }
    });

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);
</script>

<style>
    /* Extra small screens */
    @media (max-width: 480px) {
        .xs\:inline { display: inline !important; }
        .xs\:hidden { display: none !important; }
        .xs\:flex { display: flex !important; }
    }

    /* Custom select styling */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-size: 1.5rem 1.5rem;
        background-repeat: no-repeat;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    /* Smooth transitions */
    .transition-all {
        transition: all 0.2s ease;
    }

    /* Touch device optimizations */
    @media (hover: none) {
        .hover\:shadow-lg:hover { box-shadow: none !important; }
        .hover\:-translate-y-0\.5:hover { transform: none !important; }
        .hover\:bg-slate-50:hover { background: inherit !important; }
        .hover\:border-indigo-300:hover { border-color: inherit !important; }
    }

    /* Safe area support */
    @supports (padding: max(0px)) {
        .px-3 { 
            padding-left: max(0.75rem, env(safe-area-inset-left)); 
            padding-right: max(0.75rem, env(safe-area-inset-right)); 
        }
    }

    /* Custom checkbox styling */
    input[type="checkbox"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        background: white;
        transition: all 0.2s ease;
        position: relative;
        flex-shrink: 0;
        cursor: pointer;
    }
    input[type="checkbox"]:checked {
        background: #6366f1;
        border-color: #6366f1;
    }
    input[type="checkbox"]:checked::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
        font-weight: bold;
    }
    input[type="checkbox"]:focus {
        ring: 2px solid #6366f1;
    }

    /* Scrollbar for loan types */
    .max-h-48::-webkit-scrollbar {
        width: 4px;
    }
    .max-h-48::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .max-h-48::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .max-h-48::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Textarea resize */
    textarea {
        resize: vertical;
        min-height: 80px;
    }
</style>

@endsection