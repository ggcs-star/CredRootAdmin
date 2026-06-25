<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentMaster;
use App\Models\Document;

class UserProfileController extends Controller
{
   public function show(Request $request)
    {
        $user = Auth::user();

        // 1. Fetch User Profile
        $profile = UserProfile::where('user_id', $user->id)->first();

        // 2. Fetch Personal Level Documents
        $userMasterDocs = DocumentMaster::where('status', 1)
            ->where('document_level', 'user')
            ->get();

        $userUploads = Document::where('user_id', $user->id)
            ->whereNull('company_id')
            ->whereNull('lead_id')
            ->get()
            ->groupBy('document_master_id');

        // 3. Evaluate Documents Status
        $personalDocuments = $this->evaluateDocuments($userMasterDocs, $userUploads);

        if (!$profile) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile data not found. User needs to create a profile.',
                'data' => [
                    'user' => $user,
                    'profile' => null,
                    'current_step' => $user->current_step,
                    'personal_documents' => $personalDocuments // Naya addition
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile fetched successfully.',
            'data' => [
                'user' => $user,
                'profile' => $profile,
                'current_step' => $user->current_step,
                'personal_documents' => $personalDocuments // Naya addition
            ]
        ], 200);
    }
    public function upsert(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validatedData = $request->validate([
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'aadhaar_number' => 'nullable|string|size:12|unique:user_profiles,aadhaar_number,' . $user_id . ',user_id',
            'pan_number' => 'nullable|string|size:10|unique:user_profiles,pan_number,' . $user_id . ',user_id',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
        ]);

        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user_id],
            $validatedData
        );

        if ($user->current_step < 2) {
            $user->update(['current_step' => 2]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile saved successfully.',
            'data' => [
                'profile' => $profile,
                'current_step' => $user->current_step
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
            'is_kyc_complete' => ($mandatoryPendingCount === 0),
            'pending_mandatory_count' => $mandatoryPendingCount,
            'pending_documents' => array_values($pending),
            'completed_documents' => array_values($completed),
        ];
    }
}