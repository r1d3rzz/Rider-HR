<footer class="p-2 container-fluid">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <nav class="d-flex justify-content-around">
                <a href="{{route('dashboard')}}"><i class="fa-solid fa-home"></i></a>

                @can('view_employees')
                <a href="{{route('employees.index')}}"><i class="fa-solid fa-users"></i></a>
                @endcan

                @can('view_departments')
                <a href="{{route('departments.index')}}"><i class="fa-solid fa-sitemap"></i></a>
                @endcan

                @can('view_profile')
                <a href="{{route('employee_profile.show',auth()->id())}}"><i class="fa-solid fa-user"></i></a>
                @endcan
            </nav>
        </div>
    </div>
</footer>
