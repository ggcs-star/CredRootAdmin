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
        $user_id = Auth::id();

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

        return response()->json([
            'status' => 'success',
            'message' => 'Profile saved successfully.',
            'data' => $profile
        ], 200);
    }
}