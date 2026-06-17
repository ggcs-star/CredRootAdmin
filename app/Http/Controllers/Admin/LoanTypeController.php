<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanType;
use Illuminate\Http\Request;

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
            'status' => 'required|boolean',
        ]);

        LoanType::create($validated);

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
            'status' => 'required|boolean',
        ]);

        $loanType->update($validated);

        return redirect()->route('loan-types.index')
            ->with('success', 'Loan Type updated successfully.');
    }

    public function destroy(LoanType $loanType)
    {
        $loanType->delete();

        return redirect()->route('loan-types.index')
            ->with('success', 'Loan Type deleted successfully.');
    }
}