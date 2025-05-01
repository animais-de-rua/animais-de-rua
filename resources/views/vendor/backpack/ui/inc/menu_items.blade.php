{{-- This file is used for menu items by any Backpack v6 theme --}}
<x-backpack::menu-item title="Dashboard" icon="la la-dashboard" :link="backpack_url('dashboard')" />

@if(restrictTo('admin', 'reports'))
<x-backpack::menu-item title="{{ __('Reports') }}" icon="la la-bar-chart" :link="backpack_url('reports')" />
@endif

{{-- Management Section --}}
@if(restrictTo(['admin', 'volunteer']))
<x-backpack::menu-separator :title="__('Management')" />
<x-backpack::menu-item :title="ucfirst(__('processes'))" icon="la icon-process" :link="backpack_url('process')" />
<x-backpack::menu-item :title="ucfirst(__('appointments'))" icon="la icon-appointment" :link="backpack_url('appointment')" />

{{-- Adoptions Dropdown --}}
<x-backpack::menu-dropdown :title="ucfirst(__('adoptions'))" icon="la icon-animal">
    <x-backpack::menu-dropdown-item :title="ucfirst(__('adoptions'))" icon="la icon-animal" :link="backpack_url('adoption')" />
    <x-backpack::menu-dropdown-item :title="ucfirst(__('adopters'))" icon="la icon-godfather" :link="backpack_url('adopter')" />
    @if(restrictTo('admin', 'adoptions'))
    <x-backpack::menu-dropdown-item :title="__('FAT')" icon="la icon-godfather" :link="backpack_url('fat')" />
    @endif
</x-backpack::menu-dropdown>
@endif

{{-- Accountancy Items --}}
@if(restrictTo('admin', 'accountancy'))
<x-backpack::menu-item :title="ucfirst(__('godfathers'))" icon="la icon-godfather" :link="backpack_url('godfather')" />
<x-backpack::menu-item :title="ucfirst(__('donations'))" icon="la icon-donation" :link="backpack_url('donation')" />
@endif

{{-- Protocols Dropdown --}}
@if(restrictTo('admin', 'protocols'))
<x-backpack::menu-dropdown :title="ucfirst(__('protocols'))" icon="la icon-protocol">
    <x-backpack::menu-dropdown-item :title="ucfirst(__('protocols'))" icon="la icon-protocol" :link="backpack_url('protocol')" />
    <x-backpack::menu-dropdown-item :title="ucfirst(__('requests'))" icon="la icon-category" :link="backpack_url('protocol-request')" />
</x-backpack::menu-dropdown>
@endif

{{-- Treatments Dropdown --}}
@if(restrictTo('admin'))
<x-backpack::menu-dropdown :title="ucfirst(__('treatment'))" icon="la icon-treatment">
@endif
@if(restrictTo(['admin', 'volunteer']))
    <x-backpack::menu-dropdown-item :title="ucfirst(__('treatments'))" icon="la icon-treatment" :link="backpack_url('treatment')" />
@endif
@if(restrictTo('admin'))
    <x-backpack::menu-dropdown-item :title="ucfirst(__('treatment types'))" icon="la icon-treatment-type" :link="backpack_url('treatmenttype')" />
</x-backpack::menu-dropdown>
@endif

{{-- Vets Item --}}
@if(restrictTo('admin', 'vets'))
<x-backpack::menu-item :title="ucfirst(__('vets'))" icon="la icon-vet" :link="backpack_url('vet')" />
@endif

{{-- Partners Dropdown --}}
@if(restrictTo(['admin', 'friend card']))
<x-backpack::menu-dropdown :title="ucfirst(__('partners'))" icon="la icon-partner">
    <x-backpack::menu-dropdown-item :title="ucfirst(__('partners'))" icon="la icon-partner" :link="backpack_url('partner')" />
    <x-backpack::menu-dropdown-item :title="ucfirst(__('partner categories'))" icon="la icon-category" :link="backpack_url('partner-category')" />
</x-backpack::menu-dropdown>
@endif

