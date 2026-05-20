@extends('layouts.app')

@section('title', '購入画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
    <section class="purchase">
        <div class="purchase__inner">
            <form class="purchase__form" action="/purchase/{{ $item->id }}" method="post">
                @csrf

                <input type="hidden" name="postal_code" value="{{ old('postal_code', $address['postal_code'] ?? '') }}">
                <input type="hidden" name="address" value="{{ old('address', $address['address'] ?? '') }}">
                <input type="hidden" name="building" value="{{ old('building', $address['building'] ?? '') }}">

                <div class="purchase__content">
                    <div class="purchase__left">
                        <div class="purchase-item">
                            <div class="purchase-item__image-area">
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                                    class="purchase-item__image">
                            </div>

                            <div class="purchase-item__info">
                                <h1 class="purchase-item__name">{{ $item->name }}</h1>
                                <p class="purchase-item__price">¥ {{ number_format($item->price) }}</p>
                            </div>
                        </div>

                        <div class="purchase-section">
                            <h2 class="purchase-section__title">支払い方法</h2>

                            <select class="purchase-section__select" name="payment_method" id="payment_method">
                                <option value="" disabled hidden {{ old('payment_method') ? '' : 'selected' }}>選択してください
                                </option>
                                <option value="convenience_store" @selected(old('payment_method') === 'convenience_store')>
                                    コンビニ支払い
                                </option>
                                <option value="card" @selected(old('payment_method') === 'card')>
                                    カード支払い
                                </option>
                            </select>

                            @error('payment_method')
                                <p class="purchase-section__error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="purchase-section">
                            <div class="purchase-section__header">
                                <h2 class="purchase-section__title">配送先</h2>
                                <a href="/purchase/address/{{ $item->id }}" class="purchase-section__change-link">
                                    変更する
                                </a>
                            </div>

                            <div class="purchase-address">
                                <p class="purchase-address__postal">
                                    〒{{ old('postal_code', $address['postal_code'] ?? '') }}
                                </p>
                                <p class="purchase-address__text">
                                    {{ old('address', $address['address'] ?? '') }}
                                    {{ old('building', $address['building'] ?? '') }}
                                </p>
                            </div>

                            @if ($errors->has('postal_code'))
                                <p class="purchase-section__error-message">{{ $errors->first('postal_code') }}</p>
                            @elseif ($errors->has('address'))
                                <p class="purchase-section__error-message">{{ $errors->first('address') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="purchase__right">
                        <div class="purchase-summary">
                            <div class="purchase-summary__row">
                                <span class="purchase-summary__label">商品代金</span>
                                <span class="purchase-summary__value">¥{{ number_format($item->price) }}</span>
                            </div>

                            <div class="purchase-summary__row">
                                <span class="purchase-summary__label">支払い方法</span>
                                <span class="purchase-summary__value" id="payment_method_text">
                                    @if (old('payment_method') === 'convenience_store')
                                        コンビニ支払い
                                    @elseif (old('payment_method') === 'card')
                                        カード支払い
                                    @else
                                        選択してください
                                    @endif
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="purchase__submit-button">購入する</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethodSelect = document.getElementById('payment_method');
            const paymentMethodText = document.getElementById('payment_method_text');

            function updatePaymentMethodText() {
                if (paymentMethodSelect.value === 'convenience_store') {
                    paymentMethodText.textContent = 'コンビニ支払い';
                } else if (paymentMethodSelect.value === 'card') {
                    paymentMethodText.textContent = 'カード支払い';
                } else {
                    paymentMethodText.textContent = '選択してください';
                }
            }

            paymentMethodSelect.addEventListener('change', updatePaymentMethodText);
            updatePaymentMethodText();
        });
    </script>
@endsection
