<x-layout>
    <x-slot name="title">
        Rider Human Resources
    </x-slot>


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card card-body">

                    <div class="row">
                        @if (auth()->user()->avatar)
                        <div class="col-lg-4">
                            <img src="{{asset('storage/'.auth()->user()->avatar)}}" class="img-thumbnail" alt="">
                        </div>
                        @endif
                        <div class="col">
                            <table class="table">
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-id-card-clip"></i>
                                        {{auth()->user()->employee_id}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-user-tie"></i>
                                        {{auth()->user()->name}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-envelope"></i>
                                        {{auth()->user()->email}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-briefcase"></i>
                                        {{auth()->user()->department->name}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>


                    <button class="btn btn-sm btn-danger mt-3" id="logout-btn">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>
                        Logout
                    </button>

                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function () {
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

                $(document).on("click","#logout-btn",function(e){
                    e.preventDefault();
                    let id = this.dataset.id;
                    Swal.fire({
                        title: "Do you want to Leave this App?",
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: "Logout",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/logout`,
                                method: 'POST',
                                success: function(res){
                                    window.location.reload();
                                }
                            });
                        }
                    });
                });
             });
        </script>
    </x-slot>

</x-layout>
