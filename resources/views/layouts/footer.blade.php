<footer>
    <div class="container">
        <div class="row row-wrap">
            <div class="column column-67 stats">
                <h1>{{ $total_interventions ?? 0 }}</h1>
                <h2>{{ __("web.operations") }}</h2>
            </div>
            <div class="column column-33 logo">
                <i class="icon icon-logo"></i>
            </div>
        </div>

        <div class="row row-wrap">
            <div class="column column-67 newsletter">
                <form action="/newsletter" method="post" class="ajax">
                    @csrf
                    <label for="email">{{ __('web.subscribe') }}</label>
                    <input type="email" name="email" id="email" placeholder="{{ __('Your Email Address') }}" value="" required/>
                    <button type="submit" name="subscribe" aria-label="{{ __('web.subscribe') }}">
                        <i class="icon icon-arrow"></i>
                    </button>
                    <p class="result"></p>
                </form>
            </div>
            <div class="column column-33 solidarity">
                <h2>{{ __('web.solidary.title') }}</h2>
                <a class="color-link" href="tel:{{ __('web.solidary.number') }}">{{ __('web.solidary.number') }}</a>
                <p>{{ __('web.solidary.description') }}</p>
            </div>
        </div>

        <div class="row row-wrap" style="margin-top: 24px;">
            <div class="column column-67 social">
                <h2>{{ __('web.social') }}</h2>
                <a target="_blank" class="color-link" rel="noopener" onclick="app.track('ViewContent', {'path': 'facebook'})" title="Facebook" href="https://www.facebook.com/animaisderua"><i class="icon icon-facebook"></i></a>
                <a target="_blank" class="color-link" rel="noopener" onclick="app.track('ViewContent', {'path': 'instagram'})" title="Instagram" href="https://www.instagram.com/animaisderua/"><i class="icon icon-instagram"></i></a>
                <a target="_blank" class="color-link" rel="noopener" onclick="app.track('ViewContent', {'path': 'youtube'})" title="Youtube" href="https://www.youtube.com/user/animaisderuaAdR"><i class="icon icon-youtube"></i></a>
            </div>
            <div class="column column-33 contact">
                <p><a onclick="return modal.open('contact')" class="color-link lined light">{{ __("web.contact") }}</a></p>
                <p><a href="/privacy-policy" class="link color-link lined light">{{ __("web.privacy") }}</a></p>
            </div>
        </div>
    </div>
</footer>
