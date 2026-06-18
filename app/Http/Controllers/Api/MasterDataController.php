<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentMaster;
use App\Models\LoanType;
use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
class MasterDataController extends Controller
{
    public function getEntityTypes()
    {
        $entities = config('msme.entity_types');

        return response()->json([
            'status' => 'success',
            'data' => $entities
        ], 200);
    }

    public function getLoanTypes()
    {
        $loanTypes = LoanType::where('status', 1)->get([
            'id',
            'slug',
            'name',
            'description',
            'icon_path'
        ])->map(function ($loan) {
            $loan->icon_url = $loan->icon_path ? asset('storage/' . $loan->icon_path) : null;
            unset($loan->icon_path);

            return $loan;
        });

        return response()->json([
            'status' => 'success',
            'data' => $loanTypes
        ], 200);
    }

    public function getRequiredDocuments(Request $request)
    {
        $request->validate([
            'stage' => 'required|in:pre_qualification,final_application',
            'lead_id' => 'nullable|exists:leads,id'
        ]);

        $user_id = Auth::id();

        $entityType = null;
        $loanTypeId = null;
        $leadId = $request->lead_id;

        if ($request->stage === 'final_application') {
            $leadQuery = \App\Models\Lead::with('company')->where('user_id', $user_id);

            if ($leadId) {
                $leadQuery->where('id', $leadId);
            }
            $lead = $leadQuery->latest()->first();

            if (!$lead) {
                return response()->json(['status' => 'error', 'message' => 'No active loan application found.'], 404);
            }

            $leadId = $lead->id;
            $entityType = $lead->company->entity_type ?? null;
            $loanTypeId = $lead->loan_type_id;

        } else {
            $company = \App\Models\Company::where('user_id', $user_id)->first();
            $entityType = $company->entity_type ?? null;
        }

        $uploadedDocs = collect();
        if ($leadId) {
            $uploadedDocs = Document::where('lead_id', $leadId)
                ->get()
                ->groupBy('document_master_id');
        }

        $documents = DocumentMaster::where('status', 1)
            ->where('collection_stage', $request->stage)

            ->where(function ($query) use ($entityType) {
                $query->whereNull('applicable_entities');
                if ($entityType) {
                    $query->orWhereJsonContains('applicable_entities', $entityType);
                }
            })

            ->where(function ($query) use ($loanTypeId) {
                $query->whereNull('applicable_loan_types');
                if ($loanTypeId) {
                    $query->orWhereJsonContains('applicable_loan_types', (string) $loanTypeId)
                        ->orWhereJsonContains('applicable_loan_types', (int) $loanTypeId);
                }
            })
            ->get([
                'id',
                'document_code',
                'name',
                'description',
                'is_mandatory',
                'sides_required',
                'allowed_formats',
                'max_size_kb',
                'sample_image_url'
            ])
            ->map(function ($doc) use ($uploadedDocs) {
                $doc->sample_image_full_url = $doc->sample_image_url ? asset('storage/' . $doc->sample_image_url) : null;
                unset($doc->sample_image_url);

                $uploadsForThisDoc = $uploadedDocs->get($doc->id, collect());
                $uploadedSides = $uploadsForThisDoc->pluck('document_side')->toArray();

                $isComplete = false;
                if ($doc->sides_required == 0 && in_array('single', $uploadedSides)) {
                    $isComplete = true;
                } elseif ($doc->sides_required == 1 && in_array('front', $uploadedSides)) {
                    $isComplete = true;
                } elseif ($doc->sides_required == 2 && in_array('front', $uploadedSides) && in_array('back', $uploadedSides)) {
                    $isComplete = true;
                }

                $doc->is_uploaded = $isComplete;
                $doc->uploaded_sides = $uploadedSides;

                $doc->uploaded_files = $uploadsForThisDoc->map(function ($f) {
                    return [
                        'id' => $f->id,
                        'side' => $f->document_side,
                        'url' => asset('storage/' . $f->file_path),
                        'status' => $f->verification_status
                    ];
                });

                return $doc;
            });

        $allMandatoryComplete = $documents->where('is_mandatory', true)->where('is_uploaded', false)->isEmpty();

        return response()->json([
            'status' => 'success',
            'data' => [
                'documents' => $documents->values(),
                'can_submit_application' => $allMandatoryComplete
            ]
        ], 200);
    }

    public function getActiveBanks()
    {
        $banks = Bank::where('status', 1)->get([
            'id',
            'name',
            'code',
            'logo',
            'min_loan_amount',
            'max_loan_amount',
            'interest_rate_from',
            'interest_rate_to'
        ])->map(function ($bank) {
            $bank->logo_url = $bank->logo ? asset('storage/' . $bank->logo) : null;
            unset($bank->logo);
            return $bank;
        });

        return response()->json([
            'status' => 'success',
            'data' => $banks
        ], 200);
    }
}