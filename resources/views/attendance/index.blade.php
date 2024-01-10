<x-layout>
    <x-slot name="title">
        Attendances
    </x-slot>

    <div class="container p-2">
        <div class="row justify-content-center">
            <div class="col-12">
                @can('create_attendance')
                <a href="{{route('attendances.create')}}" class="btn btn-primary rounded-1 mb-2">
                    <i class="fa-solid fa-plus"></i>
                    Create Attendance
                </a>
                @endcan
                <div class="card card-body">
                    <table id="attendances" class="table table-bordered nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Date</th>
                                <th>Checkin Time</th>
                                <th>Checkout Time</th>
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
                var table = $('#attendances').DataTable({
                    ajax: "{{ route('attendances.index') }}",
                    order: [[2, 'desc']],
                    columns: [
                        {data: 'employee_name', name: 'employee_name'},
                        {data: 'date', name: 'date'},
                        {data: 'checkin_time', name: 'checkin_time'},
                        {data: 'checkout_time', name: 'checkout_time'},
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
                        title: "Do you want to remove this Attendance?",
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: "Remove",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/attendances/${id}`,
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