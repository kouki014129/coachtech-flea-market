@if (request()->is('login') || request()->is('register') || request()->is('email/verify'))
    <header class="auth-header">
        <div class="auth-header__inner">
            <a href="/" class="auth-header__logo-link">
                <img
                    src="{{ asset('images/common/COACHTECHヘッダーロゴ.png') }}"
                    alt="COACHTECHロゴ"
                    class="auth-header__logo"
                >
            </a>
        </div>
    </header>
@else
    <header class="header">
        <div class="header__inner">
            <a href="/" class="header__logo-link">
                <img
                    src="{{ asset('images/common/COACHTECHヘッダーロゴ.png') }}"
                    alt="COACHTECHロゴ"
                    class="header__logo"
                >
            </a>

            <form action="/" method="get" class="header__search-form">
                <input
                    type="text"
                    name="keyword"
                    class="header__search-input"
                    placeholder="なにをお探しですか？"
                    value="{{ request('keyword') }}"
                >
            </form>

            <nav class="header__nav">
                @auth
                    <form action="/logout" method="post" class="header__logout-form">
                        @csrf
                        <button type="submit" class="header__nav-link header__logout-button header__auth-link">ログアウト</button>
                    </form>

                    <a href="/mypage" class="header__nav-link">マイページ</a>
                    <a href="/sell" class="header__sell-button">出品</a>
                @else
                    <a href="/login" class="header__nav-link header__auth-link">ログイン</a>
                    <a href="/mypage" class="header__nav-link">マイページ</a>
                    <a href="/sell" class="header__sell-button">出品</a>
                @endauth
            </nav>
        </div>
    </header>
@endif
