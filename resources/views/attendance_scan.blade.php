<x-layout>
    <x-slot name="title">
        Attendance QR Scanner
    </x-slot>


    <div class="container p-lg-3 p-2">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-body">
                    <!-- Button trigger modal -->
                    <div class="text-center p-5">
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
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script src="{{asset('js/qr_scanner/qr-scanner.umd.min.js')}}"></script>
        <script>
            $(document).ready(function () {
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

            });
        </script>
    </x-slot>

</x-layout>