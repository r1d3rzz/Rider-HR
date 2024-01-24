<x-layout>
    <x-slot name="title">
        Project Create
    </x-slot>

    <div class="container p-2 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 mb-2 mb-lg-0">
                <div class="card card-body">
                    <form action="{{route('projects.store')}}" method="POST" id="projects" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-sm-1">
                                <x-form.input name="title" />

                                <div class="mb-4">
                                    <select name="leaders[]" multiple class="form-select single-select-field" id="leaders" data-placeholder="Select Project Leaders">
                                        <option></option>
                                        @foreach ($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <select name="members[]" multiple class="form-select single-select-field" id="members" data-placeholder="Select Project Members">
                                        <option></option>
                                        <@foreach ($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <x-form.textarea name="description" />
                                <x-form.input name="start_date" class="project_date"/>
                                <x-form.input name="deadline" class="project_date"/>

                                <div class="mb-4">
                                    <label for="images" class="fs-6">Select Images <span class="text-muted small">(Only PNG, JPG, JPEG)</span></label>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
                                </div>

                                <div class="mb-4">
                                    <label for="files" class="fs-6">Select Files <span class="text-muted small">(Only PDF)</span></label>
                                    <input type="file" name="files[]" id="files" class="form-control" multiple accept="application/pdf">
                                </div>

                                <div class="mb-4">
                                    <select name="priority" class="form-select single-select-field" id="priority" data-placeholder="Select Priority">
                                        <option></option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <select name="status" class="form-select single-select-field" id="status" data-placeholder="Select Status">
                                        <option></option>
                                        <option value="pending">Pendign</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="complete">Complete</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary rounded-1">Create Project</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 d-none" id="preview-container">
                <div class="card card-body">
                    <div>
                        <div class="fs-6 mb-3 d-none" id="preview-images">Preview Images</div>
                        <div id="preview" class="mb-4 d-flex justify-start flex-wrap"></div>
                    </div>

                    <div>
                        <div class="fs-6 mb-3 d-none" id="preview-files">Preview Files</div>
                        <div id="preview-file" class="mb-4 d-flex justify-start flex-wrap"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function(){
                $('.single-select-field').select2( {
                    theme: "bootstrap-5",
                    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                    placeholder: $( this ).data( 'placeholder' ),
                });

                $('.project_date').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "autoApply": true,
                    "locale": {
                        "format": "YYYY-MM-DD",
                    }
                });

                $('#images').on('change',function(e){
                    e.preventDefault();
                    $('#preview-container').removeClass('d-none');
                    $('#preview-images').removeClass('d-none');
                    let img_length = document.getElementById('images').files.length;
                    $('#preview').html('');
                    for(let i = 0; i < img_length; i++){
                        $('#preview').append(`<img class="img-thumbnail me-1 mb-1" src="${URL.createObjectURL(e.target.files[i])}" width="150" heigh="100px"/>`);
                    }
                });

                $('#files').on('change',function(e){
                    e.preventDefault();
                    $('#preview-container').removeClass('d-none');
                    $('#preview-files').removeClass('d-none');
                    let file_length = document.getElementById('files').files.length;
                    $('#preview-file').html('');
                    for(let i = 0; i < file_length; i++){
                        $('#preview-file').append(`
                        <a href="${URL.createObjectURL(e.target.files[i])}" class="fs-1 me-2" target="_blank">
                            <i class="fa-solid fa-file-pdf text-danger"></i>
                        </a>
                        `);
                    }
                });
            });
        </script>
        {!! JsValidator::formRequest('App\Http\Requests\StoreProject', '#projects'); !!}
    </x-slot>
</x-layout>
