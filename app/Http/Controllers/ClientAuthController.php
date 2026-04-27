<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ClientAuthController extends Controller
{
    public function showLogin()
    {
        return view('client-auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::guard('client')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $account = Auth::guard('client')->user();
            if (!$account->is_active) {
                Auth::guard('client')->logout();
                throw ValidationException::withMessages([
                    'email' => 'هذا الحساب غير مفعل',
                ]);
            }

            return redirect()->route('client.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'بيانات الدخول غير صحيحة',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }
}

