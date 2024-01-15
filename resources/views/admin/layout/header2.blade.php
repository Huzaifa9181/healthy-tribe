<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      {{-- <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('admin/home')}}" class="nav-link">Home</a>
      </li> --}}
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
          @if (Auth::user()->role_id === 1)
            <a href="{{ route('admin.edit' , ['id' => Auth::user()->id]) }}" class="dropdown-item">
              <i class="fas fa-user mr-2"></i> Profile
            </a>
          @else
            <a href="{{ url('admin/trainer/profile') }}" class="dropdown-item">
              <i class="fas fa-user mr-2"></i> Profile
            </a>
          @endif
          <a href="{{ url('admin/logout') }}" class="dropdown-item">
            <i class="fas fa-door-open mr-2"></i> Logout
          </a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->