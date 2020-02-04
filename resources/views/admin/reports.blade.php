@php
use App\Exports\AffectedAnimalsExport;
use App\Exports\TreatmentTypeExport;
use App\Exports\StoreExport;
use App\Exports\DonationExport;
use App\Models\Headquarter;
use App\Models\StoreProduct;
use App\Models\Territory;
use App\Models\Protocol;
use App\Models\Vet;
use App\User;
use App\Helpers\EnumHelper;
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

@php
// Vars
$headquarters = Headquarter::select(['id', 'name'])->get();
$protocols = Protocol::select(['id', 'name', 'territory_id'])->get();
$vets = Vet::select(['id', 'name'])->get();
$volunteers = User::select(['id', 'name'])->storeRole()->orderBy('name')->get();
$store_products = StoreProduct::select(['id', 'name'])->get();
$territories = [
    'district' => Territory::select(['id', 'name'])->district()->get(),
    'county' => Territory::select(['id', 'name', 'parent_id'])->county()->get(),
    'parish' => Territory::select(['id', 'name', 'parent_id'])->parish()->get(),
];
@endphp

{{-- Treatment type --}}
@include('admin.reports.treatment_type', [
    'order' => TreatmentTypeExport::order(),
    'headquarters' => $headquarters,
    'territories' => $territories,
    'protocols' => $protocols,
    'vets' => $vets,
    'status' => EnumHelper::values('treatment.status'),
])


{{-- Store --}}
@include('admin.reports.store', [
    'order' => StoreExport::order(),
    'store_products' => $store_products,
    'volunteers' => $volunteers,
    'status' => EnumHelper::values('store.order'),
])


{{-- Donations --}}
@include('admin.reports.donations', [
    'order' => DonationExport::order(),
    'group' => DonationExport::group(),
    'headquarters' => $headquarters,
    'protocols' => $protocols,
    'types' => EnumHelper::values('donation.type'),
])


{{-- Affected Animals --}}
@include('admin.reports.affected_animals', [
    'headquarters' => $headquarters,
    'territories' => $territories,
    'protocols' => $protocols,
    'vets' => $vets,
])


{{-- Total Affected Animals --}}
@include('admin.reports.affected_animals_total', [
    'headquarters' => $headquarters,
    'territories' => $territories,
    'protocols' => $protocols,
    'vets' => $vets,
])


{{-- Adopted Animals --}}
@include('admin.reports.adopted_animals', [
    'headquarters' => $headquarters,
    'territories' => $territories,
    'protocols' => $protocols,
    'status' => EnumHelper::values('adoption.status'),
])

@endsection
