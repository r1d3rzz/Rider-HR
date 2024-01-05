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
                            @if ($user->avatar)
                            <img src="{{asset('storage/'.$user->avatar)}}" alt="{{$user->name}}"
                                class="img-fluid my-5 img-thumbnail m-1" />
                            @endif
                            <h5 class="{{$user->avatar ? '' : 'mt-5'}}">{{$user->name}}</h5>
                            <div class="fs-6 fw-bold mb-2">{{$user->department ?
                                $user->department->name :
                                "-"}}</div>
                            <div class="d-flex justify-content-center mb-5">
                                <a href="#"><i class="fa-brands fa-github fa-lg me-3"></i></a>
                                <a href="#"><i class="fa-brands fa-linkedin fa-lg me-3"></i></a>
                                <a href="#"><i class="fa-regular fa-envelope fa-lg"></i></a>
                            </div>
                            <a href="{{route('employee_profile.edit',$user->id)}}" class="text-danger">
                                <span><i class="fa-solid fa-edit"></i></span>
                                Edit Profile
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
                                            {{$user->employee_id}}
                                        </p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Email</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-regular fa-envelope"></i></span>
                                            {{$user->email}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row pt-1">
                                    <div class="col-6 mb-3">
                                        <h6>NRC Number</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-address-card"></i></span>
                                            {{$user->nrc_number}}
                                        </p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Phone</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-mobile"></i></span>
                                            {{$user->phone}}
                                        </p>
                                    </div>
                                </div>
                                <div class="row pt-1">
                                    <div class="col-6 mb-3">
                                        <h6>Birthday</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-cake-candles"></i></span>
                                            {{$user->birthday}}
                                        </p>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Gender</h6>
                                        <p class="text-muted">
                                            @if ($user->gender == "male")
                                            <span><i class="fa-solid fa-mars"></i></span>
                                            @elseif ($user->gender == "female")
                                            <span><i class="fa-solid fa-venus"></i></span>
                                            @else
                                            <span><i class="fa-solid fa-venus-mars"></i></span>
                                            @endif
                                            {{ucfirst($user->gender)}}
                                        </p>
                                    </div>
                                </div>
                                <h6 class="text-success">Company Information</h6>
                                <hr class="mt-0 mb-4">
                                <div class="row pt-1">
                                    <div class="col-6 mb-3">
                                        <h6>Role</h6>
                                        @foreach ($user->roles as $role)
                                        <div>
                                            <span class="text-muted"><i class="fa-solid fa-briefcase"></i></span>
                                            <span class="text-body">{{$role->name}}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Is Present?</h6>
                                        @if ($user->is_present)
                                        <div class="badge bg-primary">Yes</div>
                                        @else
                                        <div class="badge bg-danger">Leave</div>
                                        @endif
                                    </div>
                                    <div class="col-6 mb-3">
                                        <h6>Date of Join</h6>
                                        <p class="text-muted">
                                            <span><i class="fa-solid fa-calendar-check"></i></span>
                                            {{$user->date_of_join}}
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
