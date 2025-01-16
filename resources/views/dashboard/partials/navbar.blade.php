<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Right navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>					
  </ul>
  <div class="navbar-nav pl-2">
    <!-- <ol class="breadcrumb p-0 m-0 bg-white">
      <li class="breadcrumb-item active">Dashboard</li>
    </ol> -->
  </div>
  
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link p-0 pr-3" data-toggle="dropdown" href="#">
        <div class="profile-initials img-circle elevation-2 text-white bg-primary text-center" style="width: 40px; height: 40px; line-height: 40px;">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
      </div>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-3">
        <h4 class="h4 mb-0"><a href="{{ route('dashboard.profile.edit') }}"><strong>{{auth()->user()->name }}</strong></a></h4>
        <div class="mb-3">{{ auth()->user()->email }}</div>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-user-cog mr-2"></i> Settings								
        </a>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-lock mr-2"></i> Change Password
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('logout') }}" class="dropdown-item text-danger"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
       <i class="fas fa-sign-out-alt mr-2"></i> Logout
     </a>
     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
       @csrf
     </form>
         
      </div>
    </li>
  </ul>
</nav>