@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <section class="login">
        <div class="login__inner">
            <h1 class="login__title">ログイン</h1>

            <form action="/login" method="post" class="login__form">
                @csrf

                <div class="login__form-group">
                    <label for="email" class="login__label">メールアドレス</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="login__input"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <div class="login__error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="login__form-group">
                    <label for="password" class="login__label">パスワード</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="login__input"
                    >
                    @error('password')
                        <div class="login__error-message">{{ $message }}</div>
                    @enderror
                </div>

                @if (session('errors'))
                    @error('login')
                        <div class="login__error-message">{{ $message }}</div>
                    @enderror
                @endif

                <button type="submit" class="login__submit-button">ログインする</button>
            </form>

            <div class="login__register-link-wrapper">
                <a href="/register" class="login__register-link">会員登録はこちら</a>
            </div>
        </div>
    </section>
@endsection