<li class="nav-item">
    <a href="{{ backpack_url('dashboard') }}" class="nav-link">
        <i class="nav-icon fa fa-dashboard"></i>
        <p>{{ trans('backpack::base.dashboard') }}</p>
    </a>
</li>

@if(restrictTo('admin', 'reports'))
<li class="nav-item">
    <a href="{{ backpack_url('reports') }}" class="nav-link">
        <i class="nav-icon fa fa-bar-chart"></i>
        <p>{{ __('Reports') }}</p>
    </a>
</li>
@endif

@if(restrictTo(['admin', 'volunteer']))
<li class="nav-title">{{ __("Management") }}</li>
<li class="nav-item">
    <a href="{{ backpack_url('process') }}" class="nav-link">
        <i class="nav-icon fa icon-process"></i>
        <p class="text-capitalize">{{ __("processes") }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ backpack_url('appointment') }}" class="nav-link">
        <i class="nav-icon fa icon-appointment"></i>
        <p class="text-capitalize">{{ __("appointments") }}</p>
    </a>
</li>
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle">
        <i class="nav-icon fa icon-animal"></i>
        <p class="text-capitalize">{{ __("adoptions") }}</p>
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a href="{{ backpack_url('adoption') }}" class="nav-link">
                <i class="nav-icon fa icon-animal"></i>
                <p class="text-capitalize">{{ __("adoptions") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('adopter') }}" class="nav-link">
                <i class="nav-icon fa icon-godfather"></i>
                <p class="text-capitalize">{{ __("adopters") }}</p>
            </a>
        </li>
        @if(restrictTo('admin', 'adoptions'))
        <li class="nav-item">
            <a href="{{ backpack_url('fat') }}" class="nav-link">
                <i class="nav-icon fa icon-godfather"></i>
                <p class="text-capitalize">{{ __("FAT") }}</p>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif

@if(restrictTo('admin', 'accountancy'))
<li class="nav-item">
    <a href="{{ backpack_url('godfather') }}" class="nav-link">
        <i class="nav-icon fa icon-godfather"></i>
        <p class="text-capitalize">{{ __("godfathers") }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ backpack_url('donation') }}" class="nav-link">
        <i class="nav-icon fa icon-donation"></i>
        <p class="text-capitalize">{{ __("donations") }}</p>
    </a>
</li>
@endif

@if(restrictTo('admin', 'protocols'))
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle">
        <i class="nav-icon fa icon-protocol"></i>
        <p class="text-capitalize">{{ __("protocols") }}</p>
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a href="{{ backpack_url('protocol') }}" class="nav-link">
                <i class="nav-icon fa icon-protocol"></i>
                <p class="text-capitalize">{{ __("protocols") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('protocol-request') }}" class="nav-link">
                <i class="nav-icon fa icon-category"></i>
                <p class="text-capitalize">{{ __("requests") }}</p>
            </a>
        </li>
    </ul>
</li>
@endif

@if(restrictTo('admin') || restrictTo(['admin', 'volunteer']))
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle">
        <i class="nav-icon fa icon-treatment"></i>
        <p class="text-capitalize">{{ __("treatment") }}</p>
    </a>
    <ul class="nav-dropdown-items">
        @if(restrictTo(['admin', 'volunteer']))
        <li class="nav-item">
            <a href="{{ backpack_url('treatment') }}" class="nav-link">
                <i class="nav-icon fa icon-treatment"></i>
                <p class="text-capitalize">{{ __("treatments") }}</p>
            </a>
        </li>
        @endif
        @if(restrictTo('admin'))
        <li class="nav-item">
            <a href="{{ backpack_url('treatmenttype') }}" class="nav-link">
                <i class="nav-icon fa icon-treatment-type"></i>
                <p class="text-capitalize">{{ __("treatment types") }}</p>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif

@if(restrictTo('admin', 'vets'))
<li class="nav-item">
    <a href="{{ backpack_url('vet') }}" class="nav-link">
        <i class="nav-icon fa icon-vet"></i>
        <p class="text-capitalize">{{ __("vets") }}</p>
    </a>
</li>
@endif

@if(restrictTo(['admin', 'friend card']))
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle">
        <i class="nav-icon fa icon-partner"></i>
        <p class="text-capitalize">{{ __("partners") }}</p>
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a href="{{ backpack_url('partner') }}" class="nav-link">
                <i class="nav-icon fa icon-partner"></i>
                <p class="text-capitalize">{{ __("partners") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('partner-category') }}" class="nav-link">
                <i class="nav-icon fa icon-category"></i>
                <p class="text-capitalize">{{ __("partner categories") }}</p>
            </a>
        </li>
    </ul>
</li>
@endif

@if(restrictTo(['admin', 'store'], ['store orders', 'store shippments', 'store stock', 'store transaction', 'suppliers', 'store vouchers']))
<li class="nav-title">{{ __("Store") }}</li>
<li class="nav-item">
    <a href="{{ backpack_url('store/products') }}" class="nav-link">
        <i class="nav-icon fa fa-cubes"></i>
        <p class="text-capitalize">{{ __("products") }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ backpack_url('store/orders') }}" class="nav-link">
        <i class="nav-icon fa fa-shopping-cart"></i>
        <p class="text-capitalize">{{ __("orders") }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ backpack_url('store/user/stock') }}" class="nav-link">
        <i class="nav-icon fa fa-truck"></i>
        <p class="text-capitalize">{{ __("stock") }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ backpack_url('store/user/transaction') }}" class="nav-link">
        <i class="nav-icon fa fa-exchange"></i>
        <p class="text-capitalize">{{ __("transactions") }}</p>
    </a>
