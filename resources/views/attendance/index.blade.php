<x-layout>
    <x-slot name="title">
        Attendances
    </x-slot>

    <div class="container p-2">
        <div class="row justify-content-center">
            <div class="col-12">
                @can('create_attendance')
                <div class="mb-2 d-flex">
                    <div class="me-2">
                        <a href="{{route('attendances.create')}}" class="btn btn-primary rounded-1">
                            <i class="fa-solid fa-plus"></i>
                            Create Attendance
                        </a>
                    </div>

                   <div>
                        <a href="{{route('attendances_pdf_download')}}" target="_blank" class="btn btn-danger rounded-1">
                            <i class="fa-solid fa-file-pdf"></i>
                            Download Attendances
                        </a>
                   </div>
                </div>
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
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [ 5,10, 25, 50, -1 ],
                        [ '5 rows','10 rows', '25 rows', '50 rows', 'Show all' ]
                    ],
                    buttons: [
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="fa-solid fa-file-pdf"></i> Pdf',
                            exportOptions:{
                                columns:[0,1,2,3]
                            },
                            className:"btn-sm mb-2 bg-danger",
                            orientation: 'portrait',
                            pageSize: 'A4',
                            customize: function (doc) {
                                        //Remove the title created by datatTables
                                        doc.content.splice(0,1);
                                        var now = new Date();
                                        var datetime = "Last Sync: " + now.getDate() + "/"
                                                        + (now.getMonth()+1)  + "/"
                                                        + now.getFullYear() + " | "
                                                        + now.getHours() + ":"
                                                        + now.getMinutes() + ":"
                                                        + now.getSeconds();
                                        var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();
                                        doc.pageMargins = [20,60,20,30];
                                        doc.defaultStyle.fontSize = 8;
                                        doc.styles.tableHeader.fontSize = 8;
                                        doc.styles.tableBodyEven.alignment = 'center';
                                        doc.styles.tableBodyOdd.alignment = 'center';

                                        doc['header']=(function() {
                                            return {
                                                columns: [
                                                    {
                                                        alignment: 'left',
                                                        italics: true,
                                                        text: 'Attendances',
                                                        fontSize: 12,
                                                        margin: [10,0]
                                                    },
                                                    {
                                                        alignment: 'right',
                                                        fontSize: 10,
                                                        text: 'Report Time: '+datetime
                                                    }
                                                ],
                                                margin: 20
                                            }
                                        });

                                        doc['footer']=(function(page, pages) {
                                            return {
                                                columns: [
                                                    {
                                                        alignment: 'left',
                                                        // text: ['Created on: ', { text: jsDate.toString() }]
                                                        text: "Rider HR"
                                                    },
                                                    {
                                                        alignment: 'right',
                                                        text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
                                                    }
                                                ],
                                                margin: [20,0,20,10]
                                            }
                                        });

                                        var objLayout = {};
                                        objLayout['hLineWidth'] = function(i) { return .5; };
                                        objLayout['vLineWidth'] = function(i) { return .5; };
                                        objLayout['hLineColor'] = function(i) { return '#aaa'; };
                                        objLayout['vLineColor'] = function(i) { return '#aaa'; };
                                        objLayout['paddingLeft'] = function(i) { return 4; };
                                        objLayout['paddingRight'] = function(i) { return 4; };
                                        doc.content[0].layout = objLayout;
                                        doc.content[0].table.widths = '25%';
                                }
                        },
                        {
                            extend:'pageLength',
                            className:"btn-sm mb-2 bg-secondary",
                        },
                        {
                            text: '<i class="fa-solid fa-arrow-rotate-right"></i>',
                            action: function ( e, dt, node, config ) {
                                dt.ajax.reload();
                            },
                            className:"btn-sm mb-2 bg-primary",
                        },
                    ],
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
