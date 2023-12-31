<x-layout>
    <x-slot name="title">
        User Profile
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row">
            <div class="col col-lg-8 mx-auto">
                <div class="card mb-3" style="border-radius: .5rem;">
                    <div class="row g-0">
                        <div class="col-md-4 gradient-custom text-center text-muted"
                            style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                            @if (auth()->user()->avatar)
                            <img src="{{asset('storage/'.auth()->user()->avatar)}}" alt="{{auth()->user()->name}}"
                                class="img-fluid my-5 img-thumbnail m-1" />
                            @endif
                            <h5 class="{{auth()->user()->avatar ? '' : 'mt-5'}}">{{auth()->user()->name}}</h5>
                            <div class="fs-6 fw-bold mb-2">{{auth()->user()->department ?
                                auth()->user()->department->name :
                                "-"}}</div>
                            <div class="d-flex justify-content-center mb-5">
                                <a href="#"><i class="fa-brands fa-github fa-lg me-3"></i></a>
                                <a href="#"><i class="fa-brands fa-linkedin fa-lg me-3"></i></a>
                                <a href="#"><i class="fa-regular fa-envelope fa-lg"></i></a>
                            </div>
                            <a href="/employees/{{auth()->user()->id}}/edit" class="text-danger">
                                <span><i class="fa-solid fa-edit"></i></span>
                                Edit Employee
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <h6 class="text-primary">Information</h6>
                                <hr class="mt-0 mb-4">
                                <div class="row pt-1">
                                    <div class="col-6 mb-3">
                                        <h6>Employee ID</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-id-badge"></i></span>
                                            {{auth()->user()->employee_id}}
                                        </p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Email</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-regular fa-envelope"></i></span>
                                            {{auth()->user()->email}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row pt-1">
                                    <div class="col-6 mb-3">
                                        <h6>NRC Number</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-address-card"></i></span>
                                            {{auth()->user()->nrc_number}}
                                        </p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Phone</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-mobile"></i></span>
                                            {{auth()->user()->phone}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row pt-1">
                                    <div class="col-6 mb-3">
                                        <h6>Birthday</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-cake-candles"></i></span>
                                            {{auth()->user()->birthday}}
                                        </p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Gender</h6>
                                        <p class="text-muted">
                                            @if (auth()->user()->gender == "male")
                                            <span><i class="fa-solid fa-mars"></i></span>
                                            @elseif (auth()->user()->gender == "female")
                                            <span><i class="fa-solid fa-venus"></i></span>
                                            @else
                                            <span><i class="fa-solid fa-venus-mars"></i></span>
                                            @endif
                                            {{ucfirst(auth()->user()->gender)}}
                                        </p>
                                    </div>
                                </div>
                                <h6 class="text-success">Company Information</h6>
                                <hr class="mt-0 mb-4">
                                <div class="row pt-1">
                                    <div class="col-6 mb-3">
                                        <h6>Is Present?</h6>
                                        @if (auth()->user()->is_present)
                                        <div class="badge bg-primary">Yes</div>
                                        @else
                                        <div class="badge bg-danger">Leave</div>
                                        @endif
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Date of Join</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-briefcase"></i></span>
                                            {{auth()->user()->date_of_join}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
