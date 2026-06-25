<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use App\Models\Document;
use App\Models\DocumentMaster;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::id();

        $companies = Company::with('members')
            ->where('user_id', $user_id)
            ->latest()
            ->get();

        $allMasterDocs = DocumentMaster::where('status', 1)
            ->where('document_level', 'company')
            ->get();

        $allUploadedDocs = Document::where('user_id', $user_id)
            ->whereNotNull('company_id')
            ->whereNull('lead_id')
            ->get();

        $companiesData = $companies->map(function ($company) use ($allMasterDocs, $allUploadedDocs) {
            $companyMasterDocs = $allMasterDocs->filter(function ($doc) use ($company) {
                if (empty($doc->applicable_entities))
                    return true;
                return in_array($company->entity_type, $doc->applicable_entities);
            });

            $companyUploads = $allUploadedDocs->where('company_id', $company->id)->groupBy('document_master_id');

            $docStatus = $this->evaluateDocuments($companyMasterDocs, $companyUploads);

            $companyData = $company->toArray();

            $pendingCount = $docStatus['pending_mandatory_count'];

            $companyData['kyc_status'] = [
                'is_complete' => $pendingCount === 0,
                'pending_mandatory_count' => $pendingCount,
                'message' => $pendingCount === 0
                    ? 'All mandatory documents uploaded.'
                    : "{$pendingCount} mandatory document(s) pending for upload."
            ];

            return $companyData;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Companies fetched successfully.',
            'data' => $companiesData
        ], 200);
    }

    public function show($id)
    {
        $user_id = Auth::id();

        $company = Company::with('members')
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->first();

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Company not found or unauthorized access.'
            ], 404);
        }

        $companyMasterDocs = DocumentMaster::where('status', 1)
            ->where('document_level', 'company')
            ->get()
            ->filter(function ($doc) use ($company) {
                if (empty($doc->applicable_entities))
                    return true;
                return in_array($company->entity_type, $doc->applicable_entities);
            });

        $companyUploads = Document::where('company_id', $company->id)
            ->whereNull('lead_id')
            ->get()
            ->groupBy('document_master_id');

        $docStatus = $this->evaluateDocuments($companyMasterDocs, $companyUploads);

        $companyData = $company->toArray();
        $companyData['is_kyc_complete'] = $docStatus['pending_mandatory_count'] === 0;
        $companyData['company_documents'] = $docStatus;

        return response()->json([
            'status' => 'success',
            'data' => $companyData
        ], 200);
    }
    private function evaluateDocuments($masterDocs, $uploadedDocsGrouped)
    {
        $pending = [];
        $completed = [];
        $mandatoryPendingCount = 0;

        foreach ($masterDocs as $doc) {
            $docData = [
                'id' => $doc->id,
                'document_code' => $doc->document_code,
                'name' => $doc->name,
                'description' => $doc->description,
                'is_mandatory' => $doc->is_mandatory,
                'sides_required' => $doc->sides_required,
                'allowed_formats' => $doc->allowed_formats,
                'max_size_kb' => $doc->max_size_kb,
                'sample_image_url' => $doc->sample_image_url ? asset('storage/' . $doc->sample_image_url) : null,
            ];

            $uploadsForThisDoc = $uploadedDocsGrouped->get($doc->id, collect());
            $uploadedSides = $uploadsForThisDoc->pluck('document_side')->toArray();

            $isComplete = false;
            if ($doc->sides_required == 0 && (in_array('single', $uploadedSides) || in_array('front', $uploadedSides))) {
                $isComplete = true;
            } elseif ($doc->sides_required == 1 && in_array('front', $uploadedSides)) {
                $isComplete = true;
            } elseif ($doc->sides_required == 2 && in_array('front', $uploadedSides) && in_array('back', $uploadedSides)) {
                $isComplete = true;
            }

            $docData['uploaded_sides'] = $uploadedSides;
            $docData['uploaded_files'] = $uploadsForThisDoc->map(function ($f) {
                return [
                    'id' => $f->id,
                    'side' => $f->document_side,
                    'url' => asset('storage/' . $f->file_path),
                    'status' => $f->verification_status
                ];
            });

            if ($isComplete) {
                $completed[] = $docData;
            } else {
                $pending[] = $docData;
                if ($doc->is_mandatory) {
                    $mandatoryPendingCount++;
                }
            }
        }

        return [
            'pending_mandatory_count' => $mandatoryPendingCount,
            'is_all_mandatory_completed' => ($mandatoryPendingCount === 0),
            'pending_documents' => array_values($pending),
            'completed_documents' => array_values($completed),
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = $this->validateCompanyData($request);

        try {
            DB::beginTransaction();

            $companyData = Arr::except($validatedData, ['members']);
            $companyData['user_id'] = $user->id;

            $company = Company::create($companyData);

            if (isset($validatedData['members']) && is_array($validatedData['members'])) {
                $this->insertMembers($company->id, $validatedData['members']);
            }

            if ($user->current_step < 3) {
                $user->update(['current_step' => 3]);
            }

            DB::commit();
            $company->load('members');

            return response()->json([
                'status' => 'success',
                'message' => 'Company created successfully.',
                'data' => [
                    'company' => $company,
                    'current_step' => $user->current_step
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while creating company.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user_id = Auth::id();

        $company = Company::where('id', $id)->where('user_id', $user_id)->first();

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'Company not found or unauthorized.'], 404);
        }

        $validatedData = $this->validateCompanyData($request);

        try {
            DB::beginTransaction();

            $companyData = Arr::except($validatedData, ['members']);

            $company->update($companyData);

            if (isset($validatedData['members']) && is_array($validatedData['members'])) {
                $company->members()->delete();
                $this->insertMembers($company->id, $validatedData['members']);
            }

            DB::commit();
            $company->load('members');

            return response()->json([
                'status' => 'success',
                'message' => 'Company updated successfully.',
                'data' => [
                    'company' => $company
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while updating company.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user_id = Auth::id();

        $company = Company::where('id', $id)->where('user_id', $user_id)->first();

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'Company not found or unauthorized.'], 404);
        }

        try {
            DB::beginTransaction();

            $company->members()->delete();
            $company->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Company deleted successfully.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete company.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function validateCompanyData(Request $request)
    {
        return $request->validate([
            'company_name' => 'required|string|max:255',
            'entity_type' => 'required|string|in:Proprietorship,Partnership,LLP,Pvt Ltd',
            'industry_type' => 'nullable|string|in:Trading,Manufacturing,Service',
            'cin_number' => 'nullable|string|max:21',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'gst_number' => 'nullable|string|max:15',
            'pan_number' => 'required|string|size:10',
            'udyam_registration_number' => 'nullable|string|max:50',
            'date_of_incorporation' => 'nullable|date',
            'monthly_revenue' => 'required|numeric|min:0',
            'turnover' => 'nullable|numeric|min:0',
            'annual_income' => 'nullable|numeric|min:0',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',

            'members' => 'nullable|array',
            'members.*.name' => 'required_with:members|string|max:255',
            'members.*.designation' => 'required_with:members|string|max:100',
            'members.*.pan_number' => 'required_with:members|string|size:10',
            'members.*.aadhaar_number' => 'nullable|string|size:12',
            'members.*.mobile' => 'required_with:members|string|max:15',
            'members.*.email' => 'nullable|email|max:255',
            'members.*.dob' => 'nullable|date',
            'members.*.din_number' => 'nullable|string|max:8',
            'members.*.residential_address' => 'nullable|string',
            'members.*.is_authorized_signatory' => 'nullable|boolean',
            'members.*.cibil_score' => 'nullable|integer|min:300|max:900',
            'members.*.ownership_percentage' => 'nullable|numeric|min:0|max:100',
        ]);
    }

    private function insertMembers($companyId, $membersArray)
    {
        $membersData = array_map(function ($member) use ($companyId) {
            $member['company_id'] = $companyId;
            $member['is_authorized_signatory'] = filter_var($member['is_authorized_signatory'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $member['created_at'] = now();
            $member['updated_at'] = now();
            return $member;
        }, $membersArray);

        CompanyMember::insert($membersData);
    }
}