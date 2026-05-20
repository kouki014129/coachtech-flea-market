@extends('layouts.app')

@section('title', '商品詳細')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
    <section class="item-detail">
        <div class="item-detail__inner">
            <div class="item-detail__content">
                <div class="item-detail__image-area">
                    <div class="item-detail__image-placeholder">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="item-detail__image">
                    </div>
                </div>

                <div class="item-detail__info-area">
                    <h1 class="item-detail__name">{{ $item->name }}</h1>
                    <p class="item-detail__brand">{{ $item->brand_name }}</p>

                    <p class="item-detail__price">
                        <span class="item-detail__price-symbol">¥</span>{{ number_format($item->price) }}
                        <span class="item-detail__price-tax">（税込）</span>
                    </p>

                    <div class="item-detail__action-area">
                        <div class="item-detail__reaction-group">
                            <div class="item-detail__reaction">
                                @auth
                                    <form action="/item/{{ $item->id }}/like" method="post" class="item-detail__icon-form">
                                        @csrf
                                        <button type="submit" class="item-detail__icon-button">
                                            <img src="{{ asset($isLiked ? 'images/common/ハートロゴ_ピンク.png' : 'images/common/ハートロゴ_デフォルト.png') }}"
                                                alt="いいね" class="item-detail__icon-image">
                                        </button>
                                    </form>
                                @else
                                    <a href="/login" class="item-detail__icon-button">
                                        <img src="{{ asset('images/common/ハートロゴ_デフォルト.png') }}" alt="いいね"
                                            class="item-detail__icon-image">
                                    </a>
                                @endauth

                                <span class="item-detail__count">{{ $likesCount }}</span>
                            </div>

                            <div class="item-detail__reaction">
                                <button type="button" class="item-detail__icon-button">
                                    <img src="{{ asset('images/common/ふきだしロゴ.png') }}" alt="コメント"
                                        class="item-detail__icon-image">
                                </button>
                                <span class="item-detail__count">{{ $commentsCount }}</span>
                            </div>
                        </div>

                        <a href="/purchase/{{ $item->id }}" class="item-detail__purchase-button">
                            購入手続きへ
                        </a>
                    </div>

                    <section class="item-detail__section">
                        <h2 class="item-detail__section-title">商品説明</h2>
                        <p class="item-detail__text">{{ $item->description }}</p>
                    </section>

                    <section class="item-detail__section">
                        <h2 class="item-detail__section-title">商品の情報</h2>

                        <div class="item-detail__meta-row">
                            <p class="item-detail__meta-label">カテゴリー</p>
                            <div class="item-detail__category-list">
                                @foreach ($item->categories as $category)
                                    <span class="item-detail__category-tag">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>

                        <div class="item-detail__meta-row">
                            <p class="item-detail__meta-label">商品の状態</p>
                            <p class="item-detail__meta-value">{{ $item->condition }}</p>
                        </div>
                    </section>

                    <section class="item-detail__section">
                        <h2 class="item-detail__comment-title">コメント({{ $commentsCount }})</h2>

                        @forelse ($item->comments as $comment)
                            <div class="item-detail__comment-item">
                                <div class="item-detail__comment-user">
                                    <div class="item-detail__comment-user-icon">
                                        @if ($comment->user->profile_image)
                                            <img
                                                src="{{ asset('storage/' . $comment->user->profile_image) }}"
                                                alt="{{ $comment->user->name }}"
                                                class="item-detail__comment-user-image"
                                                width="70"
                                                height="70"
                                            >
                                        @endif
                                    </div>
                                    <p class="item-detail__comment-user-name">{{ $comment->user->name }}</p>
                                </div>

                                <div class="item-detail__comment-body">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        @empty
                            <p class="item-detail__no-comment">まだコメントはありません。</p>
                        @endforelse
                    </section>

                    <section class="item-detail__section">
                        <h2 class="item-detail__section-title">商品へのコメント</h2>

                        @auth
                            <form action="/item/{{ $item->id }}/comment" method="post" class="item-detail__comment-form">
                                @csrf

                                <textarea name="content" class="item-detail__comment-textarea">{{ old('content') }}</textarea>

                                @error('content')
                                    <p class="item-detail__error-message">{{ $message }}</p>
                                @enderror

                                <button type="submit" class="item-detail__comment-submit">
                                    コメントを送信する
                                </button>
                            </form>
                        @else
                            <div class="item-detail__comment-form">
                                <textarea class="item-detail__comment-textarea" readonly></textarea>

                                <a href="/login" class="item-detail__comment-submit item-detail__comment-submit--link">
                                    コメントを送信する
                                </a>
                            </div>
                        @endauth
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection
