@extends('layouts.app')

@section('title', 'メール認証')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
    <section class="verify-email">
        <h1 class="visually-hidden">メール認証</h1>

        <div class="verify-email__inner">
            <p class="verify-email__message">
                登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。
            </p>

            @if (session('status') === 'verification-link-sent')
                <p class="verify-email__notice">
                    認証メールを再送しました。
                </p>
            @endif

            <a href="http://localhost:8025" class="verify-email__button" target="_blank">
                認証はこちらから
            </a>

            <form action="{{ route('verification.send') }}" method="post" class="verify-email__form">
                @csrf
                <button type="submit" class="verify-email__resend-button">
                    認証メールを再送する
                </button>
            </form>
        </div>
    </section>
@endsection
