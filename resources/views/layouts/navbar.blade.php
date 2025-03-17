
<nav class="navbar navbar-expand-lg navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Visible Only on Small Screens) -->
    <button id="sidebarToggleTop" class="btn btn-link d-lg-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Collapsible Navbar -->
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto align-items-center">

            <!-- Search Bar (Visible on Small Screens) -->
            <li class="nav-item d-lg-none">
                <form class="form-inline w-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small"
                            placeholder="Search..." aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </li>

            <!-- User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(auth()->check())
                    <span class="mr-2 d-none d-md-block text-gray-600 text-wrap">
                        <strong class="font-weight-bold">{{ auth()->user()->name }}</strong> <br>
                        <small>
                            @if(auth()->user()->role == 'admin')
                                <i class="fas fa-user-shield text-danger"></i> Admin
                            @else
                                <i class="fas fa-user text-secondary"></i> User
                            @endif
                        </small>
                    </span>
                    @endif
                </a>

                <!-- User Dropdown Menu -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile Settings
                    </a>
                    <!-- <a class="dropdown-item" href="#">
                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a> -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
