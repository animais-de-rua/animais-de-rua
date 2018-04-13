<div class="social-logins">
    <a href="{{ url('/login/facebook') }}" class="btn btn-block btn-social btn-facebook" onclick="_gaq.push(['_trackEvent', 'btn-social', 'click', 'btn-facebook']);">
        <i class="ico-facebook"></i> Facebook
    </a>
    <a href="{{ url('/login/') }}" class="btn btn-block btn-social btn-google" disabled="disabled" onclick="_gaq.push(['_trackEvent', 'btn-social', 'click', 'btn-google']);">
        <i class="ico-google"></i> Google
    </a>
</div>