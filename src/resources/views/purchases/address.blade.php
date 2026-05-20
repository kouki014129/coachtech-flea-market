@extends('layouts.app')

@section('title', '住所の変更')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
    <section class="purchase-address-edit">
        <div class="purchase-address-edit__inner">
            <h1 class="purchase-address-edit__title">住所の変更</h1>

            <form
                class="purchase-address-edit__form"
                action="/purchase/address/{{ $item->id }}"
                method="post"
            >
                @csrf
                @method('put')

                <div class="purchase-address-edit__form-group">
                    <label for="postal_code" class="purchase-address-edit__label">郵便番号</label>
                    <input
                        type="text"
                        name="postal_code"
                        id="postal_code"
                        class="purchase-address-edit__input"
                        value="{{ old('postal_code', $address['postal_code'] ?? '') }}"
                    >

                    @error('postal_code')
                        <p class="purchase-address-edit__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="purchase-address-edit__form-group">
                    <label for="address" class="purchase-address-edit__label">住所</label>
                    <input
                        type="text"
                        name="address"
                        id="address"
                        class="purchase-address-edit__input"
                        value="{{ old('address', $address['address'] ?? '') }}"
                    >

                    @error('address')
                        <p class="purchase-address-edit__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="purchase-address-edit__form-group">
                    <label for="building" class="purchase-address-edit__label">建物名</label>
                    <input
                        type="text"
                        name="building"
                        id="building"
                        class="purchase-address-edit__input"
                        value="{{ old('building', $address['building'] ?? '') }}"
                    >

                    @error('building')
                        <p class="purchase-address-edit__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="purchase-address-edit__submit-button">
                    更新する
                </button>
            </form>
        </div>
    </section>
@endsection
