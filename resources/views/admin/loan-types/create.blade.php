@extends('admin.layouts.app')

@section('title', 'Add Loan Type')
@section('page-title', 'Add Loan Type')
@section('page-subtitle', 'Create a new loan product')

@section('content')

<div class="max-w-5xl mx-auto px-3 sm:px-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs sm:text-sm text-slate-500 overflow-x-auto whitespace-nowrap pb-4">
        <a href="{{ route('admin.dashboard.index') }}" class="hover:text-blue-600 transition flex-shrink-0">
            <i class="fas fa-home"></i>
        </a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <a href="{{ route('loan-types.index') }}" class="hover:text-blue-600 transition flex-shrink-0">Loan Types</a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <span class="text-slate-700 font-medium truncate">Add New Loan Type</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-lg shadow-blue-500/5 border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 via-indigo-50/50 to-blue-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-3">
                        <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                            <i class="fas fa-tag text-base sm:text-lg"></i>
                        </span>
                        <span>Add Loan Type</span>
                    </h2>
                    <p class="text-sm text-slate-500 mt-1 ml-0 sm:ml-14">
                        <i class="fas fa-info-circle text-blue-400 mr-1"></i>
                        Create and manage loan products for your platform
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

        <form action="{{ route('loan-types.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="p-5 sm:p-8">

            @csrf

            <div class="grid grid-cols-1 gap-6">

                {{-- Loan Type Name --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Loan Type Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-tag text-slate-400 text-sm"></i>
                        </div>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               placeholder="e.g. Working Capital Loan, Business Loan, etc."
                               class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all @error('name') border-red-500 ring-2 ring-red-500/20 @enderror">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Icon Image Upload --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-image text-blue-500 mr-1.5"></i> Icon Image
                    </label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-blue-400 transition-all group @error('icon') border-red-500 @enderror">
                        <input type="file" 
                               name="icon" 
                               id="icon"
                               accept="image/png, image/jpeg, image/svg+xml"
                               class="absolute inset-0 opacity-0 cursor-pointer z-10"
                               onchange="previewIcon(event)">
                        <div class="flex flex-col items-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-50 transition-all group-hover:scale-105">
                                <i class="fas fa-cloud-upload-alt text-2xl text-slate-400 group-hover:text-blue-500 transition-all"></i>
                            </div>
                            <p class="mt-2 font-medium text-slate-700">Click to upload icon</p>
                            <p class="text-sm text-slate-400 mt-0.5">PNG, JPG, SVG (Max 2MB)</p>
                            <div id="iconPreview" class="mt-3 hidden">
                                <img id="iconPreviewImg" src="#" alt="Icon Preview" class="h-16 w-auto object-contain rounded-lg shadow-md">
                            </div>
                        </div>
                    </div>
                    @error('icon')
                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-align-left text-blue-500 mr-1.5"></i> Description
                    </label>
                    <div class="relative">
                        <textarea name="description" 
                                  rows="5" 
                                  placeholder="Enter detailed description of this loan type..."
                                  class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all @error('description') border-red-500 ring-2 ring-red-500/20 @enderror">{{ old('description') }}</textarea>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">
                        <i class="fas fa-info-circle mr-1"></i>
                        Describe the loan type, eligibility criteria, and key features
                    </p>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-toggle-on text-blue-500 mr-1.5"></i> Status
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-circle text-slate-400 text-[10px]"></i>
                        </div>
                        <select name="status" 
                                class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all appearance-none bg-white">
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>🟢 Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>🔴 Inactive</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-slate-400 text-sm"></i>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">
                        <i class="fas fa-info-circle mr-1"></i>
                        Active loan types will be visible to customers
                    </p>
                </div>

            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 mt-8 pt-6 border-t border-slate-200">
                <div class="text-xs text-slate-400 text-center sm:text-left">
                    <i class="fas fa-shield-alt text-blue-400 mr-1.5"></i> 
                    All information is secure and encrypted
                </div>
                <div class="flex flex-wrap gap-2.5 justify-center sm:justify-end">
                    <a href="{{ route('loan-types.index') }}" 
                       class="flex-1 sm:flex-none px-5 py-2.5 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all font-medium text-sm text-center">
                        <i class="fas fa-arrow-left mr-1.5"></i> Back
                    </a>
                    <button type="reset" 
                            class="flex-1 sm:flex-none px-5 py-2.5 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all font-medium text-sm text-center">
                        <i class="fas fa-undo mr-1.5"></i> Reset
                    </button>
                    <button type="submit" 
                            class="flex-1 sm:flex-none px-7 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-save"></i>
                        <span>Save Loan Type</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

    {{-- Quick Tips --}}
    <div class="mt-5 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 flex flex-col sm:flex-row items-start gap-3">
        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-lightbulb text-blue-600"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-blue-800">💡 Quick Tips</p>
            <ul class="text-sm text-blue-700 space-y-0.5 mt-1">
                <li class="flex items-start gap-2">
                    <span class="text-blue-400 mt-0.5">•</span>
                    <span><strong>Loan Type Name</strong> should be descriptive and unique</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-400 mt-0.5">•</span>
                    <span>Upload an <strong>Icon</strong> for better visual representation</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-400 mt-0.5">•</span>
                    <span><strong>Description</strong> helps customers understand the loan product</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-400 mt-0.5">•</span>
                    <span>Set <strong>Active</strong> status to make the loan type available</span>
                </li>
            </ul>
        </div>
    </div>

</div>

<script>
    // Icon preview functionality
    function previewIcon(event) {
        const input = event.target;
        const previewDiv = document.getElementById('iconPreview');
        const previewImg = document.getElementById('iconPreviewImg');

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

    // Character counter for description
    document.querySelector('textarea[name="description"]')?.addEventListener('input', function() {
        const maxLength = 500;
        const currentLength = this.value.length;
        const counter = this.parentElement.querySelector('.char-counter');
        if (counter) {
            counter.textContent = `${currentLength}/${maxLength}`;
            if (currentLength > maxLength) {
                counter.classList.add('text-red-500');
                counter.classList.remove('text-slate-400');
            } else {
                counter.classList.remove('text-red-500');
                counter.classList.add('text-slate-400');
            }
        }
    });
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
    }

    /* Safe area support */
    @supports (padding: max(0px)) {
        .px-3 { 
            padding-left: max(0.75rem, env(safe-area-inset-left)); 
            padding-right: max(0.75rem, env(safe-area-inset-right)); 
        }
    }

    /* Textarea resize */
    textarea {
        resize: vertical;
        min-height: 120px;
    }
</style>

@endsection