<x-layout>
    <x-slot name="title">
        Project Edit
    </x-slot>

    <div class="container p-2 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card card-body">
                    <form action="{{route('projects.update',$project->id)}}" method="POST" id="projects" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-sm-1">
                                <x-form.input name="title" value="{{$project->title}}"/>

                                <div class="mb-4">
                                    <select name="leaders[]" multiple class="form-select single-select-field" id="leaders" data-placeholder="Select Project Leaders">
                                        <option></option>
                                        @foreach ($employees as $employee)
                                            <option @selected(in_array($employee->id,collect($project->leaders)->pluck('id')->toArray())) value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <select name="members[]" multiple class="form-select single-select-field" id="members" data-placeholder="Select Project Members">
                                        <option></option>
                                        <@foreach ($employees as $employee)
                                        <option @selected(in_array($employee->id,collect($project->members)->pluck('id')->toArray())) value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <x-form.textarea name="description" value="{{$project->description}}"/>
                                <x-form.input name="start_date" class="project_date" value="{{$project->start_date}}"/>
                                <x-form.input name="deadline" class="project_date" value="{{$project->deadline}}"/>

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
                                        <option @selected($project->priority === 'low') value="low">Low</option>
                                        <option @selected($project->priority === 'medium') value="medium">Medium</option>
                                        <option @selected($project->priority === 'high') value="high">High</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <select name="status" class="form-select single-select-field" id="status" data-placeholder="Select Status">
                                        <option></option>
                                        <option @selected($project->status === 'pending') value="pending">Pendign</option>
                                        <option @selected($project->status === 'in_progress') value="in_progress">In Progress</option>
                                        <option @selected($project->status === 'complete') value="complete">Complete</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary rounded-1">Update Project</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5 @if(!$project->images || !$project->files) d-none @endif ">
                <div class="card card-body">
                    <div>
                        <div class="fs-6 mb-2" id="preview-images">Current Images</div>
                        <div id="imagesContainer">
                            @if ($project->images)
                                @foreach ($project->images as $image)
                                <img src="{{asset('storage/project/images/'.$image)}}" alt="{{$image}}" width="100" class="me-2">
                                @endforeach
                            @endif
                        </div>
                        <div class="my-3"></div>
                        <div class="fs-6 mb-2" id="preview-images">Current Files</div>
                        <div class="d-flex">
                            @if ($project->files)
                                @foreach ($project->files as $file)
                                <a target="_blank" href="{{asset('storage/project/files/'.$file)}}" class="me-4 fs-1 text-danger text-center text-decoration-none">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <div class="fs-6 text-muted">File {{$loop->iteration}}</div>
                                </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="mt-3 d-none" id="previewContainer">
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span class="ms-2">If you add new Images/Files the old data will Delete!</span>
                        </div>

                        <div>
                            <div class="fs-6 mb-3 d-none" id="previewImages">Preview Images</div>
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
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function(){
                new Viewer(document.getElementById('imagesContainer'));

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
                    $('#previewContainer').removeClass('d-none');
                    $('#previewImages').removeClass('d-none');
                    let img_length = document.getElementById('images').files.length;
                    $('#preview').html('');
                    for(let i = 0; i < img_length; i++){
                        $('#preview').append(`<img class="img-thumbnail me-1 mb-1" src="${URL.createObjectURL(e.target.files[i])}" width="150" heigh="100px"/>`);
                    }
                });

                $('#files').on('change',function(e){
                    e.preventDefault();
                    $('#previewContainer').removeClass('d-none');
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
