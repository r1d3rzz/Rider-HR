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

                        <div class="mb-sm-1">
                            <x-form.input name="name" value="{{$role->name}}" />
                        </div>

                        <div class="mb-2">
                            <label for="permissions" class="fs-6">Permissions</label>
                            <div class="mt-2 row">
                                @forelse ($permissions as $permission)
                                <div class="col-lg-3">
                                    <div class="form-check">
                                        <input @if(in_array($permission->id,$old_permissions)) checked @endif
                                        class="form-check-input" name="permissions[]" type="checkbox"
                                        value="{{$permission->name}}" id="{{$permission->id}}">
                                        <label class="form-check-label" for="{{$permission->id}}">
                                            {{$permission->name}}
                                        </label>
                                    </div>
                                </div>
                                @empty
                                <div class="alert alert-warning text-center">Empty Permissions</div>
                                @endforelse
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
