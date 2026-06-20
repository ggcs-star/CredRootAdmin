@extends('admin.layouts.app')

@section('title', 'Add Bank / Lender')
@section('page-title', 'Add New Lender')

@section('content')

<div class="max-w-5xl mx-auto px-3 sm:px-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs sm:text-sm text-slate-500 overflow-x-auto whitespace-nowrap pb-4">
        <a href="{{ route('admin.dashboard.index') }}" class="hover:text-blue-600 transition flex-shrink-0">
            <i class="fas fa-home"></i>
        </a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <a href="{{ route('admin.banks.index') }}" class="hover:text-blue-600 transition flex-shrink-0">Lenders</a>
        <span class="text-slate-300 flex-shrink-0">/</span>
        <span class="text-slate-700 font-medium truncate">Add New Lender</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-lg shadow-blue-500/5 border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="px-5 sm:px-8 py-5 sm:py-6 border-b border-slate-200 bg-gradient-to-r from-blue-50 via-indigo-50/50 to-blue-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-3">
                        <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                            <i class="fas fa-university text-base sm:text-lg"></i>
                        </span>
                        <span>Add New Bank</span>
                    </h2>
                    <p class="text-sm text-slate-500 mt-1 ml-0 sm:ml-14">
                        <i class="fas fa-info-circle text-blue-400 mr-1"></i>
                        Register a new lender bank in the system
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

        <form action="{{ route('admin.banks.store') }}"
              method="POST"
              enctype="multipart/form-data"
              autocomplete="off">

            @csrf

            {{-- Form Body --}}
            <div class="p-5 sm:p-8">

                {{-- Bank Logo Upload - Full Width --}}
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        <i class="fas fa-image text-blue-500 mr-2"></i> Bank Logo
                    </label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-xl p-6 sm:p-8 text-center hover:border-blue-400 transition-all group @error('logo') border-red-500 @enderror">
                        <input type="file"
                               name="logo"
                               id="logo"
                               class="absolute inset-0 opacity-0 cursor-pointer z-10"
                               accept="image/*"
                               onchange="previewLogo(event)">
                        <div class="flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-50 transition-all group-hover:scale-105">
                                <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 group-hover:text-blue-500 transition-all"></i>
                            </div>
                            <p class="mt-3 font-medium text-slate-700">Click to upload logo</p>
                            <p class="text-sm text-slate-400 mt-0.5">PNG, JPG, SVG (Max 2MB)</p>
                            <div id="logoPreview" class="mt-4 hidden">
                                <img id="logoPreviewImg" src="#" alt="Logo Preview" class="h-24 w-auto object-contain rounded-lg shadow-md">
                            </div>
                        </div>
                    </div>
                    @error('logo')
                        <span class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Two Column Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Left Column --}}
                    <div class="space-y-5">
                        {{-- Bank Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Bank Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-university text-slate-400 text-sm"></i>
                                </div>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('name') border-red-500 ring-2 ring-red-500/20 @enderror"
                                       placeholder="Enter bank name (e.g., HDFC Bank)"
                                       required>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Bank Code --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Bank Code
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-tag text-slate-400 text-sm"></i>
                                </div>
                                <input type="text"
                                       name="code"
                                       value="{{ old('code') }}"
                                       class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('code') border-red-500 ring-2 ring-red-500/20 @enderror"
                                       placeholder="Enter bank code (e.g., HDFC)"
                                       maxlength="10">
                            </div>
                            @error('code')
                                <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Contact Person --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                <i class="fas fa-user text-blue-500 mr-1.5"></i> Contact Person
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-user-circle text-slate-400 text-sm"></i>
                                </div>
                                <input type="text"
                                       name="contact_person"
                                       value="{{ old('contact_person') }}"
                                       class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('contact_person') border-red-500 ring-2 ring-red-500/20 @enderror"
                                       placeholder="Full name of contact person">
                            </div>
                            @error('contact_person')
                                <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-5">
                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                <i class="fas fa-envelope text-blue-500 mr-1.5"></i> Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-at text-slate-400 text-sm"></i>
                                </div>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('email') border-red-500 ring-2 ring-red-500/20 @enderror"
                                       placeholder="contact@bank.com">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                <i class="fas fa-phone text-blue-500 mr-1.5"></i> Phone Number
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-phone-alt text-slate-400 text-sm"></i>
                                </div>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('phone') border-red-500 ring-2 ring-red-500/20 @enderror"
                                       placeholder="+91 98765 43210">
                            </div>
                            @error('phone')
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
                                        class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all appearance-none bg-white">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>🟢 Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>🔴 Inactive</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-slate-400 text-sm"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Full Width: Loan Details Section --}}
                    <div class="md:col-span-2">
                        <div class="border-t border-slate-200 pt-6 mt-2">
                            <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2.5 mb-5">
                                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white text-xs shadow-lg shadow-green-500/20">
                                    <i class="fas fa-handshake"></i>
                                </span>
                                Loan Details
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                {{-- Min Loan --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        <i class="fas fa-arrow-down text-green-500 mr-1"></i> Min Amount
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <span class="text-slate-400 font-medium text-sm">₹</span>
                                        </div>
                                        <input type="number"
                                               name="min_loan_amount"
                                               value="{{ old('min_loan_amount') }}"
                                               class="w-full border border-slate-300 rounded-xl pl-8 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('min_loan_amount') border-red-500 ring-2 ring-red-500/20 @enderror"
                                               placeholder="1,00,000"
                                               step="1000">
                                    </div>
                                    @error('min_loan_amount')
                                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Max Loan --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        <i class="fas fa-arrow-up text-green-500 mr-1"></i> Max Amount
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                            <span class="text-slate-400 font-medium text-sm">₹</span>
                                        </div>
                                        <input type="number"
                                               name="max_loan_amount"
                                               value="{{ old('max_loan_amount') }}"
                                               class="w-full border border-slate-300 rounded-xl pl-8 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('max_loan_amount') border-red-500 ring-2 ring-red-500/20 @enderror"
                                               placeholder="50,00,000"
                                               step="1000">
                                    </div>
                                    @error('max_loan_amount')
                                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Interest From --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        <i class="fas fa-percentage text-blue-500 mr-1"></i> Rate From
                                    </label>
                                    <input type="number"
                                           step="0.01"
                                           name="interest_rate_from"
                                           value="{{ old('interest_rate_from') }}"
                                           class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('interest_rate_from') border-red-500 ring-2 ring-red-500/20 @enderror"
                                           placeholder="8.50">
                                    @error('interest_rate_from')
                                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Interest To --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                        <i class="fas fa-percentage text-blue-500 mr-1"></i> Rate To
                                    </label>
                                    <input type="number"
                                           step="0.01"
                                           name="interest_rate_to"
                                           value="{{ old('interest_rate_to') }}"
                                           class="w-full border border-slate-300 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('interest_rate_to') border-red-500 ring-2 ring-red-500/20 @enderror"
                                           placeholder="14.50">
                                    @error('interest_rate_to')
                                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Max Tenure - Full Width --}}
                            <div class="mt-4">
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                    <i class="fas fa-calendar-alt text-purple-500 mr-1"></i> Max Tenure
                                </label>
                                <div class="relative max-w-xs">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i class="fas fa-clock text-slate-400"></i>
                                    </div>
                                    <input type="number"
                                           name="max_tenure_months"
                                           value="{{ old('max_tenure_months') }}"
                                           class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('max_tenure_months') border-red-500 ring-2 ring-red-500/20 @enderror"
                                           placeholder="60 months">
                                    <span class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-sm text-slate-400 pointer-events-none">
                                        months
                                    </span>
                                </div>
                                @error('max_tenure_months')
                                    <p class="text-red-500 text-sm mt-1.5 flex items-center gap-1.5">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Form Actions --}}
            <div class="border-t border-slate-200 px-5 sm:px-8 py-4 sm:py-5 bg-slate-50/80">
                <div class="flex flex-col-reverse sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div class="text-xs text-slate-400 text-center sm:text-left">
                        <i class="fas fa-shield-alt text-blue-400 mr-1.5"></i> 
                        All information is encrypted and secure
                    </div>
                    <div class="flex flex-wrap gap-2.5 justify-center sm:justify-end">
                        <a href="{{ route('admin.banks.index') }}"
                           class="flex-1 sm:flex-none px-5 py-2.5 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all font-medium text-sm text-center">
                            <i class="fas fa-times mr-1.5"></i> Cancel
                        </a>
                        <button type="reset"
                                class="flex-1 sm:flex-none px-5 py-2.5 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 hover:border-slate-400 transition-all font-medium text-sm text-center">
                            <i class="fas fa-undo mr-1.5"></i> Reset
                        </button>
                        <button type="submit"
                                class="flex-1 sm:flex-none px-7 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center justify-center gap-2 text-sm">
                            <i class="fas fa-save"></i>
                            <span>Save Bank</span>
                        </button>
                    </div>
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
                    <span><strong>Bank Name</strong> is required and must be unique in the system</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-400 mt-0.5">•</span>
                    <span><strong>Loan Amount</strong> range (Min/Max) defines the bank's lending capacity</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-400 mt-0.5">•</span>
                    <span><strong>Interest Rate</strong> range helps borrowers understand the expected rates</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-blue-400 mt-0.5">•</span>
                    <span>Upload a <strong>Logo</strong> for better brand recognition and professional appearance</span>
                </li>
            </ul>
        </div>
    </div>

</div>

<script>
    // Logo preview functionality
    function previewLogo(event) {
        const input = event.target;
        const previewDiv = document.getElementById('logoPreview');
        const previewImg = document.getElementById('logoPreviewImg');

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

    // Auto-format phone number
    document.querySelector('input[name="phone"]')?.addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 0) {
            if (value.length <= 3) {
                this.value = value;
            } else if (value.length <= 6) {
                this.value = value.slice(0, 3) + ' ' + value.slice(3);
            } else if (value.length <= 10) {
                this.value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10);
            } else {
                this.value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10) + ' ' + value.slice(10, 12);
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

    // Touch device optimizations
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input, select, button, a').forEach(el => {
            if (el.tagName !== 'A' || el.href) {
                el.addEventListener('touchstart', function() {
                    // Haptic feedback placeholder
                }, { passive: true });
            }
        });
    });
</script>

<style>
    /* Extra small screens */
    @media (max-width: 480px) {
        .xs\:inline { display: inline !important; }
        .xs\:hidden { display: none !important; }
        .xs\:flex { display: flex !important; }
    }

    /* Hide number input arrows */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
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
</style>

@endsection