<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Spatie ka in-built scope "role('user')" use karke sirf customers fetch karo
        $query = User::role('user');

        // Search Functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(20);
        $totalCustomers = User::role('user')->count();

        return view('admin.customers.index', compact('customers', 'totalCustomers'));
    }
    public function show($id)
    {
        // Get user only if they have the 'user' role
        $customer = User::role('user')
            ->with(['profile', 'sessions' => function($q) {
                $q->latest('last_used_at')->take(5); // Get recent sessions for security view
            }])
            ->findOrFail($id);
            
        // Get active lead and company info
        $activeLead = \App\Models\Lead::where('user_id', $customer->id)->latest()->first();
        $company = \App\Models\Company::with('members')->where('user_id', $customer->id)->first();

        return view('admin.customers.show', compact('customer', 'activeLead', 'company'));
    }
}