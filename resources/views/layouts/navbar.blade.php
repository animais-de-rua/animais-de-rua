<nav id="navbar">
    <div id="loading"></div>
    <div class="container">
        <div class="left">
            <div class="logo">
                <a class="link" href="/" aria-label="{{ __("Home") }}">
                    @svg('img/logo/logo-text.svg', '', 'max-width: 230px; max-height: 36px;')
                </a>
            </div>

            <ul class="list">
                <li><a class="link" href="/association">{{ __("Organization") }}</a></li>
                <li><a class="link" href="/ced">{{ __("CED") }}</a></li>
                <li><a class="link" href="/animals">{{ __("Animals") }}</a></li>
                <li><a class="link" href="/help">{{ __("Help") }}</a></li>
                <li><a class="link" href="/partners">{{ __("Partners") }}</a></li>
            </ul>
        </div>

        <div class="cards">
            <div class="languages">
                @component('components.flagpicker')
                @endcomponent
            </div>

            <a href="{{ url('/store') }}">
                <div class="card" onclick="app.track('ViewContent', {'card': 'store'})">
                    <div class="icon icon-cart"></div>
                    <p>{{ __("Store") }}</p>
                </div>
            </a>
            <div class="card" onclick="navbar.onCardClick(this, 0)">
                <div class="icon icon-card"></div>
                <p>{{ __("friend card") }}</p>
                <div class="menu-panel">
                    @component('components.friend_card')
                    @endcomponent
                </div>
            </div>
            <div class="card" onclick="navbar.onCardClick(this, 1)">
                <div class="icon icon-donate"></div>
                <p>{{ __("Donate") }}</p>
                <div class="menu-panel">
                    @component('components.donate')
                    @endcomponent
                </div>
            </div>
        </div>

        <div class="menu" onclick="navbar.onMobileMenuClick(this)">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="swipeable">
        <div class="mobile touchable" max-x="0">
            <ul class="list">
                <li><a class="link" href="/">{{ __("Home") }}</a></li>
                <li><a class="link" href="/association">{{ __("Organization") }}</a></li>
                <li><a class="link" href="/ced">{{ __("CED") }}</a></li>
                <li><a class="link" href="/animals">{{ __("Animals") }}</a></li>
                <li><a class="link" href="/help">{{ __("Help") }}</a></li>
                <li><a class="link" href="/partners">{{ __("Partners") }}</a></li>
                <li><a class="link" href="/store">{{ __("Store") }}</a></li>
            </ul>

            <div class="cards">
                <div class="languages">
                    @component('components.flagpicker')
                    @endcomponent
                </div>
                <div class="card" onclick="navbar.onMobileCardClick(0)">
                    <div class="icon icon-card" style="margin-right: 8px"></div>
                    <p>{!! str_replace(" ", "<br />", __("friend card")) !!}</p>
                </div>
                <div class="card" onclick="navbar.onMobileCardClick(1)">
                    <div class="icon icon-donate" style="margin-right: 8px"></div>
                    <p>{{ __("Donate") }}</p>
                </div>
            </div>

            <div class="mobile-card-view touchable" min-y="0">
                <div class="menu-panel">
                    <div class="icon icon-card" style="margin:8px 0"></div>
                    @component('components.friend_card')
                    @endcomponent
                </div>
                <div class="menu-panel">
                    <div class="icon icon-donate" style="margin:8px 0"></div>
                    @component('components.donate')
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
</nav>
