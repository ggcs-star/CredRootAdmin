<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    /**
     * Display a listing of banks.
     */
    public function index()
    {
        $banks = Bank::latest()->paginate(10);

        return view('admin.banks.index', compact('banks'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.banks.create');
    }

    /**
     * Store new bank.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',

            'min_loan_amount' => 'nullable|numeric|min:0',
            'max_loan_amount' => 'nullable|numeric|min:0',

            'interest_rate_from' => 'nullable|numeric|min:0|max:100',
            'interest_rate_to' => 'nullable|numeric|min:0|max:100',

            'max_tenure_months' => 'nullable|integer|min:1',

            'status' => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')
                ->store('banks', 'public');
        }

        Bank::create($data);

        return redirect()
            ->route('admin.banks.index')
            ->with('success', 'Bank created successfully.');
    }

    /**
     * Show single bank.
     */
    public function show(Bank $bank)
    {
        return view('admin.banks.show', compact('bank'));
    }

    /**
     * Show edit form.
     */
    public function edit(Bank $bank)
    {
        return view('admin.banks.edit', compact('bank'));
    }

    /**
     * Update bank.
     */
    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',

            'min_loan_amount' => 'nullable|numeric|min:0',
            'max_loan_amount' => 'nullable|numeric|min:0',

            'interest_rate_from' => 'nullable|numeric|min:0|max:100',
            'interest_rate_to' => 'nullable|numeric|min:0|max:100',

            'max_tenure_months' => 'nullable|integer|min:1',

            'status' => 'required|boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {

            if ($bank->logo && Storage::disk('public')->exists($bank->logo)) {
                Storage::disk('public')->delete($bank->logo);
            }

            $data['logo'] = $request->file('logo')
                ->store('banks', 'public');
        }

        $bank->update($data);

        return redirect()
            ->route('admin.banks.index')
            ->with('success', 'Bank updated successfully.');
    }

    /**
     * Delete bank.
     */
    public function destroy(Bank $bank)
    {
        if ($bank->logo && Storage::disk('public')->exists($bank->logo)) {
            Storage::disk('public')->delete($bank->logo);
        }

        $bank->delete();

        return redirect()
            ->route('admin.banks.index')
            ->with('success', 'Bank deleted successfully.');
    }
}
