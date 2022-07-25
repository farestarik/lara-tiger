<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
          </li>
          {{-- <li class="nav-item d-none d-sm-inline-block">
              <a href="index3.html" class="nav-link">Home</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              <a href="#" class="nav-link">Contact</a>
          </li> --}}
              <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{__('site.langs')}}
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
              <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                  {{ $properties['native'] }}
              </a>
        @endforeach
        </div>
      </li>
      </ul>

      {{-- <!-- SEARCH FORM -->
      <form class="form-inline ml-3">
          <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
      <i class="fas fa-search"></i>
    </button>
              </div>
          </div>
      </form> --}}

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{route("dashboard.index")}}" class="brand-link">
          <img src="{{ $site_settings->logo_pic }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">{{ $site_settings->app_name }}</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                   <img src="{{ auth()->user()->profile->pic }}" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
                  <a href="{{route("dashboard.profile.index")}}" class="d-block">{{ auth()->user()->name }}</a>
              </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                  <!-- Add icons to the links using the .nav-icon class
                       with font-awesome or any other icon font library -->
                  <li class="nav-item">
                      <a href="{{ route("dashboard.index") }}" class="nav-link" id="dashboardList">
                          <i class="nav-icon fas fa-tachometer-alt"></i>
                          <p>
                              {{__("site.dashboard")}}
                          </p>
                      </a>
                      </li>

                      <li class="nav-item">
                          <a href="{{ route("dashboard.profile.index") }}" class="nav-link" id="profileList">
                              <i class="nav-icon fas fa-user"></i>
                              <p>
                                  {{__("site.profile")}}
                              </p>
                          </a>
                          </li>

                @role("owner|admin")
                  <li class="nav-item has-treeview">
                      <a href="#" class="nav-link" id="adminsList">
                          <i class="nav-icon fas fa-user"></i>
                          <p>
                              {{__("site.admins")}}
                              <i class="fas fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">


                       @permission('read_admins')
                          <li class="nav-item">
                              <a href="{{route("dashboard.admins.index")}}" class="nav-link">
                                  <i class="far fa-eye nav-icon"></i>
                                  <p>{{__("site.show_admins")}}</p>
                              </a>
                          </li>
                       @endpermission

                          @permission('create_admins')
                           <li class="nav-item">
                              <a href="{{route("dashboard.admins.create")}}" class="nav-link">
                                  <i class="fas fa-plus nav-icon"></i>
                                  <p>{{__("site.create_admins")}}</p>
                              </a>
                          </li>
                          @endpermission

                          @permission('import_admins')
                          <li class="nav-item btn-success">
                            <a href="{{route("dashboard.admins.import")}}" class="nav-link">
                                <i class="fas fa-file-import nav-icon"></i>
                                <p>{{__("site.import_admins")}}</p>
                            </a>
                         </li>
                         @endpermission
                         @permission('export_admins')
                          <li class="nav-item btn-success">
                            <a href="{{route("dashboard.admins.export")}}" class="nav-link">
                                <i class="fas fa-file-export nav-icon"></i>
                                <p>{{__("site.export_admins")}}</p>
                            </a>
                         </li>
                         @endpermission



                      </ul>
                  </li>
                @endrole

            

                  <li class="nav-header">{{__("site.options")}}</li>
                  @role('owner|admin')
                  <li class="nav-item">
                    <a href="{{route("dashboard.settings.index")}}" id="settingsList" class="nav-link">
                        <i class="fas fa-cogs nav-icon text-warning"></i>
                        <p>{{__("site.settings")}}</p>
                    </a>
                  </li>
                  @endrole
                  <li class="nav-item">
                      <a id="logout_a" class="nav-link" style="color:white;cursor:pointer">
                          <i class="nav-icon fa fa-power-off text-danger"></i>
                          <p class="text">{{__("site.logout")}}</p>
                      </a>
                  </li>
                  <form id="logoutForm" action="{{route("logout")}}" method="POST" style="display: none">
                    @csrf
                  </form>
              </ul>
          </nav>
          <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
  </aside>


  <div class="content-wrapper">
       <!-- Content Header (Page header) -->
       <div class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1 class="m-0 text-dark">{{__("site.dashboard")}}</h1>
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                          <li class="breadcrumb-item"><a href='@yield('link')'>@yield('page')</a></li>
                          <li class="breadcrumb-item active">{{__("site.dashboard")}}</li>
                      </ol>
                  </div>
                  <!-- /.col -->
              </div>
              <!-- /.row -->
          </div>
          <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <section class="content">
          <div class="container-fluid">
