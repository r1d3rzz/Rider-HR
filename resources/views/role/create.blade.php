<x-layout>
    <x-slot name="title">
        Role Create
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card card-body">
                    <form action="{{route('roles.store')}}" method="POST" id="roles">
                        @csrf
                        <div class="row">
                            <div class="mb-sm-1">
                                <x-form.input name="name" />
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