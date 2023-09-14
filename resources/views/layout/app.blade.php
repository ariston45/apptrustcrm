@php
	use App\Http\Controllers\ProfileController;
	$user = ProfileController::IdenUser();
	$menus = ProfileController::IdenMenu();
  @endphp
<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
      TrustCRM | @yield('title')
    </title>
    {{-- css files --}}
    <link type="text/css" rel="stylesheet" href="{{ asset('dist/css/tabler.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('dist/css/tabler-flags.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('dist/css/tabler-payments.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('dist/css/tabler-vendors.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('dist/css/demo.min.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('plugins/fontawesome/css/all.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('plugins/remixicon/remixicon.css') }}" >
    {{-- <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}"> --}}
    @stack('style')
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
        --tblr-font-sans-serif: Inter, -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      .custom-icon{
        font-size: 1.5rem;
      }
      .page-wrapper .page-header{
        margin: 1rem 0 0;
      }
      .page-body{
        margin: 1rem 0 1rem 0;
      }
      .footer{
        padding: 15px 0 15px 0;
      }
      .nav-icon{
        height: 20px;
        margin-right: 8px;
      }
      .custom-icn{
        margin-right: 8px;
        font-size: 18px;
      }
      .card-header-custom{
        padding: 10px 10px 10px 10px;
      }
      .card-body-custom{
        padding: 16px 24px 16px 24px;
      }
      .nav-link-title{
        color: #39656b;
      }
      .text-username{
        color: #ffffff;
      }
      .text-userstatus{
        color: #ffffffc7;
      }
      .page-wrapper{
        background-color: #f9fffd;
      }
      .card-header{
        background-color: #39656b;
      }
      .card-title{
        color: #ffffffc7;
      }
      .card-title-custom {
        display: block;
        margin: 0 0 1rem;
        font-size: 1rem;
        font-weight: var(--tblr-font-weight-medium);
        line-height: 1.5rem;
      }
      .navbar{
        background-color: #fcfdf6; 
        color: #1a1c19;
      }
      .card-body-custom{
        background-color: #ffffff;
      }
      .footer{
        background-color: #030802;
      }
    </style>
  </head>
  <body id="body-id"  class=" layout-fluid">
    <script src="{{ url('dist/js/demo-theme.min.js') }}"></script>
    <div class="page">
      {{-- HEADER --}}
      <header class="navbar navbar-expand-md navbar-light d-print-none" style="background-color: #39656b;">
        <div class="container-xl">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href=".">
              <img src="{{ asset('static/trustcrm.png') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
            </a>
          </h1>
          <div class="navbar-nav flex-row order-md-last" >
            <div class="d-none d-md-flex">
              <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip"data-bs-placement="bottom" style="margin-right: 8px;">
                <i class="fa-regular fa-moon custom-icon" style="height: 24px;width:24px;"></i>
              </a>
              <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom" style="margin-right: 8px;">
                <i class="fa-regular fa-sun custom-icon" style="height: 24px;width:24px;"></i>
              </a>
              <div class="nav-item dropdown d-none d-md-flex me-3">
                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                  <i class="fa-regular fa-bell custom-icon" style="height: 24px;width:24px;"></i>
                  <span class="badge bg-red"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Last updates</h3>
                    </div>
                    <div class="list-group list-group-flush list-group-hoverable">
                      <div class="list-group-item">
                        <div class="row align-items-center">
                          <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                          <div class="col text-truncate">
                            <a href="#" class="text-body d-block">Example 1</a>
                            <div class="d-block text-muted text-truncate mt-n1">
                              Change deprecated html tags to text decoration classes (#29604)
                            </div>
                          </div>
                          <div class="col-auto">
                            <a href="#" class="list-group-item-actions">
                              <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
                <div class="d-none d-xl-block ps-2">
                  <div class="text-username">Paweł Kuna</div>
                  <div class="mt-1 small text-userstatus">UI Designer</div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="#" class="dropdown-item">Status</a>
                <a href="#" class="dropdown-item">Profile</a>
                <a href="#" class="dropdown-item">Feedback</a>
                <div class="dropdown-divider"></div>
                <a href="./settings.html" class="dropdown-item">Settings</a>
                <a href="{{ url('logout') }}" class="dropdown-item">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </header>
      {{-- MENUS --}}
      <div class="navbar-expand-md" style="background-color: ">
        <div class="collapse navbar-collapse" id="navbar-menu">
          <div class="navbar navbar-light">
            <div class="container-xl">
              <ul class="navbar-nav">
                @foreach ($menus as $menu)
                  <li @if (request()->is($menu->mn_slug . '*') == true) class="nav-item active dropdown" @else  class="nav-item dropdown" @endif>
                    <a @if (count($menu->children)) href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false" @else href="{{ url($menu->mn_slug) }}" class="nav-link" @endif >
                      <img src="{{ asset('static/icons/' . $menu->mn_icon_code) }}" class="nav-icon" alt="nav-icon">
                      <span class="nav-link-title" > {{ $menu->mn_title }} </span>
                    </a>
                    @if (count($menu->children))
                      <div class="dropdown-menu">
                        @include('layout.submenu', ['childs' => $menu->children])
                      </div>
                    @endif
                  </li>
                @endforeach
              </ul>
              <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- PAGE WRAPER --}}
      <div class="page-wrapper">
        {{-- PAGE HEADER --}}
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                @yield('pagetitle')
              </div>
              <div class="col-auto ms-auto d-print-none">
                <ol class="breadcrumb breadcrumb-arrows">
                  @yield('breadcrumb')
                </ol>
              </div>
            </div>
          </div>
        </div>
        {{-- PAGE BODY --}}
        <div class="page-body">
          <div class="container-xl">
            @yield('content')
            </div>
          </div>
        </div>
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; 2022
                    <a href="." class="link-secondary">Tabler</a>.
                    All rights reserved.
                  </li>
                  <li class="list-inline-item">
                    <a href="./changelog.html" class="link-secondary" rel="noopener">
                      v1.0.0-beta16
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    <!-- Libs JS -->
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/js/jsvectormap.min.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world.js') }}" defer></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world-merc.js') }}" defer></script>
    <script src="{{ asset('plugins/jquery/jquery-3.6.3.min.js') }}"></script>
    <!-- Tabler Core -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    {{-- <script src="{{ asset('dist/js/demo.min.js') }}" defer></script> --}}
    @stack('script')
  </body>
</html>