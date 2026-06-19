<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Lead;
use App\Models\DocumentMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'document_master_id' => 'required|exists:document_masters,id',
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:51200', // max size add kar diya (50MB)
            'document_side' => 'required|string|in:front,back,single',
        ]);

        $user_id = Auth::id();

        // Security check
        $lead = Lead::where('id', $request->lead_id)->where('user_id', $user_id)->first();
        if (!$lead) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized or invalid lead.'], 403);
        }

        $file = $request->file('file');
        
        // File name mein side ka naam add kar rahe hain
        $fileName = time() . '_' . $request->document_side . '_' . preg_replace('/[^A-Za-z0-9.\-]/', '', $file->getClientOriginalName()); // Special characters remove kar diye
        $filePath = $file->storeAs('user_documents/' . $user_id . '/lead_' . $lead->id, $fileName, 'public');

        // UpdateOrCreate logic
        $document = Document::updateOrCreate(
            [
                'user_id' => $user_id,
                'lead_id' => $lead->id,
                'document_master_id' => $request->document_master_id,
                'document_side' => $request->document_side,
            ],
            [
                'document_type' => $file->getMimeType(),
                'file_path' => $filePath,
                'verification_status' => 'pending', 
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => ucfirst($request->document_side) . ' side uploaded successfully.',
            'data' => [
                'document_id' => $document->id,
                'document_side' => $document->document_side,
                'file_url' => asset('storage/' . $filePath),
                'verification_status' => $document->verification_status
            ]
        ], 201);
    }

    public function finalizeUploads(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
        ]);

        $user = Auth::user();
        
        // Lead aur Company details load karein
        $lead = Lead::with('company')->where('id', $request->lead_id)->where('user_id', $user->id)->first();

        if (!$lead) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized or invalid lead.'], 403);
        }

        // --- STRICT BACKEND VALIDATION START ---
        $entityType = $lead->company->entity_type ?? null;
        $loanTypeId = $lead->loan_type_id;

        // 1. Mandatory Required Documents fetch karein
        $mandatoryDocs = DocumentMaster::where('status', 1)
            ->where('collection_stage', 'final_application')
            ->where('is_mandatory', true)
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
            })->get();

        // 2. Uploaded documents nikaalein
        $uploadedDocs = Document::where('lead_id', $lead->id)
            ->get()
            ->groupBy('document_master_id');

        $missingDocuments = [];

        // 3. Validation Logic Match Karein
        foreach ($mandatoryDocs as $doc) {
            $uploadsForThisDoc = $uploadedDocs->get($doc->id, collect());
            $uploadedSides = $uploadsForThisDoc->pluck('document_side')->toArray();

            $isComplete = false;
            
            // --- MAIN FIX: 0 sides (Single) ke liye 'front' ko bhi valid maanega ---
            if ($doc->sides_required == 0 && (in_array('single', $uploadedSides) || in_array('front', $uploadedSides))) {
                $isComplete = true;
            } elseif ($doc->sides_required == 1 && in_array('front', $uploadedSides)) {
                $isComplete = true;
            } elseif ($doc->sides_required == 2 && in_array('front', $uploadedSides) && in_array('back', $uploadedSides)) {
                $isComplete = true;
            }

            if (!$isComplete) {
                $missingDocuments[] = $doc->name;
            }
        }

        // 4. Missing document response
        if (!empty($missingDocuments)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot submit application. Mandatory documents are missing.',
                'missing_documents' => $missingDocuments
            ], 422); 
        }
        // --- STRICT BACKEND VALIDATION END ---

        // --- STEP UPDATE MAGIC ---
        if ($user->current_step < 6) {
            $user->update(['current_step' => 6]);
        }

        // TODO: Lead status update logic (Uncomment when LeadStatus is fully setup)
        // $docsUploadedStatusId = LeadStatus::where('internal_code', 'DOCS_UPLOADED')->value('id');
        // if ($docsUploadedStatusId) {
        //     $lead->update(['status_id' => $docsUploadedStatusId]);
        // }

        return response()->json([
            'status' => 'success',
            'message' => 'Application submitted successfully! Your documents are under review.',
            'data' => [
                'current_step' => $user->current_step
            ]
        ], 200);
    }
}