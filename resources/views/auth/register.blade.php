<x-layouts.auth title="Register | Tenebris">

    {{-- Card Container --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">

            {{-- Header --}}
            <div class="mb-4">
                <h2 class="fw-bolder text-dark mb-1">Buat Akun</h2>
                <p class="text-muted">Lengkapi data berikut untuk bergabung dengan Tenebris.</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('register') }}" method="POST" novalidate>
                @csrf

                <div class="vstack gap-3">
                    <x-forms.input name="name" label="Username" placeholder="Contoh: VoidCryo" />
                    <x-forms.input name="email" label="Email" type="email" placeholder="Void@Tenebris.ix" />
                    <x-forms.input name="password" label="Password" type="password" placeholder="Min. 8 karakter" />
                    <x-forms.input name="password_confirmation" label="Konfirmasi Password" type="password" placeholder="Ulangi password Anda" />

                    <div class="d-grid mt-2">
                        <x-forms.button color="primary" class="btn-lg fw-semibold">
                            <i class="ri-user-add-line me-2"></i> Daftar Sekarang
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
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-bold ms-1">Masuk di sini</a>
                </p>
            </div>

        </div>
    </div>

</x-layouts.auth>

