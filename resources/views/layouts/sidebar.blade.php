<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fa fa-book"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Research</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Nav Item - Department -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
            aria-controls="collapseTwo">
            <i class="fa fa-server"></i>
            <span>Department</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header text-center">Departments</h6>

                <!-- IT & Engineering Department -->
                <span class="collapse-category d-block text-center font-weight-bold text-primary mt-2">IT</span>
                <a class="collapse-item" href="{{ route('research.department', 'Comptech') }}">Comptech</a>
                <a class="collapse-item" href="{{ route('research.department', 'Electronics') }}">Electronics</a>

                <!-- Education Department -->
                <span class="collapse-category d-block text-center font-weight-bold text-primary mt-2">Education</span>
                <a class="collapse-item" href="{{ route('research.department', 'Education') }}">Elementary Education</a>
                <a class="collapse-item" href="{{ route('research.department', 'BSEd-English') }}">BSEd - English</a>
                <a class="collapse-item" href="{{ route('research.department', 'BSEd-Filipino') }}">BSEd - Filipino</a>
                <a class="collapse-item" href="{{ route('research.department', 'BSEd-Mathematics') }}">BSEd - Mathematics</a>
                <a class="collapse-item" href="{{ route('research.department', 'BSEd-Social Studies') }}">BSEd - Social Studies</a>

                <!-- Tourism & Hospitality Management -->
                <span class="collapse-category d-block text-center font-weight-bold text-primary mt-2">HTM</span>
                <a class="collapse-item" href="{{ route('research.department', 'Tourism') }}">Tourism</a>
                <a class="collapse-item" href="{{ route('research.department', 'Hospitality Management') }}">Hospitality Management</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Nav Item - Report -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('report') }}">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Report</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Nav Item - Logout -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
