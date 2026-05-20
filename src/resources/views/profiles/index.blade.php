@extends('layouts.app')

@section('title', 'プロフィール')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
    <section class="profile">
        <div class="profile__inner">
            <div class="profile__user">
                <div class="profile__image-wrapper">
                    @if ($user->profile_image)
                        <img
                            src="{{ asset('storage/' . $user->profile_image) }}"
                            alt="{{ $user->name }}"
                            class="profile__image"
                        >
                    @endif
                </div>

                <h1 class="profile__name">{{ $user->name }}</h1>

                <a href="/mypage/profile" class="profile__edit-button">
                    プロフィールを編集
                </a>
            </div>

            <div class="profile__tab-area">
                <a
                    href="/mypage?page=sell"
                    class="profile__tab {{ request('page') !== 'buy' ? 'profile__tab--active' : '' }}"
                >
                    出品した商品
                </a>

                <a
                    href="/mypage?page=buy"
                    class="profile__tab {{ request('page') === 'buy' ? 'profile__tab--active' : '' }}"
                >
                    購入した商品
                </a>
            </div>

            <div class="profile__content">
                <div class="profile__item-list">
                    @if (request('page') === 'buy')
                        @foreach ($buyItems as $item)
                            <article class="profile-item">
                                <a href="/item/{{ $item->id }}" class="profile-item__link">
                                    <div class="profile-item__image-wrapper">
                                        <img
                                            src="{{ asset('storage/' . $item->image) }}"
                                            alt="{{ $item->name }}"
                                            class="profile-item__image"
                                        >
                                    </div>

                                    <p class="profile-item__name">{{ $item->name }}</p>
                                </a>
                            </article>
                        @endforeach
                    @else
                        @foreach ($sellItems as $item)
                            <article class="profile-item">
                                <a href="/item/{{ $item->id }}" class="profile-item__link">
                                    <div class="profile-item__image-wrapper">
                                        <img
                                            src="{{ asset('storage/' . $item->image) }}"
                                            alt="{{ $item->name }}"
                                            class="profile-item__image"
                                        >
                                    </div>

                                    <p class="profile-item__name">{{ $item->name }}</p>
                                </a>
                            </article>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection