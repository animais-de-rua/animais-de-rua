{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="Dashboard" icon="la la-dashboard" :link="backpack_url('dashboard')" />

{{-- App Content --}}
<x-backpack::menu-separator :title="__('Content')" />

{{-- Admin --}}
@include('gemadigital::vendor.backpack.base.inc.sidebar_content_admin')
