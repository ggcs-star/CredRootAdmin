<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyBankAccountController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $bankAccounts = CompanyBankAccount::where('user_id', $userId)
            ->orderBy('is_primary', 'desc')
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => $bankAccounts->isEmpty() ? 'No bank accounts found.' : 'Bank accounts fetched successfully.',
            'data' => $bankAccounts
        ], 200);
    }

    public function show($id)
    {
        $userId = Auth::id();

        $bankAccount = CompanyBankAccount::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$bankAccount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank account not found or unauthorized.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $bankAccount
        ], 200);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = $this->validateBankData($request);
        $validatedData['user_id'] = $user->id;

        if (!empty($validatedData['is_primary'])) {
            CompanyBankAccount::where('user_id', $user->id)->update(['is_primary' => false]);
        }

        $bankAccount = CompanyBankAccount::create($validatedData);

        if ($user->current_step < 4) {
            $user->update(['current_step' => 4]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account added successfully.',
            'data' => [
                'bank_account' => $bankAccount,
                'current_step' => $user->current_step
            ]
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $bankAccount = CompanyBankAccount::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$bankAccount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank account not found or unauthorized.'
            ], 404);
        }

        $validatedData = $this->validateBankData($request);

        if (!empty($validatedData['is_primary']) && !$bankAccount->is_primary) {
            CompanyBankAccount::where('user_id', $user->id)->update(['is_primary' => false]);
        }

        $bankAccount->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account updated successfully.',
            'data' => [
                'bank_account' => $bankAccount
            ]
        ], 200);
    }

    public function destroy($id)
    {
        $bankAccount = CompanyBankAccount::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$bankAccount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bank account not found or unauthorized.'
            ], 404);
        }

        $wasPrimary = $bankAccount->is_primary;
        $bankAccount->delete();

        if ($wasPrimary) {
            $nextBank = CompanyBankAccount::where('user_id', Auth::id())->first();
            if ($nextBank) {
                $nextBank->update(['is_primary' => true]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Bank account deleted successfully.'
        ], 200);
    }

    private function validateBankData(Request $request)
    {
        return $request->validate([
            'company_id' => 'nullable|integer|exists:companies,id',
            'bank_name' => 'required|string|max:100',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'ifsc_code' => 'required|string|max:20',
            'account_type' => 'required|in:Current,Savings,OD/CC',
            'is_primary' => 'boolean'
        ]);
    }
}