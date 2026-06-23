<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LoanType;
use Illuminate\Support\Facades\DB;
class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with([
            'user',
            'company',
            'loanType',
            'status',
            'assignedAgent'
        ]);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('lead_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('company', function ($c) use ($search) {
                        $c->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status_id', $request->status);
        }
        if ($request->filled('loan_type')) {
            $query->where('loan_type_id', $request->loan_type);
        }

        if ($request->filled('sort')) {
            $direction = $request->direction == 'asc' ? 'asc' : 'desc';
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $leads = $query->paginate(20);

        $allStatuses = LeadStatus::orderBy('sort_order')->get();
        $statuses = $allStatuses;
        $loanTypes = LoanType::where('status', 1)->get();

        $statusCounts = Lead::select('status_id', DB::raw('count(*) as count'))
            ->groupBy('status_id')
            ->pluck('count', 'status_id');

        $newLeads = 0;
        $inProgressLeads = 0;
        $convertedLeads = 0;
        $lostLeads = 0;

        foreach ($allStatuses as $status) {
            $count = $statusCounts->get($status->id, 0);

            if ($status->internal_code === 'NEW') {
                $newLeads += $count;
            } elseif (in_array($status->internal_code, ['BANK_PROCESSING', 'SCREENING', 'PRE_QUALIFIED', 'PENDING_DOCS'])) {
                $inProgressLeads += $count;
            } elseif (in_array($status->internal_code, ['APPROVED', 'DISBURSED'])) {
                $convertedLeads += $count;
            } elseif ($status->internal_code === 'REJECTED') {
                $lostLeads += $count;
            }
        }

        return view('admin.leads.index', compact(
            'leads',
            'allStatuses',
            'statuses',
            'loanTypes',
            'newLeads',
            'inProgressLeads',
            'convertedLeads',
            'lostLeads'
        ));
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