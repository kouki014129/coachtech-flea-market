@extends('layouts.app')

@section('title', '商品の出品')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
    <section class="sell">
        <div class="sell__inner">
            <h1 class="sell__title">商品の出品</h1>

            <form action="/sell" method="post" enctype="multipart/form-data" class="sell__form">
                @csrf

                <div class="sell__form-group">
                    <label class="sell__label">商品画像</label>

                    <div class="sell-image">
                        <div class="sell-image__preview" id="image_preview"></div>

                        <label for="image" class="sell-image__button">画像を選択する</label>
                        <input
                            type="file"
                            name="image"
                            id="image"
                            class="sell-image__input"
                            accept="image/png,image/jpeg"
                        >
                    </div>

                    @error('image')
                        <p class="sell__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="sell-section">
                    <h2 class="sell-section__title">商品の詳細</h2>

                    <div class="sell__form-group">
                        <label class="sell__label">カテゴリー</label>

                        <div class="sell-category">
                            @foreach ($categories as $category)
                                <label class="sell-category__label">
                                    <input
                                        type="checkbox"
                                        name="categories[]"
                                        value="{{ $category->id }}"
                                        class="sell-category__input"
                                        {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                    >
                                    <span class="sell-category__button">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>

                        @error('categories')
                            <p class="sell__error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sell__form-group">
                        <label for="condition" class="sell__label">商品の状態</label>

                        <select name="condition" id="condition" class="sell__select">
                            <option value="" disabled {{ old('condition') ? '' : 'selected' }} hidden>選択してください</option>
                            <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                            <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                            <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                            <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                        </select>

                        @error('condition')
                            <p class="sell__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="sell-section">
                    <h2 class="sell-section__title">商品名と説明</h2>

                    <div class="sell__form-group">
                        <label for="name" class="sell__label">商品名</label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="sell__input"
                            value="{{ old('name') }}"
                        >
                        @error('name')
                            <p class="sell__error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sell__form-group">
                        <label for="brand_name" class="sell__label">ブランド名</label>
                        <input
                            type="text"
                            name="brand_name"
                            id="brand_name"
                            class="sell__input"
                            value="{{ old('brand_name') }}"
                        >
                        @error('brand_name')
                            <p class="sell__error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sell__form-group">
                        <label for="description" class="sell__label">商品の説明</label>
                        <textarea
                            name="description"
                            id="description"
                            class="sell__textarea"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="sell__error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sell__form-group">
                        <label for="price" class="sell__label">販売価格</label>

                        <div class="sell-price">
                            <span class="sell-price__mark">¥</span>
                            <input
                                type="text"
                                name="price"
                                id="price"
                                class="sell-price__input"
                                value="{{ old('price') }}"
                            >
                        </div>

                        @error('price')
                            <p class="sell__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="sell__submit-button">出品する</button>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('image');
        const preview = document.getElementById('image_preview');

        if (!input || !preview) return;

        input.addEventListener('change', (event) => {
            const [file] = event.target.files || [];

            if (!file) return;

            preview.innerHTML = '';

            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.className = 'sell-image__preview-image';
            image.alt = '商品画像プレビュー';

            preview.appendChild(image);
        });
    });
</script>
@endpush
