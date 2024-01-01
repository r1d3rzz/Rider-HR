<x-layout>
    <x-slot name="title">
        Role Edit
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-body">
                    <form action="{{route('roles.update',$role->id)}}" method="POST" id="roles">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-sm-1">
                                <x-form.input name="name" value="{{$role->name}}" />
                            </div>
                        </div>
                        <button class="btn btn-primary rounded-1">Update Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        {!! JsValidator::formRequest('App\Http\Requests\UpdateRole', '#roles'); !!}
    </x-slot>
</x-layout>