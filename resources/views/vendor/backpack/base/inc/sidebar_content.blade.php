<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

@if(restrictTo('admin', 'reports'))
<li><a href="{{ backpack_url('reports') }}"><i class="fa fa-bar-chart"></i> <span>{{ __('Reports') }}</span></a></li>
@endif

@if(restrictTo(['admin', 'volunteer']))
<li class="header">{{ __("Management") }}</li>
<li><a href="{{ backpack_url('process') }}"><i class="fa icon-process"></i> <span class="text-capitalize">{{ __("processes") }}</span></a></li>
<li><a href="{{ backpack_url('appointment') }}"><i class="fa icon-appointment"></i> <span class="text-capitalize">{{ __("appointments") }}</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa icon-animal"></i> <span class="text-capitalize">{{ __("adoptions") }}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('adoption') }}"><i class="fa icon-animal"></i> <span class="text-capitalize">{{ __("adoptions") }}</span></a></li>
        <li><a href="{{ backpack_url('adopter') }}"><i class="fa icon-godfather"></i> <span class="text-capitalize">{{ __("adopters") }}</span></a></li>
        @if(restrictTo('admin', 'adoptions'))
        <li><a href="{{ backpack_url('fat') }}"><i class="fa icon-godfather"></i> <span class="text-capitalize">{{ __("FAT") }}</span></a></li>
        @endif
    </ul>
</li>
@endif

@if(restrictTo('admin', 'accountancy'))
<li><a href="{{ backpack_url('godfather') }}"><i class="fa icon-godfather"></i> <span class="text-capitalize">{{ __("godfathers") }}</span></a></li>
<li><a href="{{ backpack_url('donation') }}"><i class="fa icon-donation"></i> <span class="text-capitalize">{{ __("donations") }}</span></a></li>
@endif

@if(restrictTo('admin', 'protocols'))
<li class="treeview">
    <a href="#"><i class="fa icon-protocol"></i> <span class="text-capitalize">{{ __("protocols") }}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('protocol') }}"><i class="fa icon-protocol"></i> <span class="text-capitalize">{{ __("protocols") }}</span></a></li>
        <li><a href="{{ backpack_url('protocol-request') }}"><i class="fa icon-category"></i> <span class="text-capitalize">{{ __("requests") }}</span></a></li>
    </ul>
</li>
@endif

@if(restrictTo('admin'))
<li class="treeview">
    <a href="#"><i class="fa icon-treatment"></i> <span class="text-capitalize">{{ __("treatment") }}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
@endif
@if(restrictTo(['admin', 'volunteer']))
        <li><a href="{{ backpack_url('treatment') }}"><i class="fa icon-treatment"></i> <span class="text-capitalize">{{ __("treatments") }}</span></a></li>
@endif
@if(restrictTo('admin'))
        <li><a href="{{ backpack_url('treatmenttype') }}"><i class="fa icon-treatment-type"></i> <span class="text-capitalize">{{ __("treatment types") }}</span></a></li>
    </ul>
</li>
@endif

@if(restrictTo('admin', 'vets'))
<li><a href="{{ backpack_url('vet') }}"><i class="fa icon-vet"></i> <span class="text-capitalize">{{ __("vets") }}</span></a></li>
@endif

@if(restrictTo(['admin', 'friend card']))
<li class="treeview">
    <a href="#"><i class="fa icon-partner"></i> <span class="text-capitalize">{{ __("partners") }}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('partner') }}"><i class="fa icon-partner"></i> <span class="text-capitalize">{{ __("partners") }}</span></a></li>
        <li><a href="{{ backpack_url('partner-category') }}"><i class="fa icon-category"></i> <span class="text-capitalize">{{ __("partner categories") }}</span></a></li>
    </ul>
</li>
@endif

