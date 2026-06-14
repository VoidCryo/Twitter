<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Email atau password salah.');
        }
        $request->session()->regenerate();
        return to_route('home')->with('success', 'Berhasil masuk!');
    }

    public function destroy(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('login')->with('success', 'Berhasil logout.');
    }
}
