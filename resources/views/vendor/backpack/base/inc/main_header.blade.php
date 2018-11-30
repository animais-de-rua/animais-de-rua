<header class="main-header">
  <!-- Logo -->
  <a href="{{ url('') }}" class="logo hidden-xs">
    <span class="logo-mini"><img style="width: 38px; filter: brightness(0) invert(1) opacity(0.9);" src="/img/logo/logo.svg" /></span>
    <span class="logo-lg"><img style="width: 80%; max-width: 300px; filter: brightness(0) invert(1) opacity(0.9);" src="/img/logo/logo-text.svg" /></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">{{ trans('backpack::base.toggle_navigation') }}</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>

    @include('backpack::inc.menu')
  </nav>
</header>