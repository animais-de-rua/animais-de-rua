@extends('layouts.app')

@section('content')
<div id="home">
	<div class="container">
		<div class="row responsive header">
			<div class="column column-50">
				<div class="flex-vertical-align">
					<div class="info-text">
						<h1 class="label-title">{{ $page['association_title'] }}</h1>
						<p>{{ $page['association_text'] }}</p>
						<a href="/association" class="link lined">{{ $page['association_link'] }}</a>
					</div>
				</div>
			</div>
			<div class="column column-50">
				<picture>
					<source srcset="img/home.webp" type="image/webp"/>
					<source srcset="img/home.jpg" type="image/jpeg"/>
					<img src="img/home.jpg" alt="Home"/>
				</picture>
			</div>
		</div>
	</div>

	<div class="container campaigns">
		<div class="flex-slider" auto-scroll="5000">
			<ul>
				@foreach($campaigns as $campaign)
				<li>
					<div class="slide">
						<div>
							<img src="uploads/{{ $campaign->image }}" alt="{{ $campaign->name }}"/>
						</div>
						<div>
							<blockquote>{{ __("campaigns") }}</blockquote>
							<h1>{{ $campaign->name }}</h1>
							<p>{{ $campaign->introduction }}</p>
						</div>
					</div>
				</li>
				@endforeach
			</ul>
			<ul class="dots">
				@foreach($campaigns as $i => $campaign)
				<li class="{{ $i == 0 ? 'active' : '' }}"></li>
				@endforeach
			</ul>
		</div>
	</div>

	<div class="risk">
		<picture>
			<source srcset="img/risk.webp" type="image/webp"/>
			<source srcset="img/risk.jpg" type="image/jpeg"/>
			<img src="img/risk.jpg" alt="Animal in ris/k">
		</picture>
		<a href="" class="box">
			<h2>{{ __("web.risk.title") }}</h2>
			<p>{{ __("web.risk.link") }}<i class="icon icon-arrow"></i></p>
		</a>
	</div>

	<div class="container how-to-help">
		<h2>{{ __("web.help.title") }}</h2>
		<div class="row responsive header">
			@foreach(['volunteer', 'friend', 'godfather', 'donate'] as $link)
			<a href="/help#{{ $link }}" class="column box link">
				<h3>{{ __("web.help.$link.title") }}</h3>
				<p>{{ __("web.help.$link.text") }}</p>
				<div class="icon icon-arrow"></div>
			</a>
			@endforeach
		</div>
	</div>
</div>
@endsection
