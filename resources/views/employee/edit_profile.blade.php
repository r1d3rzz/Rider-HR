<x-layout>
    <x-slot name="title">
        Edit Profile
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-body">
                    <form action="{{route('employee_profile.update',$employee->id)}}" method="POST" id="profile"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <x-form.input name="name" value="{{$employee->name}}" />
                        <x-form.input name="email" type="email" value="{{$employee->email}}" />
                        <x-form.input name="phone" type="tel" value="{{$employee->phone}}" />
                        <x-form.input name="birthday" value="{{$employee->birthday}}" />
                        <x-form.input name="nrc_number" value="{{$employee->nrc_number}}" />
                        <x-form.textarea name="address" value="{{$employee->address}}" />
                        <div class="row">
                            <div class="col">
                                <div class="mb-4">
                                    <select name="gender" id="gender" class="form-select">
                                        <option disabled selected>- Select Gender -</option>
                                        <option value="male" {{old('gender',$employee->gender)=='male' ?
                                            "selected" : '' }}>Male
                                        </option>
                                        <option value="female" {{old('gender',$employee->gender)=='female' ?
                                            "selected" : '' }}>Female
                                        </option>
                                        <option value="other" {{old('gender',$employee->gender)=='other' ?
                                            "selected" : '' }}>Other
                                        </option>
                                    </select>
                                </div>
                                <x-form.input name="avatar" type="file" optional="true" />
                            </div>
                            <div class="col d-none" id="preview-display">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div>Preview</div>
                                        <div><i class="fa-solid fa-xmark" id="remove-btn"></i></div>
                                    </div>
                                    <div class="card-body p-0" id="preview"></div>
                                </div>
                            </div>
                            @if ($employee->avatar)
                            <div class="col p-0" id="current-image">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <div>Current Avatar</div>
                                    </div>
                                    <div class="card-body p-1">
                                        <img class="img-thumbnail" src="{{asset('storage/'.$employee->avatar)}}"
                                            alt="{{$employee->name}}" id="current-avatar">

                                        <div class="mt-1">
                                            <small>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="delete-avatar"
                                                        name="deleteAvatar">
                                                    <label class="form-check-label user-select-none"
                                                        for="delete-avatar">Delete Avatar</label>
                                                </div>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <x-form.input name="pin_code" type="number" optional="true" />
                        <x-form.input name="password" type="password" optional="true" />

                        <button class="btn btn-primary rounded-1">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $('#avatar').on('change',function(e){
                e.preventDefault();
                let img_length = document.getElementById('avatar').files.length;
                $("#preview-display").removeClass('d-none');
                $("#current-image").addClass('d-none');
                $('#preview').html('');
                $('#delete-avatar').prop("checked",false);

                for(let i = 0; i < img_length; i++){
                    $('#preview').append(`<img class="img-thumbnail" src="${URL.createObjectURL(e.target.files[i])}"/>`);

                    $("#remove-btn").on('click',function(){
                        $("#preview-display").addClass('d-none');
                        $("#current-image").removeClass('d-none');
                        $('#avatar').val('');
                        $('#preview').html('');
                        $('#delete-avatar').prop("checked",false);
                    });
                }
            });
        </script>
        {!! JsValidator::formRequest('App\Http\Requests\UpdateProfile', '#profile'); !!}
    </x-slot>
</x-layout>