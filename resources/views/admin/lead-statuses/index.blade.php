@extends('admin.layouts.app')

@section('title', 'Lead Statuses')
@section('page-title', 'Lead Statuses')
@section('page-subtitle', 'Manage lead workflow statuses')

@section('content')
<div class="max-w-7xl mx-auto">

@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Lead Statuses</h2>
            <p class="text-sm text-slate-500">Manage all lead pipeline stages</p>
        </div>
        <a href="{{ route('lead-statuses.create') }}" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md transition">
            + Add Lead Status
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">#</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Status Details</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Color</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">Sort Order</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-slate-700">System Lock</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-slate-700">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($statuses as $status)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-600">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-800">{{ $status->name }}</div>
                        <div class="text-xs text-slate-500 font-mono mt-1">{{ $status->internal_code }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="w-5 h-5 rounded-full border border-slate-200" style="background-color: {{ $status->color }}"></span>
                            <span class="text-sm text-slate-600">{{ $status->color }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">{{ $status->sort_order }}</td>
                    <td class="px-6 py-4">
                        @if($status->is_system_locked)
                            <span class="inline-flex items-center px-2 py-1 rounded bg-slate-100 text-slate-600 text-xs font-semibold border border-slate-200">
                                🔒 Locked
                            </span>
                        @else
                            <span class="text-slate-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('lead-statuses.edit', $status->id) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition">Edit</a>
                            
                            @if(!$status->is_system_locked)
                                <form action="{{ route('lead-statuses.destroy', $status->id) }}" method="POST" onsubmit="return confirm('Delete this status?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition">Delete</button>
                                </form>
                            @else
                                <button type="button" class="px-4 py-2 bg-slate-300 text-slate-500 cursor-not-allowed rounded-lg text-sm font-medium" title="System statuses cannot be deleted">Locked</button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-10 text-slate-500">No Lead Status Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-200">{{ $statuses->links() }}</div>
</div>
</div>
@endsection