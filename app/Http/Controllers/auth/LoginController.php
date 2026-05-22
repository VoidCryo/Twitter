<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return back()->with('failed', 'gagal login ke akun');
        }
        $request->session()->regenerate();
        return to_route('home')->with('success', 'berhasil login ke akun');
    }

    public function logout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('login')->with('success', 'berhasil logout dari akun');
    }
}
