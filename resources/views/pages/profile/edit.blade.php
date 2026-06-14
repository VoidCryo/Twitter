<x-layouts.app title="Edit Profil · Tenebris">

<div class="mx-auto" style="max-width:600px;">
    {{-- Header --}}
    <div class="sticky-top bg-white border-bottom d-flex align-items-center justify-content-between px-3 py-2" style="top:52px;z-index:89;min-height:52px;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('profile', $user) }}" class="text-dark fs-5 text-decoration-none">
                <i class="ri-close-line"></i>
            </a>
            <span class="fw-bold fs-5">Edit Profil</span>
        </div>
        <button type="submit" form="editProfileForm" class="btn btn-dark rounded-pill btn-sm px-4 fw-semibold">Simpan</button>
    </div>

    <form id="editProfileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        @if($errors->any())
            <div class="alert alert-danger mx-3 mt-3">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Banner --}}
        <div class="position-relative overflow-hidden" style="height:130px;background:linear-gradient(135deg,#1d9bf0,#0f59a4);cursor:pointer;"
             onclick="document.getElementById('inputBanner').click()">
            @if($user->profile?->banner)
                <img src="{{ Storage::url($user->profile->banner) }}" class="w-100 h-100 object-fit-cover" id="bannerPreview" alt="banner">
            @else
                <div id="bannerPreview" class="w-100 h-100"></div>
            @endif
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-black bg-opacity-50">
                <i class="ri-camera-line text-white fs-3"></i>
            </div>
            <input type="file" id="inputBanner" name="banner" class="d-none" accept="image/*">
        </div>

        {{-- Avatar --}}
        <div class="position-relative rounded-circle border border-3 border-white overflow-hidden ms-3"
             style="width:76px;height:76px;margin-top:-38px;cursor:pointer;"
             onclick="document.getElementById('inputAvatar').click()">
            @if($user->profile?->avatar)
                <img src="{{ Storage::url($user->profile->avatar) }}" class="w-100 h-100 object-fit-cover" id="avatarPreview" alt="avatar">
            @else
                <div class="w-100 h-100 bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center fw-bold text-secondary fs-4" id="avatarInitial">
                    {{ strtoupper(substr($user->profile?->display_name ?? $user->name, 0, 1)) }}
                </div>
            @endif
            <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-black bg-opacity-50">
                <i class="ri-camera-line text-white"></i>
            </div>
            <input type="file" id="inputAvatar" name="avatar" class="d-none" accept="image/*">
        </div>

        <div class="mt-3">
            {{-- Handle @ (read-only, tidak bisa diedit) --}}
            <div class="px-3 py-3 border-bottom bg-light bg-opacity-50">
                <label class="form-label text-secondary fw-semibold small mb-1">Username (tidak bisa diubah)</label>
                <div class="d-flex align-items-center gap-1 text-secondary">
                    <span class="fw-semibold">@</span>
                    <span class="fw-semibold">{{ $user->name }}</span>
                </div>
            </div>

            {{-- Display Name --}}
            <div class="px-3 py-3 border-bottom">
                <label class="form-label text-primary fw-semibold small" for="inputDisplayName">Nama Tampilan</label>
                <input type="text" class="form-control border-0 border-bottom rounded-0 px-0 shadow-none bg-transparent @error('display_name') is-invalid @enderror"
                       id="inputDisplayName" name="display_name"
                       value="{{ old('display_name', $user->profile?->display_name) }}"
                       maxlength="50" placeholder="Nama bebas kamu">
                @error('display_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="text-end text-secondary small mt-1"><span id="displayNameCount">{{ strlen($user->profile?->display_name ?? '') }}</span>/50</div>
            </div>

            {{-- Bio --}}
            <div class="px-3 py-3 border-bottom">
                <label class="form-label text-primary fw-semibold small" for="inputBio">Bio</label>
                <textarea class="form-control border-0 border-bottom rounded-0 px-0 shadow-none bg-transparent"
                          id="inputBio" name="bio" rows="3"
                          maxlength="160" placeholder="Ceritakan tentang dirimu">{{ old('bio', $user->profile?->bio) }}</textarea>
                <div class="text-end text-secondary small mt-1"><span id="bioCount">{{ strlen($user->profile?->bio ?? '') }}</span>/160</div>
            </div>

            {{-- Location --}}
            <div class="px-3 py-3 border-bottom">
                <label class="form-label text-primary fw-semibold small" for="inputLocation">Lokasi</label>
                <input type="text" class="form-control border-0 border-bottom rounded-0 px-0 shadow-none bg-transparent"
                       id="inputLocation" name="location"
                       value="{{ old('location', $user->profile?->location) }}"
                       maxlength="30" placeholder="Lokasi kamu">
            </div>

            {{-- Birthday --}}
            <div class="px-3 py-3 border-bottom">
                <label class="form-label text-primary fw-semibold small" for="inputBirthday">Tanggal Lahir</label>
                <input type="date" class="form-control border-0 border-bottom rounded-0 px-0 shadow-none bg-transparent"
                       id="inputBirthday" name="birthday"
                       value="{{ old('birthday', $user->profile?->birthday ? \Carbon\Carbon::parse($user->profile->birthday)->format('Y-m-d') : '') }}">
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
document.getElementById('inputAvatar').addEventListener('change', function() {
    if (this.files[0]) {
        const url = URL.createObjectURL(this.files[0]);
        let prev = document.getElementById('avatarPreview') || document.getElementById('avatarInitial');
        if (prev.tagName === 'DIV') {
            const img = document.createElement('img');
            img.id = 'avatarPreview';
            img.className = 'w-100 h-100 object-fit-cover';
            img.src = url;
            prev.replaceWith(img);
        } else { prev.src = url; }
    }
});
document.getElementById('inputBanner').addEventListener('change', function() {
    if (this.files[0]) {
        const url = URL.createObjectURL(this.files[0]);
        let prev = document.getElementById('bannerPreview');
        if (prev.tagName === 'DIV') {
            const img = document.createElement('img');
            img.id = 'bannerPreview';
            img.className = 'w-100 h-100 object-fit-cover';
            img.src = url;
            prev.replaceWith(img);
        } else { prev.src = url; }
    }
});
document.getElementById('inputDisplayName').addEventListener('input', function() {
    document.getElementById('displayNameCount').textContent = this.value.length;
});
document.getElementById('inputBio').addEventListener('input', function() {
    document.getElementById('bioCount').textContent = this.value.length;
});
</script>
@endpush

</x-layouts.app>