@if(restrictTo(['admin', 'store'], ['store orders', 'store shippments', 'store stock', 'store transaction']))
<li class="header">{{ __("Store") }}</li>
<li><a href="{{ backpack_url('store/products') }}"><i class="fa fa-cubes"></i> <span class="text-capitalize">{{ __("products") }}</span></a></li>
<li><a href="{{ backpack_url('store/orders') }}"><i class="fa fa-shopping-cart"></i> <span class="text-capitalize">{{ __("orders") }}</span></a></li>
<li><a href="{{ backpack_url('store/user/stock') }}"><i class="fa fa-truck"></i> <span class="text-capitalize">{{ __("stock") }}</span></a></li>
<li><a href="{{ backpack_url('store/user/transaction') }}"><i class="fa fa-exchange"></i> <span class="text-capitalize">{{ __("transactions") }}</span></a></li>
<li><a href="{{ backpack_url('store/supplier') }}?status=[%22waiting_payment%22]"><i class="fa fa-truck"></i> <span class="text-capitalize">{{ __("suppliers") }}</span></a></li>
@endif

@if(restrictTo('admin'))
<li class="header">Animais de Rua</li>
<li><a href="{{ backpack_url('headquarter') }}"><i class="fa icon-headquarter"></i> <span class="text-capitalize">{{ __("headquarters") }}</span></a></li>
<li><a href="{{ backpack_url('friend-card-modality') }}"><i class="fa icon-card"></i> <span class="text-capitalize">{{ __("friend card") }}</span></a></li>
<li class="treeview">
    <a href="#"><i class="fa icon-territory"></i> <span class="text-capitalize">{{ __("territories") }}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/territory?level=1') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("district") }}</span></a></li>
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/territory?level=2') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("county") }}</span></a></li>
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/territory?level=3') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("parish") }}</span></a></li>
    </ul>
</li>
@endif


@if(restrictTo(['admin', 'translator', 'friend card'], ['website']))
<li class="header">Admin</li>
@if(restrictTo(['admin', 'translator'], 'website'))
<li class="treeview">
    <a href="#"><i class="fa fa-window-maximize"></i> <span>Website</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('page') }}"><i class="fa fa-file-o"></i> <span>{{ __("Pages") }}</span></a></li>
        <li><a href="{{ backpack_url('sponsor') }}"><i class="fa fa-file-o"></i> <span>{{ ucfirst(__("sponsors")) }}</span></a></li>

        @if(restrictTo('admin', 'website'))
        <li><a href="{{ backpack_url('campaign') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("campaigns") }}</span></a></li>
        @endif
    </ul>
</li>
@endif

@if(restrictTo('admin'))
<li class="treeview">
    <a href="#"><i class="fa fa-unlock-alt"></i> <span>Admin</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
        <li><a href="{{ backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>{{ __("File manager") }}</span></a></li>
        <li><a href="{{ backpack_url('language') }}"><i class="fa fa-flag-o"></i> <span>{{ __("Languages") }}</span></a></li>
        <li><a href="{{ backpack_url('language/texts') }}"><i class="fa fa-language"></i> <span>{{ __("Language Files") }}</span></a></li>
        <li><a href="{{ backpack_url('backup') }}"><i class="fa fa-hdd-o"></i> <span>{{ __("Backups") }}</span></a></li>
        <li><a href="{{ backpack_url('log') }}"><i class="fa fa-terminal"></i> <span>{{ __("Logs") }}</span></a></li>
        <li><a href="{{ backpack_url('setting') }}"><i class="fa fa-cog"></i> <span>{{ __("Settings") }}</span></a></li>
    </ul>
</li>
@endif

@if(restrictTo(['admin', 'friend card']))
@if(restrictTo('admin'))
<li class="treeview">
    <a href="#"><i class="fa fa-group"></i> <span>{{ __("Users") }}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
@endif
@if(restrictTo(['admin', 'friend card']))
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/user') }}"><i class="fa fa-user"></i> <span>{{ __("Users") }}</span></a></li>
@endif
@if(restrictTo('admin'))
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/role') }}"><i class="fa fa-group"></i> <span>{{ ucfirst(__('backpack::permissionmanager.roles')) }}</span></a></li>
        <li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/permission') }}"><i class="fa fa-key"></i> <span>{{ ucfirst(__('backpack::permissionmanager.permission_plural')) }}</span></a></li>
@endif
    </ul>
</li>
@endif
@endif
