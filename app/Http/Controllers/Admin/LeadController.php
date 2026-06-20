<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with([
            'user',
            'company',
            'loanType',
            'status',
            'assignedAgent'
        ])
        ->latest()
        ->paginate(20);

        return view('admin.leads.index', compact('leads'));
    }

    public function show($id)
    {
        $lead = Lead::with([
            'user',
            'company',
            'loanType',
            'status',
            'assignedAgent',
            'documents',
            'loanApplications.bank'
        ])->findOrFail($id);

        $statuses = LeadStatus::all();
        $agents = User::all();

        return view(
            'admin.leads.show',
            compact(
                'lead',
                'statuses',
                'agents'
            )
        );
    }

    public function assignAgent(Request $request, Lead $lead)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        $lead->update([
            'assigned_to' => $request->assigned_to
        ]);

        return back()->with(
            'success',
            'Agent assigned successfully.'
        );
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status_id' => 'required|exists:lead_statuses,id'
        ]);

        $lead->update([
            'status_id' => $request->status_id
        ]);

        return back()->with(
            'success',
            'Lead status updated successfully.'
        );
    }
}