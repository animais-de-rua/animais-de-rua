@php
use App\Exports\TreatmentTypeExport;
use App\Exports\StoreExport;
use App\Exports\DonationExport;
use App\Models\Headquarter;
use App\Models\StoreProduct;
use App\Models\Territory;
use App\Models\Protocol;
use App\User;
@endphp

@extends('backpack::layout')

@section('header')
<section class="content-header">
    <h1>
        {{ __('Reports') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a></li>
        <li class="active">{{ trans('backpack::base.dashboard') }}</li>
    </ol>
</section>
@endsection


@section('after_scripts')
<script src="{{ mix('js/admin/reports.js') }}"></script>
@endsection

@section('style')
<link rel="stylesheet" href="{{ mix('css/admin/reports.css') }}">
@endsection

@section('content')


{{-- Treatment type --}}
@include('admin.reports.treatment_type', [
    'order' => TreatmentTypeExport::order(),
    'headquarters' => Headquarter::select(['id', 'name'])->get(),
    'territories' => [
        'district' => Territory::select(['id', 'name'])->district()->get(),
        'county' => Territory::select(['id', 'name', 'parent_id'])->county()->get(),
        'parish' => Territory::select(['id', 'name', 'parent_id'])->parish()->get(),
    ],
    'protocols' => Protocol::select(['id', 'name', 'territory_id'])->get(),
])


{{-- Store --}}
@include('admin.reports.store', [
    'order' => StoreExport::order(),
    'store_products' => StoreProduct::select(['id', 'name'])->get(),
    'volunteers' => User::select(['id', 'name'])->storeRole()->orderBy('name')->get(),
])


{{-- Donations --}}
@include('admin.reports.donations', [
    'order' => DonationExport::order(),
    'group' => DonationExport::group(),
    'headquarters' => Headquarter::select(['id', 'name'])->get(),
    'protocols' => Protocol::select(['id', 'name'])->get(),
])


@endsection
