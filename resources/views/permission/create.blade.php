<x-layout>
    <x-slot name="title">
        Permission Create
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card card-body">
                    <form action="{{route('permissions.store')}}" method="POST" id="permissions">
                        @csrf
                        <div class="row">
                            <div class="mb-sm-1">
                                <x-form.input name="name" />
                            </div>
                        </div>
                        <button class="btn btn-primary rounded-1">Create Permission</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        {!! JsValidator::formRequest('App\Http\Requests\StorePermission', '#permissions'); !!}
    </x-slot>
</x-layout>