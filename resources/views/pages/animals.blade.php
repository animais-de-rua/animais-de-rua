@extends('layouts.app')

@section('content')
	animals
	<picture>
		<source srcset="img/home.webp" type="image/webp">
		<source srcset="img/home.jpg" type="image/jpeg">
		<img src="img/creakyOldJPEG.jpg" alt="Home image">
	</picture>
@endsection
