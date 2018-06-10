@extends('backpack::layout')

@section('after_styles')
<style media="screen">
    .backpack-profile-form .required::after {
        content: ' *';
        color: red;
    }
</style>
@endsection

@section('header')
<section class="content-header">

    <h1>
        {{ trans('backpack::base.my_account') }}
    </h1>

    <ol class="breadcrumb">

        <li>
            <a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a>
        </li>

        <li>
            <a href="{{ route('backpack.account.info') }}">{{ trans('backpack::base.my_account') }}</a>
        </li>

        <li class="active">
            {{ trans('Terminal') }}
        </li>

    </ol>

</section>
@endsection

@section('after_scripts')
<script type="text/javascript">
    $("#terminal").submit(function(e) {
        $.ajax({
            type: "POST",
            url: '{{ route('terminal_run') }}',
            data: $("#terminal").serialize(),
            success: function(data) {
                $("pre").text(data);
            }
        });

        e.preventDefault();
    });
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('backpack::auth.account.sidemenu')
    </div>
    <div class="col-md-9">

        <form id="terminal" class="form" action="{{ route('terminal_run') }}" method="post">

            {!! csrf_field() !!}

            <div class="box">

                <div class="box-body backpack-profile-form">

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->count())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label class="required">{{ trans('Command') }}</label>
                        <input id="cmd" required class="form-control" type="text" name="cmd" value="">
                    </div>

                    <pre style="min-height: 180px;"></pre>

                </div>

                <div class="box-footer">

                    <button type="submit" class="btn btn-success"><span class="ladda-label"><i class="fa fa-terminal"></i> {{ trans('Run') }}</span></button>

                </div>
            </div>

        </form>

    </div>
</div>
@endsection
