<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBankAccount;
use App\Models\Lead;
use App\Models\LoanApplication;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Document;
use App\Models\DocumentMaster;
class LeadController extends Controller
{

   public function index(Request $request)
    {
        $user_id = Auth::id();

        $leads = Lead::with([
            'company:id,company_name,entity_type',
            'loanType:id,name',
            'status',
            'loanApplications.bank:id,name,logo',
            'bankAccount:id,bank_name,account_number'
        ])
            ->where('user_id', $user_id)
            ->latest()
            ->get();

        if ($leads->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No loan applications found.',
                'data' => []
            ], 200);
        }

        // Fetch Lead level document masters
        $allMasterDocs = DocumentMaster::where('status', 1)
            ->where('document_level', 'lead')
            ->get();

        // Fetch all uploaded documents for these leads
        $leadIds = $leads->pluck('id')->toArray();
        $allUploadedDocs = Document::whereIn('lead_id', $leadIds)->get();

        $leadsData = $leads->map(function ($lead) use ($allMasterDocs, $allUploadedDocs) {
            $entityType = $lead->company->entity_type ?? null;
            $loanTypeId = $lead->loan_type_id;

            // Filter master docs based on company entity type AND loan type
            $leadMasterDocs = $allMasterDocs->filter(function ($doc) use ($entityType, $loanTypeId) {
                $entityMatch = empty($doc->applicable_entities) || in_array($entityType, $doc->applicable_entities);
                $loanMatch = empty($doc->applicable_loan_types) || in_array((string) $loanTypeId, $doc->applicable_loan_types) || in_array((int) $loanTypeId, $doc->applicable_loan_types);
                return $entityMatch && $loanMatch;
            });

            // Get uploaded docs specific to this lead
            $leadUploads = $allUploadedDocs->where('lead_id', $lead->id)->groupBy('document_master_id');
            
            // Evaluate KYC status
            $docStatus = $this->evaluateDocuments($leadMasterDocs, $leadUploads);

            $leadData = $lead->toArray();
            $pendingCount = $docStatus['pending_mandatory_count'];
            
            $leadData['document_status'] = [
                'is_ready_for_submission' => $pendingCount === 0,
                'pending_mandatory_count' => $pendingCount,
                'message' => $pendingCount === 0 
                                ? 'All mandatory documents uploaded.' 
                                : "{$pendingCount} mandatory document(s) pending for upload."
            ];

            return $leadData;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Leads fetched successfully.',
            'data' => $leadsData
        ], 200);
    }


    public function show($id)
    {
        $user_id = Auth::id();

        $lead = Lead::with([
            'company',
            'loanType',
            'status',
            'loanApplications.bank',
            'bankAccount'
        ])
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->first();

        if (!$lead) {
            return response()->json([
                'status' => 'error',
                'message' => 'Loan application not found or unauthorized.'
            ], 404);
        }

        // Fetch User and Company documents for complete view (Optional, based on your previous code)
        $documents = Document::with('master:id,name,document_code,document_level')
            ->where('user_id', $user_id)
            ->where(function ($query) use ($lead) {
                $query->where(function ($q) {
                    $q->whereNull('company_id')->whereNull('lead_id');
                })->orWhere(function ($q) use ($lead) {
                    $q->where('company_id', $lead->company_id)->whereNull('lead_id');
                })->orWhere(function ($q) use ($lead) {
                    $q->where('lead_id', $lead->id);
                });
            })
            ->get();

        $formattedDocs = [
            'personal_documents' => [],
            'company_documents' => [],
            'lead_documents' => []
        ];

        foreach ($documents as $doc) {
            $docData = [
                'id' => $doc->id,
                'name' => $doc->master->name ?? 'Unknown Document',
                'code' => $doc->master->document_code ?? 'UNKNOWN',
                'side' => $doc->document_side,
                'url' => asset('storage/' . $doc->file_path),
                'verification_status' => $doc->verification_status,
                'reject_reason' => $doc->rejection_reason ?? null,
                'uploaded_at' => $doc->created_at->format('d M Y, h:i A')
            ];

            if (isset($doc->master)) {
                if ($doc->master->document_level === 'user') {
                    $formattedDocs['personal_documents'][] = $docData;
                } elseif ($doc->master->document_level === 'company') {
                    $formattedDocs['company_documents'][] = $docData;
                } else {
                    $formattedDocs['lead_documents'][] = $docData;
                }
            }
        }

        // Evaluate ONLY lead-specific missing documents for the detailed view
        $entityType = $lead->company->entity_type ?? null;
        $loanTypeId = $lead->loan_type_id;

        $leadMasterDocs = DocumentMaster::where('status', 1)
            ->where('document_level', 'lead')
            ->get()
            ->filter(function ($doc) use ($entityType, $loanTypeId) {
                $entityMatch = empty($doc->applicable_entities) || in_array($entityType, $doc->applicable_entities);
                $loanMatch = empty($doc->applicable_loan_types) || in_array((string) $loanTypeId, $doc->applicable_loan_types) || in_array((int) $loanTypeId, $doc->applicable_loan_types);
                return $entityMatch && $loanMatch;
            });

        $leadUploads = Document::where('lead_id', $lead->id)->get()->groupBy('document_master_id');
        $docStatus = $this->evaluateDocuments($leadMasterDocs, $leadUploads);

        $leadData = $lead->toArray();
        $leadData['document_status'] = $docStatus; // Show detailed pending/completed arrays
        $leadData['all_documents_uploaded'] = $formattedDocs; // Keep your old formatted view as well

        return response()->json([
            'status' => 'success',
            'message' => 'Lead details fetched successfully.',
            'data' => $leadData
        ], 200);
    }

    // ... (Keep store, update, destroy, validateLeadData, verifyOwnership methods unchanged) ...

    // Add Evaluate method at the bottom
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
            'is_ready_for_submission' => ($mandatoryPendingCount === 0),
            'pending_documents' => array_values($pending),
            'completed_documents' => array_values($completed),
        ];
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validatedData = $this->validateLeadData($request);

        if (!$this->verifyOwnership(Company::class, $validatedData['company_id'], $user_id)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid company selected.'], 403);
        }
        if (!$this->verifyOwnership(CompanyBankAccount::class, $validatedData['company_bank_account_id'], $user_id)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid bank account selected.'], 403);
        }

        $newStatusId = LeadStatus::where('internal_code', LeadStatus::STATUS_NEW)->value('id');

        if (!$newStatusId) {
            return response()->json(['status' => 'error', 'message' => 'System Configuration Error: Default status not found.'], 500);
        }

        try {
            DB::beginTransaction();

            $lead = Lead::create([
                'lead_number' => 'Ld-' . strtoupper(uniqid()),
                'user_id' => $user_id,
                'company_id' => $validatedData['company_id'],
                'company_bank_account_id' => $validatedData['company_bank_account_id'],
                'loan_amount' => $validatedData['loan_amount'],
                'loan_type_id' => $validatedData['loan_type_id'] ?? null,
                'status_id' => $newStatusId,
            ]);

            $application = LoanApplication::create([
                'lead_id' => $lead->id,
                'bank_id' => $validatedData['bank_id'],
                'status_id' => $newStatusId,
                'remarks' => 'User selected this bank directly from the portal.'
            ]);

            if ($user->current_step < 5) {
                $user->update(['current_step' => 5]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loan application initiated successfully!',
                'data' => [
                    'lead_id' => $lead->id,
                    'lead_number' => $lead->lead_number,
                    'current_step' => $user->current_step
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to apply for loan.', 'error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $user_id = Auth::id();

        $lead = Lead::with(['status', 'loanApplications'])->where('id', $id)->where('user_id', $user_id)->first();

        if (!$lead) {
            return response()->json(['status' => 'error', 'message' => 'Lead not found or unauthorized.'], 404);
        }

        if ($lead->status && $lead->status->internal_code !== LeadStatus::STATUS_NEW) {
            return response()->json(['status' => 'error', 'message' => 'Cannot edit a loan application that is already under review or processed.'], 403);
        }

        $validatedData = $this->validateLeadData($request);

        if (!$this->verifyOwnership(Company::class, $validatedData['company_id'], $user_id)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid company selected.'], 403);
        }
        if (!$this->verifyOwnership(CompanyBankAccount::class, $validatedData['company_bank_account_id'], $user_id)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid bank account selected.'], 403);
        }

        try {
            DB::beginTransaction();

            $lead->update([
                'company_id' => $validatedData['company_id'],
                'company_bank_account_id' => $validatedData['company_bank_account_id'],
                'loan_amount' => $validatedData['loan_amount'],
                'loan_type_id' => $validatedData['loan_type_id'] ?? null,
            ]);

            $application = $lead->loanApplications->first();
            if ($application) {
                $application->update(['bank_id' => $validatedData['bank_id']]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loan application updated successfully.',
                'data' => ['lead_id' => $lead->id]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to update loan application.', 'error' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        $user_id = Auth::id();

        $lead = Lead::with(['status'])->where('id', $id)->where('user_id', $user_id)->first();

        if (!$lead) {
            return response()->json(['status' => 'error', 'message' => 'Lead not found or unauthorized.'], 404);
        }

        if ($lead->status && $lead->status->internal_code !== LeadStatus::STATUS_NEW) {
            return response()->json(['status' => 'error', 'message' => 'Cannot delete an application that is already being processed.'], 403);
        }

        try {
            DB::beginTransaction();

            $documents = \App\Models\Document::where('lead_id', $lead->id)->get();
            foreach ($documents as $doc) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
                }
                $doc->delete();
            }

            LoanApplication::where('lead_id', $lead->id)->delete();

            $lead->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loan application cancelled and deleted successfully.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to delete lead.', 'error' => $e->getMessage()], 500);
        }
    }


    private function validateLeadData(Request $request)
    {
        return $request->validate([
            'company_id' => 'required|exists:companies,id',
            'loan_amount' => 'required|numeric|min:10000',
            'loan_type_id' => 'nullable|exists:loan_types,id',
            'bank_id' => 'required|exists:banks,id',
            'company_bank_account_id' => 'required|exists:company_bank_accounts,id',
        ]);
    }

    private function verifyOwnership($modelClass, $modelId, $userId)
    {
        return $modelClass::where('id', $modelId)->where('user_id', $userId)->exists();
    }
}