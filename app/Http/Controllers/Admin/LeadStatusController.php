<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadStatus;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{
    public function index()
    {
        $statuses = LeadStatus::orderBy('sort_order')->paginate(10);

        return view('admin.lead-statuses.index', compact('statuses'));
    }

    public function create()
    {
        return view('admin.lead-statuses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        LeadStatus::create($validated);

        return redirect()->route('lead-statuses.index')
            ->with('success', 'Lead Status created successfully.');
    }

    public function show(LeadStatus $leadStatus)
    {
        return view('admin.lead-statuses.show', compact('leadStatus'));
    }

    public function edit(LeadStatus $leadStatus)
    {
        return view('admin.lead-statuses.edit', compact('leadStatus'));
    }

    public function update(Request $request, LeadStatus $leadStatus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        $leadStatus->update($validated);

        return redirect()->route('lead-statuses.index')
            ->with('success', 'Lead Status updated successfully.');
    }

    public function destroy(LeadStatus $leadStatus)
    {
        $leadStatus->delete();

        return redirect()->route('lead-statuses.index')
            ->with('success', 'Lead Status deleted successfully.');
    }
}