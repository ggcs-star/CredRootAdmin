<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentMaster;
class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'document_master_id' => 'required|exists:document_masters,id',
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf',
            'document_side' => 'required|string|in:front,back,single', // Frontend batayega konsi side hai
        ]);

        $user_id = Auth::id();

        // Security check
        $lead = Lead::where('id', $request->lead_id)->where('user_id', $user_id)->first();
        if (!$lead) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized or invalid lead.'], 403);
        }

        $file = $request->file('file');
        
        // File name mein side ka naam bhi add kar dete hain taaki folder mein samajh aaye
        $fileName = time() . '_' . $request->document_side . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('user_documents/' . $user_id . '/lead_' . $lead->id, $fileName, 'public');

        // UpdateOrCreate mein ab document_side bhi check hoga!
        $document = Document::updateOrCreate(
            [
                'user_id' => $user_id,
                'lead_id' => $lead->id,
                'document_master_id' => $request->document_master_id,
                'document_side' => $request->document_side, // Front overwrite nahi karega Back ko
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
        
        // Lead ke sath Company bhi load kar rahe hain taaki entity_type mil sake
        $lead = Lead::with('company')->where('id', $request->lead_id)->where('user_id', $user->id)->first();

        if (!$lead) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized or invalid lead.'], 403);
        }

        // --- STRICT BACKEND VALIDATION START ---
        $entityType = $lead->company->entity_type ?? null;
        $loanTypeId = $lead->loan_type_id;

        // 1. Sirf Mandatory Required Documents nikaalein
        $mandatoryDocs = DocumentMaster::where('status', 1)
            ->where('collection_stage', 'final_application')
            ->where('is_mandatory', true) // Sirf mandatory check karna hai
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

        // 2. User ne is lead mein ab tak kya upload kiya hai
        $uploadedDocs = Document::where('lead_id', $lead->id)
            ->get()
            ->groupBy('document_master_id');

        $missingDocuments = [];

        // 3. Match karein ki required docs upload hue hain ya nahi
        foreach ($mandatoryDocs as $doc) {
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

            // Agar koi document incomplete hai, toh array mein uska naam daal do
            if (!$isComplete) {
                $missingDocuments[] = $doc->name;
            }
        }

        // 4. Agar missing documents array khali nahi hai, toh Error Return karo
        if (!empty($missingDocuments)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot submit application. Mandatory documents are missing.',
                'missing_documents' => $missingDocuments // Frontend ko batao kya missing hai
            ], 422); // 422 Unprocessable Entity
        }
        // --- STRICT BACKEND VALIDATION END ---

        // --- THE MAGIC ---
        if ($user->current_step < 6) {
            $user->update(['current_step' => 6]);
        }

        // Lead ka status bhi 'NEW' se change karke 'DOCS_UPLOADED' kar dete hain
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