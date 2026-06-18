<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LoanTypeController extends Controller
{
    public function index()
    {
        $loanTypes = LoanType::latest()->paginate(10);
        return view('admin.loan-types.index', compact('loanTypes'));
    }

    public function create()
    {
        return view('admin.loan-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // 2MB Max
            'status' => 'required|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'status' => $validated['status'],
        ];

        // File Upload Logic
        if ($request->hasFile('icon')) {
            $data['icon_path'] = $request->file('icon')->store('loan_icons', 'public');
        }

        LoanType::create($data);

        return redirect()->route('loan-types.index')
            ->with('success', 'Loan Type created successfully.');
    }

    public function show(LoanType $loanType)
    {
        return view('admin.loan-types.show', compact('loanType'));
    }

    public function edit(LoanType $loanType)
    {
        return view('admin.loan-types.edit', compact('loanType'));
    }

    public function update(Request $request, LoanType $loanType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'status' => 'required|boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']), // Name update hone par slug bhi update hoga
            'description' => $validated['description'],
            'status' => $validated['status'],
        ];

        // File Update Logic (Purani file delete karein, nayi upload karein)
        if ($request->hasFile('icon')) {
            if ($loanType->icon_path) {
                Storage::disk('public')->delete($loanType->icon_path);
            }
            $data['icon_path'] = $request->file('icon')->store('loan_icons', 'public');
        }

        $loanType->update($data);

        return redirect()->route('loan-types.index')
            ->with('success', 'Loan Type updated successfully.');
    }

    public function destroy(LoanType $loanType)
    {
        // DB se delete karne se pehle storage se icon hata dein
        if ($loanType->icon_path) {
            Storage::disk('public')->delete($loanType->icon_path);
        }
        
        $loanType->delete();

        return redirect()->route('loan-types.index')
            ->with('success', 'Loan Type deleted successfully.');
    }
}