<footer class="p-2 container-fluid">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <nav class="d-flex justify-content-around text-center">
                <a class="text-decoration-none" href="{{route('dashboard')}}"><i class="fa-solid fa-home"></i><br><span
                        class="d-none d-md-block">Home</span></a>

                <a class="text-decoration-none" href="{{route('attendance_scan.index')}}"><i
                        class="fa-solid fa-user-clock"></i><br><span class="d-none d-md-block">Attendance</span></a>

                @can('view_employees')
                <a class="text-decoration-none" href="{{route('employees.index')}}"><i
                        class="fa-solid fa-users"></i><br><span class="d-none d-md-block">Employees</span></a>
                @endcan

                <a class="text-decoration-none" href="{{route('my_projects.index')}}"><i
                        class="fa-solid fa-toolbox"></i><br><span class="d-none d-md-block">Projects</span></a>

                @can('view_profile')
                <a class="text-decoration-none" href="{{route('employee_profile.show',auth()->id())}}"><i
                        class="fa-solid fa-user"></i><br><span class="d-none d-md-block">Profile</span></a>
                @endcan
            </nav>
        </div>
    </div>
</footer>
