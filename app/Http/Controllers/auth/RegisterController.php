<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse {
        $validated = $request->validated();
        if (!User::create($validated)) {
            return back()->with('failed', 'gagal membuat akun');
        }
        return to_route('login')->with('success', 'berhasil membuat akun');
    }
}
