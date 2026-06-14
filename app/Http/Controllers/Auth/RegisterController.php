<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function index(): View {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => $validated['password'],
            ]);
            $user->profile()->create([]);
        });

        return to_route('login')->with('success', 'Akun berhasil dibuat! Silakan masuk.');
    }
}
