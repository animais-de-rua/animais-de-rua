<nav class="navbar">
    <div id="loading"></div>
    <div class="container">
        <div class="left">
            <div class="logo">
                <a href="{{ url('/') }}">
                    @svg('img/logo/logo-text.svg', '', 'max-width: 230px; max-height: 36px;')
                </a>
            </div>

            <ul class="list">
                <li><a class="link" href="/association">{{ __("The Organization") }}</a></li>
                <li><a class="link" href="/ced">{{ __("CED") }}</a></li>
                <li><a class="link" href="/animals">{{ __("Animals") }}</a></li>
                <li><a class="link" href="/help">{{ __("Help") }}</a></li>
                <li><a class="link" href="/partners">{{ __("Partners") }}</a></li>
            </ul>
        </div>

        <div class="cards">
            <a href="{{ url('/store') }}">
                <div class="card">
                    <i class="icon icon-cart"></i>
                    <p>{{ __("Store") }}</p>
                </div>
            </a>
            <div class="card" onclick="menuCard(this, 0)">
                <i class="icon icon-card"></i>
                <p>{{ __("friend card") }}</p>
                <div class="menu-panel">
                    @component('components.friend_card')
                    @endcomponent
                </div>
            </div>
            <div class="card" onclick="menuCard(this, 1)">
                <i class="icon icon-donate"></i>
                <p>{{ __("Donate") }}</p>
                <div class="menu-panel">
                    @component('components.donate')
                    @endcomponent
                </div>
            </div>
        </div>

        <div class="menu" onclick="mobileMenu(this)">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="mobile">
        <ul class="list">
            <li><a class="link" href="/home">{{ __("Home") }}</a></li>
            <li><a class="link" href="/association">{{ __("The Organization") }}</a></li>
            <li><a class="link" href="/ced">{{ __("CED") }}</a></li>
            <li><a class="link" href="/animals">{{ __("Animals") }}</a></li>
            <li><a class="link" href="/help">{{ __("Help") }}</a></li>
            <li><a class="link" href="/partners">{{ __("Partners") }}</a></li>
            <li><a class="link" href="/store">{{ __("Store") }}</a></li>
        </ul>

        <div class="cards">
            <div class="card" onclick="mobileMenuCard(0)" style="border-right: 4px solid white;">
                <div class="icon icon-card" style="margin-right: 8px"></div>
                <p>{!! str_replace(" ", "<br />", __("friend card")) !!}</p>
            </div>
            <div class="card" onclick="mobileMenuCard(1)">
                <div class="icon icon-donate" style="margin-right: 8px"></div>
                <p>{{ __("Donate") }}</p>
            </div>
        </div>

        <div class="mobile-card-view">
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
</nav>
