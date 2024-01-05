<x-layout>
    <x-slot name="title">
        Company Setting
    </x-slot>

    <div class="container p-2">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card card-body">
                    <div class="row">
                        <div class="col-lg-6 my-3">
                            <label class="h5 fw-bold text-muted">Company Name</label>
                            <div class="fs-6">
                                <i class="fa-solid fa-building"></i>
                                {{$company->name}}
                            </div>
                        </div>

                        <div class="col-lg-6 my-3">
                            <label class="h5 fw-bold text-muted">Company Mail</label>
                            <div class="fs-6">
                                <i class="fa-solid fa-envelope"></i>
                                {{$company->email}}
                            </div>
                        </div>

                        <div class="col-lg-6 my-3">
                            <label class="h5 fw-bold text-muted">Company Phone</label>
                            <div class="fs-6">
                                <i class="fa-solid fa-headset"></i>
                                {{$company->phone}}
                            </div>
                        </div>

                        <div class="col-lg-6 my-3">
                            <label class="h5 fw-bold text-muted">Company Address</label>
                            <div class="fs-6">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$company->address}}
                            </div>
                        </div>

                        <div class="col-lg-6 my-3">
                            <label class="h5 fw-bold text-muted">Office Start Time</label>
                            <div class="fs-6">
                                <i class="fa-regular fa-clock"></i>
                                {{$company->office_start_time}}
                            </div>
                        </div>

                        <div class="col-lg-6 my-3">
                            <label class="h5 fw-bold text-muted">Office End Time</label>
                            <div class="fs-6">
                                <i class="fa-solid fa-clock"></i>
                                {{$company->office_end_time}}
                            </div>
                        </div>

                        <div class="col-lg-6 my-3">
                            <label class="h5 fw-bold text-muted">Break Start Time</label>
                            <div class="fs-6">
                                <i class="fa-solid fa-mug-hot"></i>
                                {{$company->break_start_time}}
                            </div>
                        </div>

                        <div class="col-lg-6 my-3">
                            <label class="h5 fw-bold text-muted">Break End Time</label>
                            <div class="fs-6">
                                <i class="fa-solid fa-mug-saucer"></i>
                                {{$company->break_end_time}}
                            </div>
                        </div>
                    </div>
                </div>

                @can('edit_company_setting')
                <div class="mt-2">
                    <a href="{{route('company_settings.edit',1)}}" class="btn btn-primary bg-gradient w-100">Edit
                        Company Setting</a>
                </div>
                @endcan
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function(){
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
