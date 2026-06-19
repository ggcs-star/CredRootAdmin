<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyBankAccountController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'company_id' => 'nullable|integer'
        ]);

        $userId = Auth::id();
        $company = null;

        if ($request->filled('company_id')) {
            $company = Company::where('id', $request->company_id)
                ->where('user_id', $userId)
                ->first();
        } else {
            $company = Company::where('user_id', $userId)
                ->latest()
                ->first();
        }

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'No active company found.'], 404);
        }

        $latestBankAccount = CompanyBankAccount::where('company_id', $company->id)
            ->latest()
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => $latestBankAccount
        ], 200);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validatedData = $request->validate([
            'company_id' => 'required|integer',
            'bank_name' => 'required|string|max:100',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
            'account_type' => 'required|in:Current,Savings,OD/CC',
            'is_primary' => 'boolean'
        ]);

        $company = Company::where('id', $validatedData['company_id'])
            ->where('user_id', $user_id)
            ->first();

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'Company not found or unauthorized.'], 403);
        }

        if (isset($validatedData['is_primary']) && $validatedData['is_primary']) {
            CompanyBankAccount::where('company_id', $company->id)->update(['is_primary' => false]);
        }

        $bankAccount = CompanyBankAccount::updateOrCreate(
            ['company_id' => $company->id],
            $validatedData
        );


        if ($user->current_step < 4) {
            $user->update(['current_step' => 4]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account saved successfully.',
            'data' => [
                'bank_account' => $bankAccount,
                'current_step' => $user->current_step
            ]
        ], 200);
    }

    public function destroy($id)
    {
        $bankAccount = CompanyBankAccount::where('id', $id)
            ->whereHas('company', function ($query) {
                $query->where('user_id', Auth::id());
            })->first();

        if (!$bankAccount) {
            return response()->json(['status' => 'error', 'message' => 'Bank account not found or unauthorized.'], 404);
        }

        $bankAccount->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account deleted successfully.'
        ], 200);
    }
}