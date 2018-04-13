@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2 grid">

            @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
            @endif
            
            <div class="panel panel-default">
                <div class="panel-heading"><i class="ico-stack" style="font-size: 20px;"></i> ...</div>
                <div class="panel-body">
                    ...
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
