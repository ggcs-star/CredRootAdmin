<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Lead;
use App\Models\LoanApplication;
use App\Models\LeadStatus; // Import karna zaroori hai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    // Loan ke liye apply karna (Final Step of Wizard)
    public function applyForLoan(Request $request)
    {
        // Auth::id() ki jagah Auth::user() fetch kar rahe hain step update karne ke liye
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

        // MAGIC HERE: Database se 'NEW' status ki actual ID nikal rahe hain
        $newStatusId = LeadStatus::where('internal_code', LeadStatus::STATUS_NEW)->value('id');

        // Failsafe: Agar galti se database mein status nahi hai
        if (!$newStatusId) {
            return response()->json([
                'status' => 'error', 
                'message' => 'System Configuration Error: Default status not found.'
            ], 500);
        }

        try {
            DB::beginTransaction();

            // 2. Nayi Lead Create Karein
            $lead = Lead::create([
                'lead_number' => 'Ld-' . strtoupper(uniqid()), 
                'user_id' => $user_id,
                'company_id' => $company->id,
                'loan_amount' => $validatedData['loan_amount'],
                'loan_type_id' => $validatedData['loan_type_id'] ?? null,
                'status_id' => $newStatusId, // Hardcoded 1 ki jagah dynamic ID
            ]);

            // 3. User ne jo Bank select kiya hai, uski Loan Application create karein
            $application = LoanApplication::create([
                'lead_id' => $lead->id,
                'bank_id' => $validatedData['bank_id'],
                'status_id' => $newStatusId, // Yahan bhi dynamic ID
                'remarks' => 'User selected this bank directly from the portal.'
            ]);

            // --- NEW STEP LOGIC ---
            // Loan apply hone ke baad user ko Step 5 (Document Upload) par bhej do
            if ($user->current_step < 5) {
                $user->update(['current_step' => 5]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Loan application submitted successfully!',
                'data' => [
                    'lead_id' => $lead->id, // Frontend ko docs upload ke liye lead_id chahiye hogi
                    'lead_number' => $lead->lead_number,
                    'loan_amount' => $lead->loan_amount,
                    'selected_bank_id' => $application->bank_id,
                    'current_step' => $user->current_step // Naya step return kar diya
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