@extends('errors.illustrated-layout')

@section('code', '401')
@section('title', __('Sorry, you are forbidden from accessing this page.'))

@section('image')
<div style="background-image: url('/svg/404.svg');" class="absolute pin bg-cover bg-no-repeat md:bg-left lg:bg-center">
</div>
@endsection

@section('message', __('Sorry, you are forbidden from accessing this page.'))