</li>
@if(restrictTo('admin', 'store orders'))
<li class="nav-item">
    <a href="{{ backpack_url('store/supplier') }}?status=[%22waiting_payment%22]" class="nav-link">
        <i class="nav-icon fa fa-truck"></i>
        <p class="text-capitalize">{{ __("suppliers") }}</p>
    </a>
</li>
@endif
@if(restrictTo('admin', 'store vouchers'))
<li class="nav-item">
    <a href="{{ backpack_url('store/voucher') }}" class="nav-link">
        <i class="nav-icon fa fa-credit-card"></i>
        <p class="text-capitalize">{{ __("vouchers") }}</p>
    </a>
</li>
@endif
@endif

@if(restrictTo('admin'))
<li class="nav-title">Animais de Rua</li>
<li class="nav-item">
    <a href="{{ backpack_url('headquarter') }}" class="nav-link">
        <i class="nav-icon fa icon-headquarter"></i>
        <p class="text-capitalize">{{ __("headquarters") }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ backpack_url('friend-card-modality') }}" class="nav-link">
        <i class="nav-icon fa icon-card"></i>
        <p class="text-capitalize">{{ __("friend card") }}</p>
    </a>
</li>
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle">
        <i class="nav-icon fa icon-territory"></i>
        <p class="text-capitalize">{{ __("territories") }}</p>
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/territory?level=1') }}" class="nav-link">
                <i class="nav-icon fa fa-file-o"></i>
                <p class="text-capitalize">{{ __("district") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/territory?level=2') }}" class="nav-link">
                <i class="nav-icon fa fa-file-o"></i>
                <p class="text-capitalize">{{ __("county") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/territory?level=3') }}" class="nav-link">
                <i class="nav-icon fa fa-file-o"></i>
                <p class="text-capitalize">{{ __("parish") }}</p>
            </a>
        </li>
    </ul>
</li>
@endif

@if(restrictTo(['admin', 'translator', 'friend card'], ['website']))
<li class="nav-title">Admin</li>
@if(restrictTo(['admin', 'translator'], 'website'))
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle">
        <i class="nav-icon fa fa-window-maximize"></i>
        <p>Website</p>
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a href="{{ backpack_url('page') }}" class="nav-link">
                <i class="nav-icon fa fa-file-o"></i>
                <p>{{ __("Pages") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('sponsor') }}" class="nav-link">
                <i class="nav-icon fa fa-file-o"></i>
                <p>{{ ucfirst(__("sponsors")) }}</p>
            </a>
        </li>
        @if(restrictTo('admin', 'website'))
        <li class="nav-item">
            <a href="{{ backpack_url('campaign') }}" class="nav-link">
                <i class="nav-icon fa fa-file-o"></i>
                <p class="text-capitalize">{{ __("campaigns") }}</p>
            </a>
        </li>
        @endif
    </ul>
</li>
@endif

@if(restrictTo('admin'))
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle">
        <i class="nav-icon fa fa-unlock-alt"></i>
        <p>Admin</p>
    </a>
    <ul class="nav-dropdown-items">
        <li class="nav-item">
            <a href="{{ backpack_url('elfinder') }}" class="nav-link">
                <i class="nav-icon fa fa-files-o"></i>
                <p>{{ __("File manager") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('language') }}" class="nav-link">
                <i class="nav-icon fa fa-flag-o"></i>
                <p>{{ __("Languages") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('language/texts') }}" class="nav-link">
                <i class="nav-icon fa fa-language"></i>
                <p>{{ __("Language Files") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('backup') }}" class="nav-link">
                <i class="nav-icon fa fa-hdd-o"></i>
                <p>{{ __("Backups") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('log') }}" class="nav-link">
                <i class="nav-icon fa fa-terminal"></i>
                <p>{{ __("Logs") }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ backpack_url('setting') }}" class="nav-link">
                <i class="nav-icon fa fa-cog"></i>
                <p>{{ __("Settings") }}</p>
            </a>
        </li>
    </ul>
</li>
@endif

@if(restrictTo('admin'))
<li class="nav-item nav-dropdown">
    <a href="#" class="nav-link nav-dropdown-toggle">
        <i class="nav-icon fa fa-group"></i>
        <p>{{ __("Users") }}</p>
    </a>
    <ul class="nav-dropdown-items">
        @if(restrictTo(['admin', 'friend card']))
        <li class="nav-item">
            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/user') }}" class="nav-link">
                <i class="nav-icon fa fa-user"></i>
                <p>{{ __("Users") }}</p>
            </a>
        </li>
        @endif
        <li class="nav-item">
            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/role') }}" class="nav-link">
                <i class="nav-icon fa fa-group"></i>
                <p>{{ ucfirst(__('backpack::permissionmanager.roles')) }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/permission') }}" class="nav-link">
                <i class="nav-icon fa fa-key"></i>
                <p>{{ ucfirst(__('backpack::permissionmanager.permission_plural')) }}</p>
            </a>
        </li>
    </ul>
</li>
@endif
@endif
