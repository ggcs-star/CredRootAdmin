@extends('admin.layouts.app')

@section('title', 'Document Masters')
@section('page-title', 'Document Masters')
@section('page-subtitle', 'Manage required documents for each business entity')

@section('content')

<div class="max-w-7xl mx-auto px-3 sm:px-4">

    {{-- Stats Overview --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-5 sm:mb-6">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Total Documents</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $documents->total() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-file-alt text-indigo-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $documents->where('status', 1)->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Mandatory</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $documents->where('is_mandatory', 1)->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-asterisk text-amber-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Global Docs</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $documents->where('applicable_entities', '[]')->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fas fa-globe text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-700 flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
            <div>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.style.display='none'" class="ml-auto text-green-500 hover:text-green-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg shadow-indigo-500/5 border border-slate-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-5 sm:px-8 py-5 sm:py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-50 via-blue-50/50 to-indigo-50">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                        <i class="fas fa-file-alt text-base sm:text-lg"></i>
                    </span>
                    <span>Document Masters</span>
                </h2>
                <p class="text-sm text-slate-500 mt-1 ml-0 sm:ml-14">
                    <i class="fas fa-info-circle text-indigo-400 mr-1"></i>
                    Configure global and specific document rules
                </p>
            </div>
            <div class="flex gap-2.5">
                <button onclick="window.location.reload()" 
                        class="px-4 py-2.5 border border-slate-300 text-slate-600 rounded-xl hover:bg-slate-50 transition-all font-medium text-sm flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i>
                    <span class="hidden xs:inline">Refresh</span>
                </button>
                <a href="{{ route('document-masters.create') }}"
                   class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-indigo-500/30 hover:-translate-y-0.5 active:translate-y-0 active:scale-95 flex items-center gap-2 text-sm">
                    <i class="fas fa-plus"></i>
                    <span>Add Document</span>
                </a>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="px-5 sm:px-8 py-4 border-b border-slate-200 bg-slate-50/50">
            <form method="GET" action="{{ route('document-masters.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                    </div>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by document name or code..."
                           class="w-full border border-slate-300 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all">
                </div>
                <div class="flex flex-wrap gap-2">
                    <select name="level" class="border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all bg-white">
                        <option value="">All Levels</option>
                        <option value="user" {{ request('level') == 'user' ? 'selected' : '' }}>User Profile</option>
                        <option value="company" {{ request('level') == 'company' ? 'selected' : '' }}>Company</option>
                        <option value="lead" {{ request('level') == 'lead' ? 'selected' : '' }}>Loan (Lead)</option>
                    </select>
                    <select name="status" class="border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all bg-white">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <select name="mandatory" class="border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all bg-white">
                        <option value="">All Types</option>
                        <option value="1" {{ request('mandatory') == '1' ? 'selected' : '' }}>Mandatory</option>
                        <option value="0" {{ request('mandatory') == '0' ? 'selected' : '' }}>Optional</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-medium text-sm">
                        <i class="fas fa-filter mr-1.5"></i> Filter
                    </button>
                    <a href="{{ route('document-masters.index') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl hover:bg-slate-50 transition text-sm flex items-center">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr class="text-left">
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Code / Name</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden sm:table-cell">Level</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider hidden md:table-cell">Entities</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider">Stage</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider text-center">Mandatory</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider text-center">Status</th>
                        <th class="px-4 sm:px-6 py-3.5 font-semibold text-slate-600 text-xs uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $document)
                        <tr class="hover:bg-indigo-50/50 transition group">
                            <td class="px-4 sm:px-6 py-4">
                                <div>
                                    <p class="font-bold text-slate-800">{{ $document->name }}</p>
                                    <p class="text-xs text-slate-400 font-mono flex items-center gap-1 mt-0.5">
                                        <i class="fas fa-code text-[10px]"></i>
                                        {{ $document->document_code }}
                                    </p>
                                </div>
                            </td>

                            <td class="px-4 sm:px-6 py-4 hidden sm:table-cell">
                                @if($document->document_level == 'user')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-cyan-100 text-cyan-700 text-xs rounded-full font-medium">
                                        <i class="fas fa-user text-[10px]"></i> User Profile
                                    </span>
                                @elseif($document->document_level == 'company')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-fuchsia-100 text-fuchsia-700 text-xs rounded-full font-medium">
                                        <i class="fas fa-building text-[10px]"></i> Company
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-700 text-xs rounded-full font-medium">
                                        <i class="fas fa-file-invoice-dollar text-[10px]"></i> Loan/Lead
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 sm:px-6 py-4 hidden md:table-cell">
                                @if(empty($document->applicable_entities) || count($document->applicable_entities) == 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 text-xs rounded-full font-medium">
                                        Global (All)
                                    </span>
                                @else
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @foreach($document->applicable_entities as $entity)
                                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 border border-indigo-100 text-[10px] rounded-md font-medium">{{ $entity }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 sm:px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs rounded-full font-medium
                                    {{ $document->collection_stage == 'pre_qualification' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $document->collection_stage == 'pre_qualification' ? 'Pre Qual' : 'Final App' }}
                                </span>
                            </td>

                            <td class="px-4 sm:px-6 py-4 text-center">
                                @if($document->is_mandatory)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                        <i class="fas fa-check-circle text-[10px]"></i> Yes
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-500 text-xs rounded-full font-medium">
                                        No
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 sm:px-6 py-4 text-center">
                                @if($document->status)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 text-green-700 text-xs rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 sm:px-6 py-4">
                                <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                    <a href="{{ route('document-masters.edit', $document->id) }}"
                                       class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition font-medium text-xs flex items-center gap-1">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('document-masters.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this document?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium text-xs flex items-center gap-1">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16">
                                <p class="text-lg font-semibold text-slate-600">No documents found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer with Pagination --}}
        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50">
            {{ $documents->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection