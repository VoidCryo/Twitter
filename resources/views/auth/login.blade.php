<x-layouts.auth title="Login — Tenebris">
    <div class="auth-card">
        <div class="auth-logo">
            <i class="ri-twitter-x-line"></i>
        </div>
        <h1 class="auth-title">Masuk ke Tenebris</h1>
        <p class="auth-subtitle">Gunakan akun @Tenebris.ix kamu</p>

        @if(session('success'))
            <div class="alert alert-success mb-3">
                <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('failed'))
            <div class="alert alert-danger mb-3">
                <i class="ri-error-warning-line me-1"></i>{{ session('failed') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.user') }}">
            @csrf

            <x-forms.input
                name="email"
                label="Email"
                type="email"
                placeholder="nama@Tenebris.ix"
                icon="ri-mail-line"
                :required="true"
            />

            <x-forms.input
                name="password"
                label="Password"
                type="password"
                placeholder="Minimal 8 karakter"
                icon="ri-lock-line"
                :required="true"
            />

            <x-forms.button type="submit" variant="brand" class="mt-1">
                Masuk
            </x-forms.button>
        </form>

        <p class="text-center mt-3 mb-0" style="color:#536471;font-size:.9rem">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color:#1d9bf0;font-weight:600;text-decoration:none">Daftar</a>
        </p>
    </div>
</x-layouts.auth>
