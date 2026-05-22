<x-layouts.auth title="Login | Tenebris">

    {{-- Card Container --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">

            {{-- Header --}}
            <div class="mb-4">
                <h2 class="fw-bolder text-dark mb-1">Selamat Datang</h2>
                <p class="text-muted">Masuk ke akun Tenebris kamu</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" novalidate>
                @csrf

                <div class="vstack gap-3">
                    <x-forms.input
                        name="email"
                        label="Email"
                        type="email"
                        placeholder="Email kamu: ....@Tenebris.ix"
                    />

                    <x-forms.input
                        name="password"
                        label="Password"
                        type="password"
                        placeholder="Password kamu"
                    />

                    <div class="d-grid mt-2">
                        <x-forms.button color="primary" class="btn-lg fw-semibold">
                            <i class="ri-login-box-line me-2"></i> Masuk
                        </x-forms.button>
                    </div>
                </div>
            </form>

            {{-- Divider --}}
            <div class="d-flex align-items-center my-4">
                <hr class="flex-grow-1 border-secondary opacity-25">
                <span class="px-3 text-muted small text-uppercase fw-bold">atau</span>
                <hr class="flex-grow-1 border-secondary opacity-25">
            </div>

            {{-- Footer --}}
            <div class="text-center">
                <p class="text-muted small mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-bold ms-1">Daftar di sini</a>
                </p>
            </div>

        </div>
    </div>

</x-layouts.auth>

