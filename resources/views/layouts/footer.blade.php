<footer>
    <div class="container">
        <div class="row row-wrap">
            <div class="column column-67 stats">
                <h1>{{ $total_interventions }}</h1>
                <h4>{{ __("web.operations") }}</h4>
            </div>
            <div class="column column-33 logo">
                <i class="icon icon-logo"></i>
            </div>
        </div>

        <div class="row row-wrap">
            <div class="column column-67 newsletter">
                <form target="_blank" action="https://animaisderua.us19.list-manage.com/subscribe/post?u=a1458853c374b6db4fc079c1c&id=3bf06054b8" method="post">
                    <input type="hidden" name="b_a1458853c374b6db4fc079c1c_3bf06054b8" value="">
                    <input type="hidden" name="req_hid" id="req_hid" value="">

                    <label for="email">{{ __('web.subscribe') }}</label>
                    <input type="email" name="EMAIL" id="email" placeholder="{{ __('Your Email Address') }}" value=""/>
                    <button type="submit" name="subscribe" title="{{ __('web.subscribe') }}">
                        <i class="icon icon-arrow"></i>
                    </button>
                </form>
            </div>
            <div class="column column-33 solidarity">
                <h4>{{ __('web.solidary.title') }}</h4>
                <a class="color-link" href="tel:{{ __('web.solidary.number') }}">{{ __('web.solidary.number') }}</a>
                <p>{{ __('web.solidary.description') }}</p>
            </div>
        </div>

        <div class="row row-wrap" style="margin-top: 24px;">
            <div class="column column-67 social">
                <h4>{{ __('web.social') }}</h4>
                <a target="_blank" class="color-link" rel="noopener" title="Facebook" href="https://www.facebook.com/animaisderua"><i class="icon icon-facebook"></i></a>
                <a target="_blank" class="color-link" rel="noopener" title="Instagram" href="https://www.instagram.com/animaisderua/"><i class="icon icon-instagram"></i></a>
                <a target="_blank" class="color-link" rel="noopener" title="Youtube" href="https://www.youtube.com/user/animaisderuaAdR"><i class="icon icon-youtube"></i></a>
            </div>
            <div class="column column-33 contact">
                <p><a onclick="return modal.open('contact')" class="color-link lined light">{{ __("web.contact") }}</a></p>
                <p><a href="/privacy-policy" class="link color-link lined light">{{ __("web.privacy") }}</a></p>
            </div>
        </div>
    </div>
</footer>
