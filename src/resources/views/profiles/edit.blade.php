@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <section class="profile-edit">
        <div class="profile-edit__inner">
            <h1 class="profile-edit__title">プロフィール設定</h1>

            <form action="/mypage/profile" method="post" enctype="multipart/form-data" class="profile-edit__form">
                @csrf
                @method('put')

                <div class="profile-edit__image-area">
                    <div class="profile-edit__image-preview">
                        <img
                            id="profile_image_preview"
                            @if ($user->profile_image)
                                src="{{ asset('storage/' . $user->profile_image) }}"
                                alt="プロフィール画像"
                            @else
                                alt=""
                                hidden
                            @endif
                            class="profile-edit__image {{ $user->profile_image ? '' : 'profile-edit__image--hidden' }}"
                        >
                    </div>

                    <div class="profile-edit__image-button-area">
                        <label for="profile_image" class="profile-edit__image-button">画像を選択する</label>
                        <input type="file" name="profile_image" id="profile_image" class="profile-edit__image-input"
                            accept=".jpeg,.jpg,.png">

                        @error('profile_image')
                            <p class="profile-edit__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="profile-edit__form-group">
                    <label for="name" class="profile-edit__label">ユーザー名</label>
                    <input type="text" name="name" id="name" class="profile-edit__input"
                        value="{{ old('name', $user->name) }}">
                    @error('name')
                        <p class="profile-edit__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit__form-group">
                    <label for="postal_code" class="profile-edit__label">郵便番号</label>
                    <input type="text" name="postal_code" id="postal_code" class="profile-edit__input"
                        value="{{ old('postal_code', $user->postal_code) }}">
                    @error('postal_code')
                        <p class="profile-edit__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit__form-group">
                    <label for="address" class="profile-edit__label">住所</label>
                    <input type="text" name="address" id="address" class="profile-edit__input"
                        value="{{ old('address', $user->address) }}">
                    @error('address')
                        <p class="profile-edit__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit__form-group">
                    <label for="building" class="profile-edit__label">建物名</label>
                    <input type="text" name="building" id="building" class="profile-edit__input"
                        value="{{ old('building', $user->building) }}">
                    @error('building')
                        <p class="profile-edit__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="profile-edit__submit-button">更新する</button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('profile_image');
            const preview = document.getElementById('profile_image_preview');

            if (!input || !preview) return;

            let previewUrl = null;

            input.addEventListener('change', (event) => {
                const [file] = event.target.files || [];

                if (!file) return;

                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                }

                previewUrl = URL.createObjectURL(file);
                preview.src = previewUrl;
                preview.alt = 'プロフィール画像';
                preview.removeAttribute('hidden');
                preview.classList.remove('profile-edit__image--hidden');
            });
        });
    </script>
@endpush
