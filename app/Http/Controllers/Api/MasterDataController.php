<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentMaster;
use App\Models\LoanType;
use App\Models\Bank;
use App\Models\Document;
use App\Models\Company;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterDataController extends Controller
{
    public function getEntityTypes()
    {
        return response()->json([
            'status' => 'success',
            'data' => config('msme.entity_types')
        ], 200);
    }

    public function getLoanTypes()
    {
        $loanTypes = LoanType::where('status', 1)->get(['id', 'slug', 'name', 'description', 'icon_path'])
            ->map(function ($loan) {
                $loan->icon_url = $loan->icon_path ? asset('storage/' . $loan->icon_path) : null;
                unset($loan->icon_path);
                return $loan;
            });

        return response()->json(['status' => 'success', 'data' => $loanTypes], 200);
    }

    public function getActiveBanks()
    {
        $banks = Bank::where('status', 1)->get(['id', 'name', 'code', 'logo', 'min_loan_amount', 'max_loan_amount', 'interest_rate_from', 'interest_rate_to'])
            ->map(function ($bank) {
                $bank->logo_url = $bank->logo ? asset('storage/' . $bank->logo) : null;
                unset($bank->logo);
                return $bank;
            });

        return response()->json(['status' => 'success', 'data' => $banks], 200);
    }

    public function getDashboardDocumentStatus()
    {
        $userId = Auth::id();

        $allMasterDocs = DocumentMaster::where('status', 1)->get();
        $allUploadedDocs = Document::where('user_id', $userId)->get();

        $companies = Company::where('user_id', $userId)->get();
        $leads = Lead::with('company')->where('user_id', $userId)->get();


        $userMasterDocs = $allMasterDocs->where('document_level', 'user');
        $userUploads = $allUploadedDocs->whereNull('company_id')->whereNull('lead_id')->groupBy('document_master_id');
        $personalKyc = $this->evaluateDocuments($userMasterDocs, $userUploads);


        $businessesKyc = [];
        foreach ($companies as $company) {
            $companyMasterDocs = $allMasterDocs->where('document_level', 'company')->filter(function ($doc) use ($company) {
                if (empty($doc->applicable_entities))
                    return true;
                return in_array($company->entity_type, $doc->applicable_entities);
            });

            $companyUploads = $allUploadedDocs->where('company_id', $company->id)->whereNull('lead_id')->groupBy('document_master_id');
            $status = $this->evaluateDocuments($companyMasterDocs, $companyUploads);

            $businessesKyc[] = [
                'company_id' => $company->id,
                'company_name' => $company->company_name,
                'entity_type' => $company->entity_type,
                'is_kyc_complete' => $status['pending_mandatory_count'] === 0,
                'pending_mandatory_count' => $status['pending_mandatory_count'],
                'document_status' => $status
            ];
        }

        $loansKyc = [];
        foreach ($leads as $lead) {
            $entityType = $lead->company->entity_type ?? null;
            $loanTypeId = $lead->loan_type_id;

            $leadMasterDocs = $allMasterDocs->where('document_level', 'lead')->filter(function ($doc) use ($entityType, $loanTypeId) {
                $entityMatch = empty($doc->applicable_entities) || in_array($entityType, $doc->applicable_entities);
                $loanMatch = empty($doc->applicable_loan_types) || in_array((string) $loanTypeId, $doc->applicable_loan_types) || in_array((int) $loanTypeId, $doc->applicable_loan_types);
                return $entityMatch && $loanMatch;
            });

            $leadUploads = $allUploadedDocs->where('lead_id', $lead->id)->groupBy('document_master_id');
            $status = $this->evaluateDocuments($leadMasterDocs, $leadUploads);

            $loansKyc[] = [
                'lead_id' => $lead->id,
                'lead_number' => $lead->lead_number,
                'loan_amount' => $lead->loan_amount,
                'company_id' => $lead->company->id ?? 'Unknown',
                'company_name' => $lead->company->company_name ?? 'Unknown',
                'is_ready_for_submission' => $status['pending_mandatory_count'] === 0,
                'pending_mandatory_count' => $status['pending_mandatory_count'],
                'document_status' => $status
            ];
        }


        $futureDocs = collect();
        if ($companies->isEmpty()) {
            $futureDocs = $allMasterDocs->whereIn('document_level', ['company', 'lead'])->map(function ($doc) {
                return [
                    'name' => $doc->name,
                    'level' => $doc->document_level,
                    'description' => $doc->description,
                    'message' => 'Will be required once you add a business or apply for a loan.'
                ];
            })->values();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'account_overview' => [
                    'personal_kyc_complete' => $personalKyc['pending_mandatory_count'] === 0,
                    'total_businesses' => count($businessesKyc),
                    'total_loans_in_progress' => count($loansKyc),
                ],
                'personal_profile' => $personalKyc,
                'business_profiles' => $businessesKyc,
                'loan_applications' => $loansKyc,
                'locked_future_requirements' => $futureDocs
            ]
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
}