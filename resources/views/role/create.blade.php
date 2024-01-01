<x-layout>
    <x-slot name="title">
        Role Create
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card card-body">
                    <form action="{{route('roles.store')}}" method="POST" id="roles">
                        @csrf
                        <div class="mb-sm-1">
                            <x-form.input name="name" />
                        </div>

                        <div class="mb-2">
                            <label for="permissions" class="fs-6">Permissions</label>
                            <div class="mt-2 row">
                                @forelse ($permissions as $permission)
                                <div class="col-lg-3">
                                    <div class="form-check">
                                        <input class="form-check-input" name="permissions[]" type="checkbox"
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

                        <button class="btn btn-primary rounded-1">Create Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        {!! JsValidator::formRequest('App\Http\Requests\StoreRole', '#roles'); !!}
    </x-slot>
</x-layout>
