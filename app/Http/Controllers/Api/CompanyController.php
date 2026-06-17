<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class CompanyController extends Controller
{
    public function show(Request $request)
    {
        $user_id = Auth::id();
        $company = Company::with('members')->where('user_id', $user_id)->first();

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Company profile not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $company
        ], 200);
    }

    public function upsert(Request $request)
    {
        $user_id = Auth::id();

        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'entity_type' => 'required|string|in:Proprietorship,Partnership,LLP,Pvt Ltd',
            'industry_type' => 'nullable|string|in:Trading,Manufacturing,Service',
            'gst_number' => 'nullable|string|max:15',
            'pan_number' => 'required|string|size:10',
            'udyam_registration_number' => 'nullable|string|max:50',
            'date_of_incorporation' => 'nullable|date',
            'monthly_revenue' => 'required|numeric|min:0',
            'turnover' => 'nullable|numeric|min:0',
            'annual_income' => 'nullable|numeric|min:0',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',

            'members' => 'nullable|array',
            'members.*.name' => 'required_with:members|string|max:255',
            'members.*.designation' => 'required_with:members|string|max:100',
            'members.*.pan_number' => 'required_with:members|string|size:10',
            'members.*.aadhaar_number' => 'nullable|string|size:12',
            'members.*.mobile' => 'required_with:members|string|max:15',
            'members.*.ownership_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $companyData = Arr::except($validatedData, ['members']);

            $company = Company::updateOrCreate(
                ['user_id' => $user_id],
                $companyData
            );

            if (isset($validatedData['members']) && is_array($validatedData['members'])) {
                $company->members()->delete();

                $membersData = array_map(function ($member) use ($company) {
                    $member['company_id'] = $company->id;
                    $member['created_at'] = now();
                    $member['updated_at'] = now();
                    return $member;
                }, $validatedData['members']);

                CompanyMember::insert($membersData);
            }

            DB::commit();

            $company->load('members');

            return response()->json([
                'status' => 'success',
                'message' => 'Company and members details saved successfully.',
                'data' => $company
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while saving details.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}