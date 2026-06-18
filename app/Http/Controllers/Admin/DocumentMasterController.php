<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentMaster;
use App\Models\LoanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentMasterController extends Controller
{
    public function index()
    {
        $documents = DocumentMaster::latest()->paginate(10);
        return view('admin.document-masters.index', compact('documents'));
    }

    public function create()
    {
        $loanTypes = LoanType::where('status', 1)->get();
        $entityTypes = config('msme.entity_types');

        return view('admin.document-masters.create', compact('loanTypes', 'entityTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_code' => 'required|string|max:255|unique:document_masters,document_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'applicable_entities' => 'nullable|array',
            'applicable_loan_types' => 'nullable|array',
            'sides_required' => 'required|integer|min:0',
            'allowed_formats' => 'required|string|max:255',
            'max_size_kb' => 'required|integer|min:1',
            'sample_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'collection_stage' => 'required|in:pre_qualification,final_application',
            'is_mandatory' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        $data = $validated;

        $data['applicable_entities'] = $request->applicable_entities ?: null;
        $data['applicable_loan_types'] = $request->applicable_loan_types ?: null;

        if ($request->hasFile('sample_image')) {
            $data['sample_image_url'] = $request->file('sample_image')->store('document_samples', 'public');
        }

        DocumentMaster::create($data);

        return redirect()->route('document-masters.index')
            ->with('success', 'Document Master created successfully.');
    }



    public function edit(DocumentMaster $documentMaster)
    {
        $loanTypes = LoanType::where('status', 1)->get();
        $entityTypes = config('msme.entity_types');

        return view('admin.document-masters.edit', compact('documentMaster', 'loanTypes', 'entityTypes'));
    }

    public function update(Request $request, DocumentMaster $documentMaster)
    {
        $validated = $request->validate([
            'document_code' => 'required|string|max:255|unique:document_masters,document_code,' . $documentMaster->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'applicable_entities' => 'nullable|array',
            'applicable_loan_types' => 'nullable|array',
            'sides_required' => 'required|integer|min:0',
            'allowed_formats' => 'required|string|max:255',
            'max_size_kb' => 'required|integer|min:1',
            'sample_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'collection_stage' => 'required|in:pre_qualification,final_application',
            'is_mandatory' => 'required|boolean',
            'status' => 'required|boolean',
        ]);

        $data = $validated;

        $data['applicable_entities'] = $request->applicable_entities ?: null;
        $data['applicable_loan_types'] = $request->applicable_loan_types ?: null;

        if ($request->hasFile('sample_image')) {
            if ($documentMaster->sample_image_url) {
                Storage::disk('public')->delete($documentMaster->sample_image_url);
            }
            $data['sample_image_url'] = $request->file('sample_image')->store('document_samples', 'public');
        }

        $documentMaster->update($data);

        return redirect()->route('document-masters.index')
            ->with('success', 'Document Master updated successfully.');
    }

    public function destroy(DocumentMaster $documentMaster)
    {
        if ($documentMaster->sample_image_url) {
            Storage::disk('public')->delete($documentMaster->sample_image_url);
        }

        $documentMaster->delete();

        return redirect()->route('document-masters.index')
            ->with('success', 'Document Master deleted successfully.');
    }
}