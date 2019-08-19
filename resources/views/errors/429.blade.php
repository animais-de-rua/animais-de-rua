@extends('errors.illustrated-layout')

@section('code', '429')
@section('title', __('Sorry, you are making too many requests to our servers.'))

@section('image')
<div style="background-image: url('/svg/404.svg');" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __('Sorry, you are making too many requests to our servers.'))
