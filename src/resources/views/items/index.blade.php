@extends('layouts.app')

@section('title', '商品一覧')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <section class="item-index">
        <h1 class="visually-hidden">商品一覧</h1>

        <div class="item-index__tab-area">
            <a
                href="{{ url('/') . '?' . http_build_query(['keyword' => request('keyword')]) }}"
                class="item-index__tab {{ request('tab') !== 'mylist' ? 'item-index__tab--active' : '' }}"
            >
                おすすめ
            </a>

            <a
                href="{{ url('/') . '?' . http_build_query(['tab' => 'mylist', 'keyword' => request('keyword')]) }}"
                class="item-index__tab {{ request('tab') === 'mylist' ? 'item-index__tab--active' : '' }}"
            >
                マイリスト
            </a>
        </div>

        <div class="item-index__content">
            <div class="item-index__list">
                @foreach ($items as $item)
                    <article class="item-card">
                        <a href="/item/{{ $item->id }}" class="item-card__link">
                            <div class="item-card__image-wrapper">
                                <img
                                    src="{{ asset('storage/' . $item->image) }}"
                                    alt="{{ $item->name }}"
                                    class="item-card__image"
                                >

                                @if ($item->purchase)
                                    <span class="item-card__sold">Sold</span>
                                @endif
                            </div>

                            <p class="item-card__name">{{ $item->name }}</p>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
