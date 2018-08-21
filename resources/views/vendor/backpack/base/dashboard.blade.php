@extends('backpack::layout')

@section('header')
    <section class="content-header">
      <h1>
        {{ env('APP_NAME') }} <small>{{ __('Admin Panel') }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
      </ol>
    </section>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="box-title">{{ __('Intranet Stats') }}</div>
                </div>
                <div class="box-body">
                  <p>Esterilizamos <b>{{ $stats['sterilizations'] }}</b> animais em <b>{{ $stats['appointments'] }}</b> consultas recorrendo a <b>{{ $stats['vets'] }}</b> veterinários, só o núcleo de <b>{{ $stats['top_headquarter_sterilizations_name'] }}</b> já leva <b>{{ $stats['top_headquarter_sterilizations_value'] }}</b> operações feitas.</p>
                  <p>Desparasitamos <b>{{ $stats['deworming'] }}</b> animais sendo que o núcleo de <b>{{ $stats['top_headquarter_deworming_name'] }}</b> é recordista com um total de <b>{{ $stats['top_headquarter_deworming_value'] }}</b>. Parabéns!</p>
                  <p>Os nossos <b>{{ $stats['volunteers'] }}</b> voluntários estão de parabéns, já marcaram <b>{{ $stats['treatments'] }}</b> tratamentos num total estimado de <b>{{ $stats['vets_working_hours'] }}</b> mil horas de trabalho.</p>
                  <p>Padrinhos, sem vocês nada seria possível, os nossos <b>{{ $stats['godfathers'] }}</b> benfeitores disponibilizaram mais de <b>{{ $stats['donations'] }}</b> mil euros envolvendo-se directamente em <b>{{ $stats['godfathers_processes'] }}</b> casos!</p>
                </div>
            </div>
        </div>
    </div>
@endsection