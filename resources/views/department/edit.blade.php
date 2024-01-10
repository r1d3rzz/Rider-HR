<x-layout>
    <x-slot name="title">
        Department Edit
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card card-body">
                    <form action="{{route('departments.update',$department->id)}}" method="POST" id="departments">
                        @csrf
                        @method('PUT')
                        <x-form.input name="name" value="{{$department->name}}" />

                        <button class="btn btn-primary rounded-1">Update Department</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        {!! JsValidator::formRequest('App\Http\Requests\UpdateDepartment', '#departments'); !!}
    </x-slot>
</x-layout>