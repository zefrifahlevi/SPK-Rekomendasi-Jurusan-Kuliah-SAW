<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isGuruBk()
                ? redirect()->route('guru.dashboard')
                : redirect()->route('siswa.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $credentials['login_id'];
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($loginInput) ? 'nisn' : 'email');

        if (Auth::attempt([$field => $loginInput, 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isGuruBk()) {
                return redirect()->route('guru.dashboard')->with('success', 'Selamat datang kembali, ' . $user->name);
            } else {
                return redirect()->route('siswa.dashboard')->with('success', 'Selamat datang kembali, ' . $user->name);
            }
        }

        return back()->withErrors([
            'login_id' => 'Email / NISN atau password yang Anda masukkan salah.',
        ])->onlyInput('login_id');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
