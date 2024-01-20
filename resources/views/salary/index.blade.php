<x-layout>
    <x-slot name="title">
        Employees Salaries
    </x-slot>

    <div class="container p-2">
        <div class="row justify-content-center">
            <div class="col-12">
                @can('create_salary')
                <a href="{{route('salaries.create')}}" class="btn btn-primary rounded-1 mb-2">
                    <i class="fa-solid fa-plus"></i>
                    Create Salary
                </a>
                @endcan
                <div class="card card-body">
                    <table id="salaries" class="table table-bordered nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Month</th>
                                <th>Year</th>
                                <th>Amount (MMK)</th>
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
                var table = $('#salaries').DataTable({
                    ajax: "{{ route('salaries.index') }}",
                    order: [[4, 'desc']],
                    columns: [
                        {data: 'employee_name', name: 'employee_name'},
                        {data: 'month', name: 'month'},
                        {data: 'year', name: 'year'},
                        {data: 'amount', name: 'amount'},
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
                        title: "Do you want to remove this Employee Salary?",
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: "Remove",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/salaries/${id}`,
                                method: 'DELETE',
                            }).done(function(){
                                table.ajax.reload();

                                Toast.fire({
                                    icon: "success",
                                    title: "Removed Salary Successful"
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
