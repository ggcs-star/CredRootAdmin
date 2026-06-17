<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentMaster;
use Illuminate\Http\Request;

class DocumentMasterController extends Controller
{
    public function index()
    {
        $documents = DocumentMaster::latest()->paginate(10);

        return view('admin.document-masters.index', compact('documents'));
    }

    public function create()
    {
        return view('admin.document-masters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'entity_type'      => 'required|string|max:255',
            'collection_stage' => 'required|in:pre_qualification,final_application',
            'is_mandatory'     => 'required|boolean',
            'status'           => 'required|boolean',
        ]);

        DocumentMaster::create($validated);

        return redirect()
            ->route('document-masters.index')
            ->with('success', 'Document Master created successfully.');
    }

    public function edit(DocumentMaster $documentMaster)
    {
        return view('admin.document-masters.edit', compact('documentMaster'));
    }

    public function update(Request $request, DocumentMaster $documentMaster)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'entity_type'      => 'required|string|max:255',
            'collection_stage' => 'required|in:pre_qualification,final_application',
            'is_mandatory'     => 'required|boolean',
            'status'           => 'required|boolean',
        ]);

        $documentMaster->update($validated);

        return redirect()
            ->route('document-masters.index')
            ->with('success', 'Document Master updated successfully.');
    }

    public function destroy(DocumentMaster $documentMaster)
    {
        $documentMaster->delete();

        return redirect()
            ->route('document-masters.index')
            ->with('success', 'Document Master deleted successfully.');
    }
}

