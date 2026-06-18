@extends('admin.layouts.app')

@section('title', 'Edit Lead Status')
@section('page-title', 'Edit Lead Status')
@section('page-subtitle', 'Update lead pipeline status')

@section('content')
<div class="max-w-4xl mx-auto">
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
        <h2 class="text-2xl font-bold text-white">Edit Lead Status</h2>
        <p class="text-blue-100 text-sm mt-1">Update lead workflow status settings</p>
    </div>

    <form action="{{ route('lead-statuses.update', $leadStatus->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status Name</label>
                <input type="text" name="name" value="{{ old('name', $leadStatus->name) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition">
                @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Internal Code</label>
                <input type="text" name="internal_code" value="{{ old('internal_code', $leadStatus->internal_code) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition uppercase"
                       {{ $leadStatus->is_system_locked ? 'readonly title="Locked statuses cannot change their internal code"' : '' }}>
                @if($leadStatus->is_system_locked)
                    <p class="text-xs text-amber-600 mt-1">System locked statuses cannot have their code changed.</p>
                @endif
                @error('internal_code')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status Color</label>
                <input type="color" name="color" value="{{ old('color', $leadStatus->color) }}" class="w-24 h-14 border border-slate-300 rounded-xl cursor-pointer">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $leadStatus->sort_order) }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                    <input type="checkbox" name="is_system_locked" value="1" class="w-5 h-5 text-blue-600 rounded border-slate-300 focus:ring-blue-500" 
                           {{ old('is_system_locked', $leadStatus->is_system_locked) ? 'checked' : '' }}>
                    <div class="ml-3">
                        <span class="block text-sm font-semibold text-slate-700">Lock this status (System Core)</span>
                        <span class="block text-xs text-slate-500 mt-0.5">Check this if the backend logic depends on this specific status. It prevents deletion.</span>
                    </div>
                </label>
            </div>

        </div>

        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-slate-200">
            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md transition">Update Status</button>
            <a href="{{ route('lead-statuses.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition">Cancel</a>
        </div>
    </form>
</div>
</div>
@endsection