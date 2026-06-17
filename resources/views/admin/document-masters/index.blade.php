@extends('admin.layouts.app')

@section('content')

<div class="p-6">

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            Document Masters
        </h2>
        <p class="text-sm text-slate-500">
            Manage required documents for each business entity.
        </p>
    </div>

    <a href="{{ route('document-masters.create') }}"
       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
        Add Document
    </a>
</div>

@if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700 border border-green-200">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">#</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Document Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Entity Type</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Collection Stage</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold">Mandatory</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                    <th class="px-4 py-3 text-center text-sm font-semibold">Action</th>
                </tr>
            </thead>

            <tbody>

            @forelse($documents as $document)

                <tr class="border-b hover:bg-slate-50">

                    <td class="px-4 py-3">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-4 py-3 font-medium text-slate-700">
                        {{ $document->name }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $document->entity_type }}
                    </td>

                    <td class="px-4 py-3">
                        {{ ucfirst(str_replace('_',' ',$document->collection_stage)) }}
                    </td>

                    <td class="px-4 py-3 text-center">

                        @if($document->is_mandatory)
                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                Yes
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                No
                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3 text-center">

                        @if($document->status)
                            <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                Active
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                Inactive
                            </span>
                        @endif

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex items-center justify-center gap-2">

                            <a href="{{ route('document-masters.edit',$document->id) }}"
                               class="px-3 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-md text-sm">
                                Edit
                            </a>

                            <form action="{{ route('document-masters.destroy',$document->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        onclick="return confirm('Delete this document?')"
                                        class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center py-8 text-slate-500">
                        No Documents Found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>


</div>
@endsection
