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
                  <a data-toggle="collapse" href="#collapseOne">
                    <div class="box-title" style="color: #000">Acções administrativas <span class="glyphicon glyphicon-chevron-down" aria-hidden="true" style="font-size: 12px; margin-left: 10px;"></span></div>
                  </a>
                </div>
                <div id="collapseOne" class="collapse out">
                  <div class="panel-body">
                    <button path="/admin/cache/update-products" success="Produtos atualizados." class="btn btn-primary ajax">Actualizar produtos na página inicial</button>
                    @if(backpack_user()->id == 1)
                      <button path="/admin/cache/flush" success="Cache limpa" class="btn btn-primary ajax">Limpar cache</button>
                      <hr />
                      <div style="margin-bottom: 5px;"></div>
                      <button path="/admin/cache/config" success="Configurações em cache" class="btn btn-default ajax">Cache config</button>
                      <button path="/admin/cache/config/clear" success="Cache das configurações limpas" class="btn btn-default ajax">Limpar cache config</button>
                      {{-- <button path="/admin/cache/route" success="Routes em cache" class="btn btn-default ajax">Cache route</button>
                      <button path="/admin/cache/route/clear" success="Cache das routes limpas" class="btn btn-default ajax">Limpar cache route</button>
                      <button path="/admin/cache/view" success="Views em cache" class="btn btn-default ajax">Cache view</button>
                      <button path="/admin/cache/view/clear" success="Cache das views limpas" class="btn btn-default ajax">Limpar cache view</button> --}}
                      <div style="margin-bottom: 5px;"></div>
                      <button path="/admin/maintenance/down" success="Modo de manuntenção activado" class="btn btn-danger ajax">Activar manutenção</button>
                      <button path="/admin/maintenance/up" success="Modo de manuntenção desactivado" class="btn btn-success ajax">Desativar manutenção</button>
                    @endif
                    <script>
                      document.querySelectorAll('.btn.ajax').forEach(btn => {
                        btn.addEventListener('click', e => {
                          fetch(btn.getAttribute('path'), {
                            method: 'POST',
                            credentials: "same-origin",
                            headers: {
                              'X-Requested-With': 'XMLHttpRequest',
                              'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                          });
                          new PNotify({
                            title: "Sucesso",
                            text: btn.getAttribute('success'),
                            type: "success",
                          });
                        });
                      });
                    </script>
                  </div>
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
