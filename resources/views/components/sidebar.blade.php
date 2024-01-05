<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <a href="#">HR Dashboard</a>
            <div id="close-sidebar">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="sidebar-header">
            @if (auth()->user()->avatar)
            <div class="user-pic">
                <img class="img-responsive img-rounded" src="{{asset('storage/'.auth()->user()->avatar)}}"
                    alt="{{auth()->user()->name}}">
            </div>
            @endif
            <div class="user-info">
                <span class="user-name">
                    <strong>{{auth()->user()->name}}</strong>
                </span>
                @foreach (auth()->user()->roles as $role)
                <span class="user-role">{{$role->name}}</span>
                @endforeach
                @if (auth()->user()->department)
                <span class="user-status">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>{{auth()->user()->department->name}}</span>
                </span>
                @endif
            </div>
        </div>

        <!-- sidebar-search  -->
        <div class="sidebar-menu">
            <ul>
                <li class="header-menu">
                    <span>Dashboard</span>
                </li>
                <li>
                    <a href="{{route('dashboard')}}">
                        <i class="fa-solid fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>

                @can('view_company_setting')
                <li>
                    <a href="{{route('company_settings.show',1)}}">
                        <i class="fa-solid fa-building"></i>
                        <span>Company Setting</span>
                    </a>
                </li>
                @endcan

                @can('view_employees')
                <li>
                    <a href="{{route('employees.index')}}">
                        <i class="fa-solid fa-users"></i>
                        <span>Employees</span>
                    </a>
                </li>
                @endcan

                @can('view_departments')
                <li>
                    <a href="{{route('departments.index')}}">
                        <i class="fa-solid fa-sitemap"></i>
                        <span>Departments</span>
                    </a>
                </li>
                @endcan

                @can('view_roles')
                <li>
                    <a href="{{route('roles.index')}}">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Roles</span>
                    </a>
                </li>
                @endcan

                @can('view_permissions')
                <li>
                    <a href="{{route('permissions.index')}}">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Permissions</span>
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    {{-- <div class="sidebar-footer">
        <a href="#">
            <i class="fa fa-bell"></i>
            <span class="badge badge-pill badge-warning notification">3</span>
        </a>
        <a href="#">
            <i class="fa fa-envelope"></i>
            <span class="badge badge-pill badge-success notification">7</span>
        </a>
        <a href="#">
            <i class="fa fa-cog"></i>
            <span class="badge-sonar"></span>
        </a>
        <a href="#">
            <i class="fa fa-power-off"></i>
        </a>
    </div> --}}
</nav>
