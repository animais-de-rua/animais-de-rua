<h1>{{ __("Donate") }}</h1>
<p>{{ __("web.donate.title") }}</p>
<form class="stopPropagation" name="_xclick" action="https://www.paypal.com/yt/cgi-bin/webscr" method="post" target="_blank">
    <input type="hidden" name="cmd" value="_xclick"/>
    <input type="hidden" name="business" value="{{ env("PAYPAL") }}"/>
    <input type="hidden" name="item_name" value="{{ __("Donate") }}"/>
    <input type="hidden" name="currency_code" value="EUR"/>
    <label><input type="radio" name="amount[]" value="{{ __("web.donate.values.1") }}.00"> <span></span> {{ __("web.donate.values.1") }}€</label>
    <label><input type="radio" name="amount[]" value="{{ __("web.donate.values.2") }}.00"> <span></span> {{ __("web.donate.values.2") }}€</label>
    <label><input type="radio" name="amount[]" value="{{ __("web.donate.values.3") }}.00"> <span></span> {{ __("web.donate.values.3") }}€</label>
    <br />
    <input class="btn inverse" type="submit" src="" border="0" name="submit" value="Quero Ajudar">
</form>
