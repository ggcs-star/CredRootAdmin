<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyBankAccountController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'company_id' => 'required|integer'
        ]);

        $company = Company::where('id', $request->company_id)
                          ->where('user_id', Auth::id())
                          ->first();

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'Company not found or unauthorized.'], 403);
        }

        $bankAccounts = CompanyBankAccount::where('company_id', $company->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $bankAccounts
        ], 200);
    }

    public function store(Request $request)
    {
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
                          ->where('user_id', Auth::id())
                          ->first();

        if (!$company) {
            return response()->json(['status' => 'error', 'message' => 'Company not found or unauthorized.'], 403);
        }

        if (isset($validatedData['is_primary']) && $validatedData['is_primary']) {
            CompanyBankAccount::where('company_id', $company->id)->update(['is_primary' => false]);
        }

        $bankAccount = CompanyBankAccount::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account added successfully.',
            'data' => $bankAccount
        ], 201);
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