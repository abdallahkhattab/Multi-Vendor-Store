<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ asset('assets/dashboardAssets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ 'Multi vendor Store',env('APP_NAME') }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->

            @foreach ($items as $item)
            <li class="nav-item">
                <a href="{{ route($item['route']) }}" class="nav-link {{ $item['route'] == $active ? 'active': '' }}">
                  <i class="{{ $item['icon'] }}"></i>
                  <p>{{ $item['title'] }}</p>
                </a>																
              </li>
            @endforeach
        
						
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
       </aside>