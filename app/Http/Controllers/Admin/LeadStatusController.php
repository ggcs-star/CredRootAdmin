<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'internal_code' => 'nullable|string|max:255|unique:lead_statuses,internal_code',
            'color' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        // Agar user ne internal code nahi dala, toh Name se automatically bana lo
        $internalCode = $request->internal_code 
            ? strtoupper(str_replace(' ', '_', $request->internal_code))
            : strtoupper(Str::slug($request->name, '_'));

        LeadStatus::create([
            'name' => $validated['name'],
            'internal_code' => $internalCode,
            'color' => $validated['color'] ?? '#cccccc',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_system_locked' => $request->has('is_system_locked'), // Checkbox handling
        ]);

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
            'internal_code' => 'nullable|string|max:255|unique:lead_statuses,internal_code,' . $leadStatus->id,
            'color' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        $internalCode = $request->internal_code 
            ? strtoupper(str_replace(' ', '_', $request->internal_code))
            : strtoupper(Str::slug($request->name, '_'));

        $leadStatus->update([
            'name' => $validated['name'],
            'internal_code' => $internalCode,
            'color' => $validated['color'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_system_locked' => $request->has('is_system_locked'),
        ]);

        return redirect()->route('lead-statuses.index')
            ->with('success', 'Lead Status updated successfully.');
    }

    public function destroy(LeadStatus $leadStatus)
    {
        // Delete protection logic: System Locked status delete nahi ho sakta
        if ($leadStatus->is_system_locked) {
            return redirect()->route('lead-statuses.index')
                ->with('error', 'Cannot delete a System Locked status as it is required by the core application logic.');
        }

        $leadStatus->delete();

        return redirect()->route('lead-statuses.index')
            ->with('success', 'Lead Status deleted successfully.');
    }
}