{{-- Store Section --}}
@if(restrictTo(['admin', 'store'], ['store orders', 'store shippments', 'store stock', 'store transaction', 'suppliers', 'store vouchers']))
<x-backpack::menu-separator :title="__('Store')" />
<x-backpack::menu-item :title="ucfirst(__('products'))" icon="la la-cubes" :link="backpack_url('store/products')" />
<x-backpack::menu-item :title="ucfirst(__('orders'))" icon="la la-shopping-cart" :link="backpack_url('store/orders')" />
<x-backpack::menu-item :title="ucfirst(__('stock'))" icon="la la-truck" :link="backpack_url('store/user/stock')" />
<x-backpack::menu-item :title="ucfirst(__('transactions'))" icon="la la-exchange" :link="backpack_url('store/user/transaction')" />
@if(restrictTo('admin', 'store orders'))
<x-backpack::menu-item :title="ucfirst(__('suppliers'))" icon="la la-truck" :link="backpack_url('store/supplier').'?status=[%22waiting_payment%22]'" />
@endif
@if(restrictTo('admin', 'store vouchers'))
<x-backpack::menu-item :title="ucfirst(__('vouchers'))" icon="la la-credit-card" :link="backpack_url('store/voucher')" />
@endif
@endif

{{-- Animais de Rua Section --}}
@if(restrictTo('admin'))
<x-backpack::menu-separator title="Animais de Rua" />
<x-backpack::menu-item :title="ucfirst(__('headquarters'))" icon="la icon-headquarter" :link="backpack_url('headquarter')" />
<x-backpack::menu-item :title="ucfirst(__('friend card'))" icon="la icon-card" :link="backpack_url('friend-card-modality')" />

{{-- Territories Dropdown --}}
<x-backpack::menu-dropdown :title="ucfirst(__('territories'))" icon="la icon-territory">
    <x-backpack::menu-dropdown-item :title="ucfirst(__('district'))" icon="la la-file-o" :link="url(config('backpack.base.route_prefix', 'admin') . '/territory?level=1')" />
    <x-backpack::menu-dropdown-item :title="ucfirst(__('county'))" icon="la la-file-o" :link="url(config('backpack.base.route_prefix', 'admin') . '/territory?level=2')" />
    <x-backpack::menu-dropdown-item :title="ucfirst(__('parish'))" icon="la la-file-o" :link="url(config('backpack.base.route_prefix', 'admin') . '/territory?level=3')" />
</x-backpack::menu-dropdown>
@endif

{{-- Admin Section --}}
@if(restrictTo(['admin', 'translator', 'friend card'], ['website']))
<x-backpack::menu-separator title="Admin" />

{{-- Website Dropdown --}}
@if(restrictTo(['admin', 'translator'], 'website'))
<x-backpack::menu-dropdown title="Website" icon="la la-window-maximize">
    <x-backpack::menu-dropdown-item :title="__('Pages')" icon="la la-file-o" :link="backpack_url('page')" />
    <x-backpack::menu-dropdown-item :title="ucfirst(__('sponsors'))" icon="la la-file-o" :link="backpack_url('sponsor')" />
    @if(restrictTo('admin', 'website'))
    <x-backpack::menu-dropdown-item :title="ucfirst(__('campaigns'))" icon="la la-file-o" :link="backpack_url('campaign')" />
    @endif
</x-backpack::menu-dropdown>
@endif

{{-- Admin Dropdown --}}
@if(restrictTo('admin'))
<x-backpack::menu-dropdown title="Admin" icon="la la-unlock-alt">
    <x-backpack::menu-dropdown-item :title="__('File manager')" icon="la la-files-o" :link="backpack_url('elfinder')" />
    <x-backpack::menu-dropdown-item :title="__('Languages')" icon="la la-flag-o" :link="backpack_url('translation-manager')" />
    <x-backpack::menu-dropdown-item :title="__('Logs')" icon="la la-terminal" :link="backpack_url('log')" />
    <x-backpack::menu-dropdown-item :title="__('Settings')" icon="la la-cog" :link="backpack_url('setting')" />
</x-backpack::menu-dropdown>
@endif

{{-- Users Dropdown --}}
@if(restrictTo(['admin', 'friend card']))
@if(restrictTo('admin'))
<x-backpack::menu-dropdown :title="__('Users')" icon="la la-group">
@endif
@if(restrictTo(['admin', 'friend card']))
    <x-backpack::menu-dropdown-item :title="__('Users')" icon="la la-user" :link="url(config('backpack.base.route_prefix', 'admin') . '/user')" />
@endif
@if(restrictTo('admin'))
    <x-backpack::menu-dropdown-item :title="ucfirst(__('backpack::permissionmanager.roles'))" icon="la la-group" :link="url(config('backpack.base.route_prefix', 'admin') . '/role')" />
    <x-backpack::menu-dropdown-item :title="ucfirst(__('backpack::permissionmanager.permission_plural'))" icon="la la-key" :link="url(config('backpack.base.route_prefix', 'admin') . '/permission')" />
</x-backpack::menu-dropdown>
@endif
@endif
@endif
