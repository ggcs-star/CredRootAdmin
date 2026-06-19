<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lead;
use App\Models\LoanApplication;
use App\Models\LeadStatus; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{

    public function show(Request $request)
    {
        $user = Auth::user();

        $leadQuery = Lead::with([
            'company',
            'loanType',
            'status',
            'loanApplications.bank'
        ])->where('user_id', $user->id);

        if ($request->filled('lead_id')) {
            $leadQuery->where('id', $request->lead_id);
        }

        $lead = $leadQuery->latest()->first();

        if (!$lead) {
            return response()->json([
                'status' => 'success',
                'message' => 'No active loan application found.',
                'data' => [
                    'lead' => null,
                    'current_step' => $user->current_step
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Loan application fetched successfully.',
            'data' => [
                'lead' => $lead,
                'current_step' => $user->current_step
            ]
        ], 200);
    }
    public function applyForLoan(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;

        $validatedData = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'loan_amount' => 'required|numeric|min:10000',
            'bank_id' => 'required|exists:banks,id',
            'loan_type_id' => 'nullable|exists:loan_types,id',
        ]);

        $company = Company::where('id', $validatedData['company_id'])
            ->where('user_id', $user_id)
            ->first();

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid company selected.'
            ], 403);
        }

        $newStatusId = LeadStatus::where('internal_code', LeadStatus::STATUS_NEW)->value('id');

        if (!$newStatusId) {
            return response()->json([
                'status' => 'error',
                'message' => 'System Configuration Error: Default status not found.'
            ], 500);
        }

        try {
            DB::beginTransaction();

            $lead = Lead::create([
                'lead_number' => 'Ld-' . strtoupper(uniqid()),
                'user_id' => $user_id,
                'company_id' => $company->id,
                'loan_amount' => $validatedData['loan_amount'],
                'loan_type_id' => $validatedData['loan_type_id'] ?? null,
                'status_id' => $newStatusId,
            ]);

            $application = LoanApplication::create([
                'lead_id' => $lead->id,
                'bank_id' => $validatedData['bank_id'],
                'status_id' => $newStatusId,
                'remarks' => 'User selected this bank directly from the portal.'
            ]);

            if ($user->current_step < 5) {
                $user->update(['current_step' => 5]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loan application submitted successfully!',
                'data' => [
                    'lead_id' => $lead->id,
                    'lead_number' => $lead->lead_number,
                    'loan_amount' => $lead->loan_amount,
                    'selected_bank_id' => $application->bank_id,
                    'current_step' => $user->current_step
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while applying for loan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}