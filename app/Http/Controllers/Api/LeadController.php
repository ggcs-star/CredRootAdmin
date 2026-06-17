<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lead;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    // Loan ke liye apply karna (Final Step of Wizard)
    public function applyForLoan(Request $request)
    {
        $user_id = Auth::id();

        // 1. Validation: User ko kitna loan chahiye aur kis bank se
        $validatedData = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'loan_amount' => 'required|numeric|min:10000',
            'bank_id' => 'required|exists:banks,id', // User ne jo lender bank select kiya hai (e.g., HDFC)
            'loan_type_id' => 'nullable|exists:loan_types,id', // Agar specific type hai (Working Capital etc.)
        ]);

        // Security Check: Kya ye company is logged-in user ki hi hai?
        $company = Company::where('id', $validatedData['company_id'])
                          ->where('user_id', $user_id)
                          ->first();

        if (!$company) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Invalid company selected.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // 2. Nayi Lead Create Karein
            $lead = Lead::create([
                'lead_number' => 'Ld-' . strtoupper(uniqid()), // Unique ID generate karna
                'user_id' => $user_id,
                'company_id' => $company->id,
                'loan_amount' => $validatedData['loan_amount'],
                'loan_type_id' => $validatedData['loan_type_id'] ?? null,
                'status_id' => 1, // Maan lijiye Status 1 = "New Application"
            ]);

            // 3. User ne jo Bank select kiya hai, uski Loan Application create karein
            $application = LoanApplication::create([
                'lead_id' => $lead->id,
                'bank_id' => $validatedData['bank_id'], // HDFC, ICICI etc.
                'status_id' => 1, // Status 1 = "Pending with Admin"
                'remarks' => 'User selected this bank directly from the portal.'
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loan application submitted successfully!',
                'data' => [
                    'lead_number' => $lead->lead_number,
                    'loan_amount' => $lead->loan_amount,
                    'selected_bank_id' => $application->bank_id
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