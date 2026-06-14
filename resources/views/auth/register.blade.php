<x-layouts.auth title="Daftar — Tenebris">
    <div class="card border rounded-4 shadow-sm p-4 p-sm-5 w-100" style="max-width:420px;">
        <div class="d-flex align-items-center gap-2 text-primary fw-bold fs-4 mb-4">
            <i class="ri-centos-line fs-3"></i>
            Tenebris
        </div>
        <h1 class="fw-bold fs-4 mb-1">Buat akun baru</h1>
        <p class="text-secondary mb-4">Bergabung dengan Tenebris sekarang</p>

        @if(session('error'))
            <div class="alert alert-danger mb-3">
                <i class="ri-error-warning-line me-1"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" for="name">Username</label>
                <div class="input-group">
                    <span class="input-group-text fw-semibold">@</span>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Minimal 3 karakter, huruf & angka, maks 20"
                           value="{{ old('name') }}" >
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" for="email">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-mail-line"></i></span>
                    <input type="text" name="email" id="email"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="nama@Tenebris.ix"
                           value="{{ old('email') }}" >
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" for="password">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-lock-line"></i></span>
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Minimal 8 karakter, huruf & angka" >
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" for="password_confirmation">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-lock-2-line"></i></span>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           placeholder="Ulangi password" >
                    @error('password_confirmation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold mt-1">Buat Akun</button>
        </form>

        <p class="text-center mt-3 mb-0 text-secondary small">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Masuk</a>
        </p>
    </div>
</x-layouts.auth>
