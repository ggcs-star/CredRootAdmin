@extends('admin.layouts.app')

@section('title', 'Document Masters')
@section('page-title', 'Document Masters')
@section('page-subtitle', 'Manage required documents for each business entity')

@section('content')
<div class="max-w-7xl mx-auto">

@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Document Masters</h2>
            <p class="text-sm text-slate-500">Configure global and specific document rules</p>
        </div>
        <a href="{{ route('document-masters.create') }}" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md transition">
            + Add Document
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Code / Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Applicable Entities</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Stage</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Sides</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Mandatory</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Action</th>
                </tr>
            </thead>
            <tbody>
            @forelse($documents as $document)
                <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <div class="font-bold text-slate-800">{{ $document->name }}</div>
                        <div class="text-xs text-slate-500 font-mono">{{ $document->document_code }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if(empty($document->applicable_entities))
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-xs rounded border border-slate-200">Global (All)</span>
                        @else
                            <div class="flex flex-wrap gap-1">
                                @foreach($document->applicable_entities as $entity)
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 text-xs rounded">{{ $entity }}</span>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-600">
                        {{ $document->collection_stage == 'pre_qualification' ? 'Pre Qual' : 'Final App' }}
                    </td>
                    <td class="px-4 py-3 text-center text-sm font-medium text-slate-700">
                        {{ $document->sides_required }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($document->is_mandatory)
                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">Yes</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs bg-slate-100 text-slate-600">No</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('document-masters.edit', $document->id) }}" class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-md text-sm">Edit</a>
                            <form action="{{ route('document-masters.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Delete this document?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-slate-500">No Documents Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-200">{{ $documents->links() }}</div>
</div>
</div>
@endsection