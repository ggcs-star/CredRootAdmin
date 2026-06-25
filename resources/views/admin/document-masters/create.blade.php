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
                        <input type="text" name="document_code" value="{{ old('document_code') }}" 
                               class="w-full border rounded-xl px-4 py-3 text-sm uppercase focus:ring-1 outline-none transition
                               @error('document_code') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        @error('document_code')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Document Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-1 outline-none transition
                               @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        @error('name')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Description</label>
                        <textarea name="description" rows="2" 
                                  class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-1 outline-none transition
                                  @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Application Rules --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">2. Application Rules</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    
                    {{-- Document Level --}}
                    <div class="md:col-span-2 bg-indigo-50/50 rounded-xl p-4 border @error('document_level') border-red-300 bg-red-50/50 @else border-indigo-100 @enderror">
                        <label class="block text-sm font-bold text-indigo-800 mb-2">
                            <i class="fas fa-layer-group mr-1.5"></i> Document Level (Scope) <span class="text-red-500">*</span>
                        </label>
                        <select name="document_level" 
                                class="w-full border rounded-xl px-4 py-3 text-sm bg-white focus:ring-1 outline-none transition
                                @error('document_level') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                            <option value="lead" {{ old('document_level') == 'lead' ? 'selected' : '' }}>📄 Lead Level (Requested for every new Loan Application)</option>
                            <option value="company" {{ old('document_level') == 'company' ? 'selected' : '' }}>🏢 Company Level (e.g. GST, Udyam - Uploaded once per company)</option>
                            <option value="user" {{ old('document_level') == 'user' ? 'selected' : '' }}>👤 User Profile Level (e.g. Personal Aadhaar, PAN - Uploaded once per user)</option>
                        </select>
                        @error('document_level')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Collection Stage <span class="text-red-500">*</span></label>
                        <select name="collection_stage" 
                                class="w-full border rounded-xl px-4 py-3 text-sm bg-white focus:ring-1 outline-none transition
                                @error('collection_stage') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                            <option value="pre_qualification" {{ old('collection_stage') == 'pre_qualification' ? 'selected' : '' }}>Pre Qualification</option>
                            <option value="final_application" {{ old('collection_stage') == 'final_application' ? 'selected' : '' }}>Final Application</option>
                        </select>
                        @error('collection_stage')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Requirement Type <span class="text-red-500">*</span></label>
                        <select name="is_mandatory" 
                                class="w-full border rounded-xl px-4 py-3 text-sm bg-white focus:ring-1 outline-none transition
                                @error('is_mandatory') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                            <option value="1" {{ old('is_mandatory') == '1' ? 'selected' : '' }}>Mandatory</option>
                            <option value="0" {{ old('is_mandatory', '0') == '0' ? 'selected' : '' }}>Optional</option>
                        </select>
                        @error('is_mandatory')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Applicability (NEW) --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">3. Applicability</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                    
                    {{-- Loan Types Checkboxes --}}
                    <div class="bg-slate-50 rounded-xl p-5 border @error('applicable_loan_types') border-red-300 @else border-slate-200 @enderror">
                        <label class="block text-sm font-bold text-slate-800 mb-3">
                            <i class="fas fa-money-check-alt text-blue-500 mr-1.5"></i> Applicable Loan Types <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($loanTypes as $loan)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" name="applicable_loan_types[]" value="{{ $loan->id }}" 
                                           class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500"
                                           {{ in_array($loan->id, old('applicable_loan_types', [])) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600 transition">{{ $loan->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('applicable_loan_types')
                            <p class="text-xs text-red-500 mt-2 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Entity Types Checkboxes --}}
                    <div class="bg-slate-50 rounded-xl p-5 border @error('entity_types') border-red-300 @else border-slate-200 @enderror">
                        <label class="block text-sm font-bold text-slate-800 mb-3">
                            <i class="fas fa-building text-indigo-500 mr-1.5"></i> Applicable Entity Types <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($entityTypes as $entity)
                                <label class="flex items-center gap-2.5 cursor-pointer group">
                                    <input type="checkbox" name="applicable_entities[]" value="{{ $entity }}" 
                                           class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500"
                                           {{ in_array($entity, old('applicable_entities', [])) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium text-slate-700 group-hover:text-indigo-600 transition">{{ $entity }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('applicable_entities')
                            <p class="text-xs text-red-500 mt-2 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Section 4: UI & Validation Controls --}}
            <div class="mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">4. Validation Controls</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sides Required <span class="text-red-500">*</span></label>
                        <input type="number" name="sides_required" value="{{ old('sides_required', 1) }}" min="1" max="2"
                               class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-1 outline-none transition
                               @error('sides_required') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        @error('sides_required')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Allowed Formats <span class="text-red-500">*</span></label>
                        <input type="text" name="allowed_formats" value="{{ old('allowed_formats', 'jpg,jpeg,png,pdf') }}" 
                               class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-1 outline-none transition
                               @error('allowed_formats') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        @error('allowed_formats')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Max Size (KB) <span class="text-red-500">*</span></label>
                        <input type="number" name="max_size_kb" value="{{ old('max_size_kb', 5120) }}" 
                               class="w-full border rounded-xl px-4 py-3 text-sm focus:ring-1 outline-none transition
                               @error('max_size_kb') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                        @error('max_size_kb')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select name="status" 
                                class="w-full md:w-1/3 border rounded-xl px-4 py-3 text-sm bg-white focus:ring-1 outline-none transition
                                @error('status') border-red-500 focus:ring-red-500 focus:border-red-500 @else border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="text-xs text-red-500 mt-1.5 font-medium"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="px-7 py-3 bg-indigo-600 hover:bg-indigo-700 transition text-white rounded-xl font-bold shadow-md shadow-indigo-200">
                    <i class="fas fa-save mr-1.5"></i> Save Document Rule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection