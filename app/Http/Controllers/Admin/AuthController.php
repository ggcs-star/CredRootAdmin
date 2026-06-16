<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->with('error', 'Invalid credentials');
        }

        $user = Auth::user();

        if (!$user->hasRole('admin')) {

            Auth::logout();

            return back()->with('error', 'Unauthorized');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard.index');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('admin.login');
    }
}