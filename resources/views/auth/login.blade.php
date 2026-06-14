<x-layouts.auth title="Login — Tenebris">
    <div class="card border rounded-4 shadow-sm p-4 p-sm-5 w-100" style="max-width:420px;">
        <div class="d-flex align-items-center gap-2 text-primary fw-bold fs-4 mb-4">
            <i class="ri-centos-line fs-3"></i>
            Tenebris
        </div>
        <h1 class="fw-bold fs-4 mb-1">Masuk ke Tenebris</h1>
        <p class="text-secondary mb-4">Gunakan akun @Tenebris.ix kamu</p>

        @if(session('success'))
            <div class="alert alert-success mb-3">
                <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-3">
                <i class="ri-error-warning-line me-1"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" for="email">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-mail-line"></i></span>
                    <input type="text" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="nama@Tenebris.ix"
                           value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" for="password">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-lock-line"></i></span>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold mt-1">Masuk</button>
        </form>

        <p class="text-center mt-3 mb-0 text-secondary small">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">Daftar</a>
        </p>
    </div>
</x-layouts.auth>
