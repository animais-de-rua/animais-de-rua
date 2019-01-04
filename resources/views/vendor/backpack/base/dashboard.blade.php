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
            @if(is('admin'))
            <div class="box">
                <div class="box-header with-border">
                    <div class="box-title">Acções administrativas</div>
                </div>
                <div class="box-body">
                  <button id="update-store" class="btn btn-primary">Actualizar produtos na página inicial</button>
                  <script>
                    document.querySelector('#update-store').addEventListener('click', e => {
                      fetch('/admin/update-products', {
                        method: 'POST',
                        credentials: "same-origin",
                        headers: {
                          'X-Requested-With': 'XMLHttpRequest',
                          'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                      });
                      new PNotify({
                        title: "Sucesso",
                        text: "Produtos atualizados.",
                        type: "success",
                      });
                    });
                  </script>
                </div>
            </div>
            @endif

            <div class="box">
                <div class="box-header with-border">
                    <div class="box-title">{{ __('Stats') }}</div>
                </div>
                <div class="box-body">
                  <p>Esterilizámos <b>{{ $stats['sterilizations'] }}</b> animais em <b>{{ $stats['appointments'] }}</b> consultas recorrendo a <b>{{ $stats['vets'] }}</b> veterinários, só o núcleo de <b>{{ $stats['top_headquarter_sterilizations_name'] }}</b> já leva <b>{{ $stats['top_headquarter_sterilizations_value'] }}</b> operações feitas.</p>
                  <p>Os nossos <b>{{ $stats['volunteers'] }}</b> voluntários estão de parabéns, já marcaram <b>{{ $stats['treatments'] }}</b> tratamentos num total estimado de <b>{{ $stats['vets_working_hours'] < 2000 ? $stats['vets_working_hours'] : (floor($stats['vets_working_hours'] / 100) / 10 . " mil") }}</b> horas de trabalho.</p>
                  <p>Sem padrinhos nada seria possível, os nossos <b>{{ $stats['godfathers'] }}</b> benfeitores disponibilizaram mais de <b>{{ $stats['donations'] < 2000 ? (int) $stats['donations'] : (floor($stats['donations'] / 100) / 10 . " mil") }}</b> euros envolvendo-se directamente em <b>{{ $stats['godfathers_processes'] }}</b> casos!</p>
                  <p>Obrigada pelo vosso trabalho!</p>
                </div>
            </div>
        </div>
    </div>
@endsection
