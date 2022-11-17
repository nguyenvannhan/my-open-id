<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ], true)) {
            if (session()->has('authorize_url')) {
                return redirect()->to(session()->get('authorize_url'));
            }
            return redirect()->route('home');
        }

        return back()->withErrors(['login' => 'Login Failed']);
    }
}
