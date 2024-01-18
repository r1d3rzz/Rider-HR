<x-layout>
    <x-slot name="title">
        Attendance QR Scanner
    </x-slot>


    <div class="container p-lg-3 p-2">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card card-body mb-2">
                    <!-- Button trigger modal -->
                    <div class="text-center">
                        <div>
                            <img src="{{asset('img/qr-code-scan.png')}}" alt="sample_logo" class="img-fluid"
                                width="300px">
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#qrScannerModal">
                            Open QR Scanner
                        </button>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="qrScannerModalLabel">Attendance QR Scanner</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <video id="qr_scan_video" width="100%"></video>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary w-100"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form class="mb-2">
                    <div class="card card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <select id="month" class="form-select text-center">
                                    <option disabled selected>-- Select Month --</option>
                                    <option value="01" @if(now()->format('m') == '01') selected @endif>Jan</option>
                                    <option value="02" @if(now()->format('m') == '02') selected @endif>Feb</option>
                                    <option value="03" @if(now()->format('m') == '03') selected @endif>Mar</option>
                                    <option value="04" @if(now()->format('m') == '04') selected @endif>Apr</option>
                                    <option value="05" @if(now()->format('m') == '05') selected @endif>May</option>
                                    <option value="06" @if(now()->format('m') == '06') selected @endif>Jun</option>
                                    <option value="07" @if(now()->format('m') == '07') selected @endif>Jul</option>
                                    <option value="08" @if(now()->format('m') == '08') selected @endif>Aug</option>
                                    <option value="09" @if(now()->format('m') == '09') selected @endif>Sep</option>
                                    <option value="10" @if(now()->format('m') == '10') selected @endif>Oct</option>
                                    <option value="11" @if(now()->format('m') == '11') selected @endif>Nov</option>
                                    <option value="12" @if(now()->format('m') == '12') selected @endif>Dec</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <select id="year" class="form-select text-center">
                                    <option disabled selected>-- Select Year --</option>
                                    @foreach (range(0,4) as $item)
                                        <option @if(now()->format('Y') == now()->format('Y') - $item) selected @endif value="{{now()->format('Y') - $item}}">
                                            {{now()->format('Y') - $item}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <button id="filterBtn" class="btn btn-primary rounded-1 w-100">Search</button>
                            </div>
                        </div>
                        <div id="attendances-overview-table"></div>
                    </div>
                </form>
                <div class="card card-body mb-5">
                    <table id="attendances" class="table table-bordered nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Date</th>
                                <th>Checkin Time</th>
                                <th>Checkout Time</th>
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
        <script src="{{asset('js/qr_scanner/qr-scanner.umd.min.js')}}"></script>
        <script>
            $(document).ready(function () {
                var table = $('#attendances').DataTable({
                    ajax: "/my-attendances",
                    order: [[2, 'desc']],
                    columns: [
                        {data: 'employee_name', name: 'employee_name'},
                        {data: 'date', name: 'date'},
                        {data: 'checkin_time', name: 'checkin_time'},
                        {data: 'checkout_time', name: 'checkout_time'},
                    ],
                });

                var videoElem = document.getElementById('qr_scan_video');
                const openScannerModal = document.getElementById('qrScannerModal');
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                const qrScanner = new QrScanner(videoElem,function(res){
                        if(res){
                            qrScanner.stop();
                            $('.modal').modal('hide');

                            $.ajax({
                                url: '/attendance_scan',
                                type: 'POST',
                                data: {hash_qr_value : res},
                                success: function(res){
                                    if(res.status == "success"){
                                        Toast.fire({
                                            icon: "success",
                                            title: res.message,
                                        });
                                    }else{
                                        Toast.fire({
                                            icon: "error",
                                            title: res.message,
                                        });
                                    }
                                }
                            });
                        }
                    }
                );

                openScannerModal.addEventListener('shown.bs.modal', event => {
                    qrScanner.start();
                })

                openScannerModal.addEventListener('hidden.bs.modal', event => {
                    qrScanner.stop();
                })

                function getAttendancesOverview(){
                    var month = $('#month').val();
                    var year = $('#year').val();

                    $.ajax({
                        url: `/my-attendances-overview-table?month=${month}&year=${year}`,
                        type: "GET",
                        success: function(res){
                            $('#attendances-overview-table').html(res);
                        }
                    });

                    table.ajax.url(`/my-attendances?month=${month}&year=${year}`).load();
                }

                $('#filterBtn').on('click',function(e){
                    e.preventDefault();
                    getAttendancesOverview();
                });

                getAttendancesOverview();

            });
        </script>
    </x-slot>

</x-layout>
