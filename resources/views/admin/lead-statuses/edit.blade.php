@extends('admin.layouts.app')

@section('title', 'Edit Lead Status')
@section('page-title', 'Edit Lead Status')
@section('page-subtitle', 'Update lead pipeline status')

@section('content')

<div class="max-w-4xl mx-auto px-3 sm:px-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs sm:text-sm text-slate-500 overflow-x-auto whitespace-nowrap pb-4">
        <a href="{{ route('admin.dashboard.index') }}" class="hover:text-indigo-600 transition flex-shrink-0">
            <i class="fas fa-home"></i>
        </a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <a href="{{ route('lead-statuses.index') }}" class="hover:text-indigo-600 transition flex-shrink-0">Lead Statuses</a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <span class="text-slate-700 font-medium truncate">Edit: {{ $leadStatus->name }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-lg shadow-indigo-500/5 border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-50 via-blue-50/50 to-indigo-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-3">
                        <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-edit text-base sm:text-lg"></i>
                        </span>
                        <span>Edit Lead Status</span>
                    </h2>
                    <p class="text-sm text-slate-500 mt-1 ml-0 sm:ml-14">
                        <i class="fas fa-info-circle text-indigo-400 mr-1"></i>
                        Update lead workflow status settings
                    </p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    @if($leadStatus->is_system_locked)
                        <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-lg flex items-center gap-1.5">
                            <i class="fas fa-lock text-amber-500"></i>
                            <span>System Locked</span>
                        </span>
                    @endif
                    <span class="bg-red-100 text-red-600 px-2.5 py-1 rounded-lg flex items-center gap-1.5">
                        <i class="fas fa-asterisk text-[8px]"></i> 
                        <span class="hidden xs:inline">Required fields</span>
                        <span class="xs:hidden">Required</span>
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('lead-statuses.update', $leadStatus->id) }}" 
              method="POST" 
              class="p-5 sm:p-8">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">

                {{-- Status Name --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Status Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-tag text-slate-400 text-sm"></i>
                        </div>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $leadStatus->name) }}" 
                               placeholder="e.g. Processing with Bank"
                               class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('name') border-red-500 ring-2 ring-red-500/20 @enderror">
                    </div>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Internal Code --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-code text-blue-500 mr-1.5"></i> Internal Code
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-hashtag text-slate-400 text-sm"></i>
                        </div>
                        <input type="text" 
                               name="internal_code" 
                               value="{{ old('internal_code', $leadStatus->internal_code) }}" 
                               placeholder="e.g. BANK_PROCESSING"
                               class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm uppercase focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('internal_code') border-red-500 ring-2 ring-red-500/20 @enderror"
                               {{ $leadStatus->is_system_locked ? 'readonly' : '' }}>
                    </div>
                    @if($leadStatus->is_system_locked)
                        <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-lock text-amber-500"></i>
                            System locked statuses cannot have their code changed.
                        </p>
                    @else
                        <p class="text-xs text-slate-400 mt-1.5">
                            <i class="fas fa-info-circle mr-1"></i>
                            Leave blank to auto-generate from name
                        </p>
                    @endif
                    @error('internal_code')
                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Status Color --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-palette text-purple-500 mr-1.5"></i> Status Color
                    </label>
                    <div class="flex items-center gap-4">
                        <input type="color" 
                               name="color" 
                               value="{{ old('color', $leadStatus->color ?? '#6366f1') }}" 
                               id="colorPicker"
                               class="w-16 h-14 sm:w-20 sm:h-16 border border-slate-300 rounded-xl cursor-pointer hover:scale-105 transition">
                        <div class="flex-1">
                            <div class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-600 font-mono" id="colorDisplay">
                                {{ old('color', $leadStatus->color ?? '#6366f1') }}
                            </div>
                            <p class="text-xs text-slate-400 mt-1.5">
                                <i class="fas fa-info-circle mr-1"></i>
                                Click to pick a color
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        <i class="fas fa-sort-numeric-down text-blue-500 mr-1.5"></i> Sort Order
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-arrow-up text-slate-400 text-sm"></i>
                        </div>
                        <input type="number" 
                               name="sort_order" 
                               value="{{ old('sort_order', $leadStatus->sort_order ?? 0) }}" 
                               placeholder="0"
                               class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all @error('sort_order') border-red-500 ring-2 ring-red-500/20 @enderror">
                    </div>
                    <p class="text-xs text-slate-400 mt-1.5">
                        <i class="fas fa-info-circle mr-1"></i>
                        Lower numbers appear first in the pipeline
                    </p>
                    @error('sort_order')
                        <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- System Lock --}}
                <div class="md:col-span-2">
                    <label class="flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all group
                        {{ $leadStatus->is_system_locked ? 'border-amber-300 bg-amber-50/50' : 'border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50' }}">
                        <input type="checkbox" 
                               name="is_system_locked" 
                               value="1" 
                               class="w-5 h-5 mt-0.5 rounded border-slate-300 focus:ring-indigo-500 focus:ring-2 transition
                                      {{ $leadStatus->is_system_locked ? 'text-amber-600 border-amber-400' : 'text-indigo-600' }}"
                               {{ old('is_system_locked', $leadStatus->is_system_locked) ? 'checked' : '' }}
                               {{ $leadStatus->is_system_locked ? 'disabled' : '' }}>
                        <div class="ml-3">
                            <span class="block text-sm font-semibold text-slate-700 group-hover:text-indigo-700 transition">
                                <i class="fas {{ $leadStatus->is_system_locked ? 'fa-lock text-amber-500' : 'fa-unlock text-amber-500' }} mr-1.5"></i>
                                Lock this status (System Core)
                            </span>
                            <span class="block text-xs text-slate-500 mt-0.5">
                                @if($leadStatus->is_system_locked)
                                    <span class="text-amber-600">This status is system locked and cannot be unlocked.</span>
                                @else
                                    Check this if the backend logic depends on this specific status. It prevents deletion.
                                @endif
                            </span>
                        </div>
                    </label>
                </div>

                {{-- Preview Section --}}
                <div class="md:col-span-2">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                        <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-eye text-indigo-500"></i>
                            Live Preview
                        </p>
                        <div class="flex flex-wrap items-center gap-3" id="previewContainer">
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium border-2 transition-all" 
                                  id="previewBadge"
                                  style="background-color: {{ old('color', $leadStatus->color ?? '#6366f1') }}33; 
                                         color: {{ old('color', $leadStatus->color ?? '#6366f1') }};
                                         border-color: {{ old('color', $leadStatus->color ?? '#6366f1') }};">
                                <span class="w-2.5 h-2.5 rounded-full" 
                                      id="previewDot"
                                      style="background-color: {{ old('color', $leadStatus->color ?? '#6366f1') }};"></span>
                                {{ $leadStatus->name }}
                            </span>
                            <span class="text-xs text-slate-400">
                                <i class="fas fa-arrow-right mr-1"></i>
                                This is how the status will appear
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Metadata --}}
                <div class="md:col-span-2 bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-plus text-indigo-400"></i>
                            <span>Created: <strong>{{ $leadStatus->created_at->format('d M Y, h:i A') }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-green-400"></i>
                            <span>Last Updated: <strong>{{ $leadStatus->updated_at->diffForHumans() }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-hashtag text-purple-400"></i>
                            <span>ID: <strong>#{{ $leadStatus->id }}</strong></span>
                        </div>
                        @if($leadStatus->is_system_locked)
                            <div class="flex items-center gap-2">
                                <i class="fas fa-lock text-amber-500"></i>
                                <span class="text-amber-600 font-medium">System Locked</span>
                            </div>
                        @endif
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
                    <a href="{{ route('lead-statuses.index') }}" 
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
                        <span>Update Status</span>
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
                    <span><strong>Status Name</strong> should be clear and descriptive for the pipeline</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-indigo-400 mt-0.5">•</span>
                    <span><strong>Color</strong> helps visually distinguish statuses in the pipeline</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-indigo-400 mt-0.5">•</span>
                    <span><strong>Sort Order</strong> defines the flow of your lead pipeline</span>
                </li>
                @if(!$leadStatus->is_system_locked)
                    <li class="flex items-start gap-2">
                        <span class="text-indigo-400 mt-0.5">•</span>
                        <span><strong>Lock</strong> critical statuses that your system depends on</span>
                    </li>
                @endif
            </ul>
        </div>
    </div>

</div>

<script>
    // Color picker live preview
    document.getElementById('colorPicker')?.addEventListener('input', function() {
        const color = this.value;
        
        // Update color display
        document.getElementById('colorDisplay').textContent = color;
        
        // Update preview badge
        const badge = document.getElementById('previewBadge');
        const dot = document.getElementById('previewDot');
        
        if (badge) {
            badge.style.backgroundColor = color + '33';
            badge.style.color = color;
            badge.style.borderColor = color;
        }
        if (dot) {
            dot.style.backgroundColor = color;
        }
    });

    // Reset confirmation
    document.querySelector('button[type="reset"]')?.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to reset all fields to their original values?')) {
            e.preventDefault();
        }
    });

    // Auto-generate internal code from name (only if not locked)
    @if(!$leadStatus->is_system_locked)
    document.querySelector('input[name="name"]')?.addEventListener('input', function() {
        const codeField = document.querySelector('input[name="internal_code"]');
        if (codeField && !codeField.value) {
            const name = this.value.trim();
            if (name) {
                const code = name.toUpperCase().replace(/[^A-Z0-9]/g, '_');
                codeField.placeholder = 'Auto: ' + code;
            }
        }
    });
    @endif

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

    /* Smooth transitions */
    .transition-all {
        transition: all 0.2s ease;
    }

    /* Color input styling */
    input[type="color"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding: 4px;
        cursor: pointer;
    }
    input[type="color"]::-webkit-color-swatch-wrapper {
        padding: 0;
    }
    input[type="color"]::-webkit-color-swatch {
        border: none;
        border-radius: 8px;
    }
    input[type="color"]::-moz-color-swatch {
        border: none;
        border-radius: 8px;
    }

    /* Touch device optimizations */
    @media (hover: none) {
        .hover\:shadow-lg:hover { box-shadow: none !important; }
        .hover\:-translate-y-0\.5:hover { transform: none !important; }
        .hover\:bg-slate-50:hover { background: inherit !important; }
        .hover\:bg-indigo-50\/50:hover { background: inherit !important; }
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
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        background: white;
        transition: all 0.2s ease;
        position: relative;
        flex-shrink: 0;
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
        font-size: 14px;
        font-weight: bold;
    }
    input[type="checkbox"]:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    input[type="checkbox"]:disabled:checked {
        background: #f59e0b;
        border-color: #f59e0b;
    }
    input[type="checkbox"]:focus {
        ring: 2px solid #6366f1;
    }

    /* Disabled input styling */
    input:disabled {
        cursor: not-allowed;
        opacity: 0.7;
        background-color: #f8fafc;
    }
</style>

@endsection