<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{

   public function upsert(Request $request)
    {
        // Auth::id() ki jagah Auth::user() use kar rahe hain taaki step update kar sakein
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

        // Profile Update ya Create karein
        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user_id],
            $validatedData
        );

        // --- NEW STEP LOGIC ---
        // Agar user pehli baar profile bhar raha hai (step < 2), toh step 2 set karo
        if ($user->current_step < 2) {
            $user->update(['current_step' => 2]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profile saved successfully.',
            'data' => [
                'profile' => $profile,
                'current_step' => $user->current_step // Frontend ko naya step bhej dein taaki wo redirect kar sake
            ]
        ], 200);
    }
}