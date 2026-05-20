@extends('layouts.app')

@section('title', '会員登録')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
    <section class="register">
        <div class="register__inner">
            <h1 class="register__title">会員登録</h1>
            <form action="/register" method="post" class="register__form">
                @csrf
                <div class="register__form-group">
                    <label for="name" class="register__label">ユーザー名</label>
                    <input type="text" name="name" id="name" class="register__input" value="{{ old('name') }}" >
                    @error('name')
                        <div class="register__error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register__form-group">
                    <label for="email" class="register__label">メールアドレス</label>
                    <input type="email" name="email" id="email" class="register__input" value="{{ old('email') }}" >
                    @error('email')
                        <div class="register__error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register__form-group">
                    <label for="password" class="register__label">パスワード</label>
                    <input type="password" name="password" id="password" class="register__input" >
                    @error('password')
                        <div class="register__error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="register__form-group">
                    <label for="password_confirmation" class="register__label">確認用パスワード</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="register__input" >
                    @error('password_confirmation')
                        <div class="register__error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="register__submit-button">登録する</button>
            </form>
            <div class="register__login-link-wrapper">
               <a href="/login" class="register__login-link">ログインはこちら</a>
            </div>
        </div>
    </section>
@endsection