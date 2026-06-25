<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentMaster;
use App\Models\LoanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentMasterController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentMaster::latest();

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('document_code', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('mandatory') && $request->mandatory != '') {
            $query->where('is_mandatory', $request->mandatory);
        }
        if ($request->has('stage') && $request->stage != '') {
            $query->where('collection_stage', $request->stage);
        }
        if ($request->has('level') && $request->level != '') {
            $query->where('document_level', $request->level);
        }

        $documents = $query->paginate(10);
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
    $rules = [
        'document_code' => 'required|string|max:255|unique:document_masters,document_code',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'document_level' => 'required|in:user,company,lead',
        'sides_required' => 'required|integer|min:1|max:2',
        'allowed_formats' => 'required|string|max:255',
        'max_size_kb' => 'required|integer|min:1',
        'sample_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'collection_stage' => 'required|in:pre_qualification,final_application',
        'is_mandatory' => 'required|boolean',
        'status' => 'required|boolean',
    ];

    if ($request->document_level === 'company') {
        $rules['applicable_entities'] = 'required|array|min:1';
    }

    if ($request->document_level === 'lead') {
        $rules['applicable_entities'] = 'nullable|array';
        $rules['applicable_loan_types'] = 'nullable|array';
    }

    $validated = $request->validate($rules);

    $data = $validated;

    if ($request->document_level === 'user') {
        $data['applicable_entities'] = null;
        $data['applicable_loan_types'] = null;
    } elseif ($request->document_level === 'company') {
        $data['applicable_entities'] = $request->input('applicable_entities');
        $data['applicable_loan_types'] = null;
    } else {
        $data['applicable_entities'] = $request->input('applicable_entities');
        $data['applicable_loan_types'] = $request->input('applicable_loan_types');
    }

    if ($request->hasFile('sample_image')) {
        $data['sample_image_url'] = $request->file('sample_image')
            ->store('document_samples', 'public');
    }

    DocumentMaster::create($data);

    return redirect()
        ->route('document-masters.index')
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
    $rules = [
        'document_code' => 'required|string|max:255|unique:document_masters,document_code,' . $documentMaster->id,
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'document_level' => 'required|in:user,company,lead',
        'sides_required' => 'required|integer|min:1|max:2',
        'allowed_formats' => 'required|string|max:255',
        'max_size_kb' => 'required|integer|min:1',
        'sample_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'collection_stage' => 'required|in:pre_qualification,final_application',
        'is_mandatory' => 'required|boolean',
        'status' => 'required|boolean',
    ];

    if ($request->document_level === 'company') {
        $rules['applicable_entities'] = 'required|array|min:1';
    }

    if ($request->document_level === 'lead') {
        $rules['applicable_entities'] = 'nullable|array';
        $rules['applicable_loan_types'] = 'nullable|array';
    }

    $validated = $request->validate($rules);

    $data = $validated;

    if ($request->document_level === 'user') {
        $data['applicable_entities'] = null;
        $data['applicable_loan_types'] = null;
    } elseif ($request->document_level === 'company') {
        $data['applicable_entities'] = $request->input('applicable_entities');
        $data['applicable_loan_types'] = null;
    } else {
        $data['applicable_entities'] = $request->input('applicable_entities');
        $data['applicable_loan_types'] = $request->input('applicable_loan_types');
    }

    if ($request->hasFile('sample_image')) {

        if ($documentMaster->sample_image_url) {
            Storage::disk('public')
                ->delete($documentMaster->sample_image_url);
        }

        $data['sample_image_url'] = $request->file('sample_image')
            ->store('document_samples', 'public');
    }

    $documentMaster->update($data);

    return redirect()
        ->route('document-masters.index')
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