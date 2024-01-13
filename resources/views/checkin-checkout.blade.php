<x-plain-layout>
    <x-slot name="title">
        CheckIn | Checkout
    </x-slot>

    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh">
            <div class="col-lg-6">
                <div class="card card-body text-center">
                    <div>
                        <h5>QR Code</h5>
                        <img
                            src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(240)->generate($hash_qr_value)) !!} ">
                        <p class="text-muted mt-3">Please scan your qr code to Check in or Checkout</p>
                    </div>
                    <hr>
                    <div>
                        <h5>Pin Code</h5>
                        <input type="number" id="pin_code" class="form-control">
                        <p class="text-muted mt-3">Please enter your pin code to Check in or Checkout</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function(){
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

                $('#pin_code').pincodeInput({inputs:6,complete:function(value, e, errorElement){
                    $.ajax({
                        "url": '/checkin',
                        "type": 'POST',
                        "data": {'pin_code':value},
                        "success": function(res){
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

                            $('.pincode-input-container .pincode-input-text').val("");
                            $('.pincode-input-text').first().select().focus();
                        },
                    });

                    // $(errorElement).html("I'm sorry, but the code not correct");
                }});
                $('.pincode-input-text').first().select().focus();
            });
        </script>
    </x-slot>
</x-plain-layout>