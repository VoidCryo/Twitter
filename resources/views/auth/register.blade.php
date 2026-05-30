<x-layouts.auth title="Daftar — Tenebris">
    <div class="auth-card">
        <div class="auth-logo">
            <i class="ri-twitter-x-line"></i>
        </div>
        <h1 class="auth-title">Buat akun baru</h1>
        <p class="auth-subtitle">Bergabung dengan Tenebris sekarang</p>

        @if(session('failed'))
            <div class="alert alert-danger mb-3">
                <i class="ri-error-warning-line me-1"></i>{{ session('failed') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.user') }}">
            @csrf

            <x-forms.input
                name="name"
                label="Username"
                type="text"
                placeholder="Minimal 3 karakter, huruf & angka"
                icon="ri-user-line"
                :required="true"
            />

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
                placeholder="Minimal 8 karakter, huruf & angka"
                icon="ri-lock-line"
                :required="true"
            />

            <x-forms.input
                name="password_confirmation"
                label="Konfirmasi Password"
                type="password"
                placeholder="Ulangi password"
                icon="ri-lock-2-line"
                :required="true"
            />

            <x-forms.button type="submit" variant="brand" class="mt-1">
                Buat Akun
            </x-forms.button>
        </form>

        <p class="text-center mt-3 mb-0" style="color:#536471;font-size:.9rem">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:#1d9bf0;font-weight:600;text-decoration:none">Masuk</a>
        </p>
    </div>
</x-layouts.auth>
