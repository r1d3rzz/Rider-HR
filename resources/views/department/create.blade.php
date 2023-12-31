<x-layout>
    <x-slot name="title">
        Department Create
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-body">
                    <form action="{{route('departments.store')}}" method="POST" id="departments">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-sm-1">
                                <x-form.input name="name" />
                            </div>
                        </div>
                        <button class="btn btn-primary rounded-1">Create Department</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        {!! JsValidator::formRequest('App\Http\Requests\StoreDepartment', '#departments'); !!}
    </x-slot>
</x-layout>