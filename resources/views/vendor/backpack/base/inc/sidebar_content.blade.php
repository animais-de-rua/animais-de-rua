<li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

<li class="header">{{ __("Management") }}</li>
<li><a href="{{ backpack_url('processes') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("processes") }}</span></a></li>
<li><a href="{{ backpack_url('appointments') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("appointments") }}</span></a></li>
<li><a href="{{ backpack_url('counters') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("counters") }}</span></a></li>
<li><a href="{{ backpack_url('adoptions') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("adoptions") }}</span></a></li>
<li><a href="{{ backpack_url('godfathers') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("godfathers") }}</span></a></li>
<li><a href="{{ backpack_url('protocols') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("protocols") }}</span></a></li>
<li><a href="{{ backpack_url('vets') }}"><i class="fa fa-file-o"></i> <span class="text-capitalize">{{ __("vets") }}</span></a></li>

<li class="header">Animais de Rua</li>
<li><a href="{{ backpack_url('headquarter') }}"><i class="fa fa-university"></i> <span class="text-capitalize">{{ __("headquarters") }}</span></a></li>

<li class="header">Admin</li>
<li class="treeview">
	<a href="#"><i class="fa fa-window-maximize"></i> <span>Website</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href="{{ backpack_url('page') }}"><i class="fa fa-file-o"></i> <span>{{ __("Pages") }}</span></a></li>
	</ul>
</li>

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

<li class="treeview">
	<a href="#"><i class="fa fa-group"></i> <span>{{ __("Users") }}</span> <i class="fa fa-angle-left pull-right"></i></a>
	<ul class="treeview-menu">
		<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/user') }}"><i class="fa fa-user"></i> <span>{{ __("Users") }}</span></a></li>
		<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/role') }}"><i class="fa fa-group"></i> <span>{{ __("Roles") }}</span></a></li>
		<li><a href="{{ url(config('backpack.base.route_prefix', 'admin') . '/permission') }}"><i class="fa fa-key"></i> <span>{{ __("Permissions") }}</span></a></li>
	</ul>
</li>