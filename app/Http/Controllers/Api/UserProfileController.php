<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentMaster;
use App\Models\Document;
use App\Services\DocumentEvaluationService;
class UserProfileController extends Controller
{
   protected $documentService;

    // 👈 Constructor mein service inject ki (Dependency Injection)
    public function __construct(DocumentEvaluationService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function show(Request $request)
    {
        $user = Auth::user();

        $profile = UserProfile::where('user_id', $user->id)->first();

        $userMasterDocs = DocumentMaster::where('status', 1)
            ->where('document_level', 'user')
            ->get();

        $userUploads = Document::where('user_id', $user->id)
            ->whereNull('company_id')
            ->whereNull('lead_id')
            ->get()
            ->groupBy('document_master_id');

        // 👈 Ab private function ki jagah Service call hogi
        $personalDocuments = $this->documentService->evaluate($userMasterDocs, $userUploads);

        if (!$profile) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile data not found. User needs to create a profile.',
                'data' => [
                    'user' => $user,
                    'profile' => null,
                    'current_step' => $user->current_step,
                    'personal_documents' => $personalDocuments 
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
                'personal_documents' => $personalDocuments 
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
   
}