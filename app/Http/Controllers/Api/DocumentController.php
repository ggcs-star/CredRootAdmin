<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Lead;
use App\Models\Company;
use App\Models\DocumentMaster;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {

        $request->validate([
            'document_master_id' => 'required|exists:document_masters,id',
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200',
            'document_side' => 'required|string|in:front,back,single',
        ]);

        $user_id = Auth::id();
        $masterDoc = DocumentMaster::find($request->document_master_id);


        $dynamicRules = [];
        $customMessages = [];

        if ($masterDoc->document_level === 'company') {
            $dynamicRules['company_id'] = 'required|integer|exists:companies,id';
            $customMessages['company_id.required'] = "The company_id is strictly required because '{$masterDoc->name}' is a Company level document.";
        } elseif ($masterDoc->document_level === 'lead') {
            $dynamicRules['lead_id'] = 'required|integer|exists:leads,id';
            $customMessages['lead_id.required'] = "The lead_id is strictly required because '{$masterDoc->name}' is a Loan/Lead level document.";
        }

        if (!empty($dynamicRules)) {
            $request->validate($dynamicRules, $customMessages);
        }

        $companyId = null;
        $leadId = null;
        $folderPath = "user_documents/{$user_id}";

        if ($masterDoc->document_level === 'company') {
            $company = Company::where('id', $request->company_id)->where('user_id', $user_id)->first();
            if (!$company) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized company access.'], 403);
            }
            $companyId = $company->id;
            $folderPath .= "/company_{$companyId}";
        } elseif ($masterDoc->document_level === 'lead') {
            $lead = Lead::where('id', $request->lead_id)->where('user_id', $user_id)->first();
            if (!$lead) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized lead access.'], 403);
            }
            $leadId = $lead->id;
            $companyId = $lead->company_id;
            $folderPath .= "/lead_{$leadId}";
        } else {
            $folderPath .= "/personal";
        }

        $file = $request->file('file');
        $cleanOriginalName = preg_replace('/[^A-Za-z0-9.\-]/', '', $file->getClientOriginalName());
        $fileName = time() . '_' . $request->document_side . '_' . $cleanOriginalName;

        $filePath = $file->storeAs($folderPath, $fileName, 'public');

        try {
            DB::beginTransaction();

            $matchConditions = [
                'user_id' => $user_id,
                'document_master_id' => $request->document_master_id,
                'document_side' => $request->document_side,
                'company_id' => ($masterDoc->document_level === 'company') ? $companyId : null,
                'lead_id' => ($masterDoc->document_level === 'lead') ? $leadId : null,
            ];

            $existingDoc = Document::where($matchConditions)->first();
            if ($existingDoc && Storage::disk('public')->exists($existingDoc->file_path)) {
                Storage::disk('public')->delete($existingDoc->file_path);
            }

            $document = Document::updateOrCreate(
                $matchConditions,
                [
                    'document_type' => $file->getMimeType(),
                    'file_path' => $filePath,
                    'verification_status' => 'pending',
                ]
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => ucfirst($request->document_side) . ' side uploaded.',
                'data' => [
                    'document_id' => $document->id,
                    'file_url' => asset('storage/' . $filePath)
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return response()->json(['status' => 'error', 'message' => 'Upload failed due to server error.', 'error' => $e->getMessage()], 500);
        }
    }

    public function finalizeUploads(Request $request)
    {
        $request->validate(['lead_id' => 'required|exists:leads,id']);
        $user = Auth::user();

        $lead = Lead::with('company')->where('id', $request->lead_id)->where('user_id', $user->id)->first();
        if (!$lead) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized lead.'], 403);
        }

        $entityType = $lead->company->entity_type ?? null;
        $loanTypeId = $lead->loan_type_id;

        $mandatoryDocs = DocumentMaster::where('status', 1)
            ->where('is_mandatory', true)
            ->where(function ($query) use ($entityType) {
                $query->whereNull('applicable_entities')
                    ->orWhereJsonContains('applicable_entities', $entityType);
            })
            ->where(function ($query) use ($loanTypeId) {
                $query->whereNull('applicable_loan_types')
                    ->orWhereJsonContains('applicable_loan_types', (string) $loanTypeId)
                    ->orWhereJsonContains('applicable_loan_types', (int) $loanTypeId);
            })->get();

        $uploadedDocs = Document::where('user_id', $user->id)
            ->where(function ($query) use ($lead) {
                $query->where(function ($q) {
                    $q->whereNull('company_id')->whereNull('lead_id');
                })->orWhere(function ($q) use ($lead) {
                    $q->where('company_id', $lead->company_id)->whereNull('lead_id');
                })->orWhere(function ($q) use ($lead) {
                    $q->where('lead_id', $lead->id);
                });
            })->get()->groupBy('document_master_id');

        $missingDocuments = [];

        foreach ($mandatoryDocs as $doc) {
            $uploadsForThisDoc = $uploadedDocs->get($doc->id, collect());
            $uploadedSides = $uploadsForThisDoc->pluck('document_side')->toArray();

            $isComplete = false;
            if ($doc->sides_required == 0 && (in_array('single', $uploadedSides) || in_array('front', $uploadedSides))) {
                $isComplete = true;
            } elseif ($doc->sides_required == 1 && in_array('front', $uploadedSides)) {
                $isComplete = true;
            } elseif ($doc->sides_required == 2 && in_array('front', $uploadedSides) && in_array('back', $uploadedSides)) {
                $isComplete = true;
            }

            if (!$isComplete) {
                $missingDocuments[] = ['level' => $doc->document_level, 'name' => $doc->name];
            }
        }

        if (!empty($missingDocuments)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mandatory documents are missing.',
                'missing_documents' => $missingDocuments
            ], 422);
        }

        try {
            DB::beginTransaction();

            if ($user->current_step < 6) {
                $user->update(['current_step' => 6]);
            }

            $reviewStatus = LeadStatus::where('internal_code', 'DOCS_UPLOADED')
                ->orWhere('internal_code', 'UNDER_REVIEW')
                ->first();

            if ($reviewStatus) {
                $lead->update(['status_id' => $reviewStatus->id]);

                \App\Models\LoanApplication::where('lead_id', $lead->id)
                    ->update(['status_id' => $reviewStatus->id]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Application submitted successfully! Your documents are under review.',
                'data' => ['current_step' => $user->current_step]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to submit application.'], 500);
        }
    }
}