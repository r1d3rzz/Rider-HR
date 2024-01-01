<x-layout>
    <x-slot name="title">
        Employees
    </x-slot>

    <div class="container p-2">
        <div class="row justify-content-center">
            <div class="col-12">
                <a href="{{route('employees.create')}}" class="btn btn-primary rounded-1 mb-2">
                    <i class="fa-solid fa-user-plus"></i>
                    Create Employee
                </a>
                <div class="card card-body">
                    <table id="employees" class="table table-bordered nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th class="no-sort">Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="no-sort">Phone</th>
                                <th class="no-sort">Department</th>
                                <th class="no-sort">Is Present</th>
                                <th class="hidden">Updated At</th>
                                <th class="no-sort">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
            $(function () {
                var table = $('#employees').DataTable({
                    ajax: "{{ route('employees.index') }}",
                    order: [[7, 'desc']],
                    columns: [
                        {data: 'employee_id', name: 'employee_id'},
                        {data: 'avatar', name: 'avatar',class: 'text-center'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'phone', name: 'phone'},
                        {data: 'department', name: 'department'},
                        {data: 'is_present', name: 'is_present'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'actions', name: 'actions'},
                    ],
                });

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                $(document).on("click","#del-btn",function(e){
                    e.preventDefault();
                    let id = this.dataset.id;
                    Swal.fire({
                        title: "Do you want to remove this Employee?",
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: "Remove",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/employees/${id}`,
                                method: 'DELETE',
                            }).done(function(){
                                table.ajax.reload();

                                Toast.fire({
                                    icon: "success",
                                    title: "Removed Successful"
                                });
                            });
                        }
                    });
                });

                @if(session('created'))
                    Toast.fire({
                        icon: "success",
                        title: "{{session('created')}}"
                    });
                @endif

                @if(session('updated'))
                    Toast.fire({
                        icon: "success",
                        title: "{{session('updated')}}"
                    });
                @endif
            });
        </script>
    </x-slot>
</x-layout>
