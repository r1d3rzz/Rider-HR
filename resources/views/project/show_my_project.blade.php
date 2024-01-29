<x-layout>
    <x-slot name="title">
        Projects | Detail & Tasks
    </x-slot>

    <div class="container-fluid p-2 px-lg-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 mb-2 mb-lg-0">
                <div class="card card-body">
                    <div class="fs-4 fw-bold">{{$project->title}}</div>
                    <div class="fs-6 mb-2">
                        <span class="d-block d-lg-inline">
                            <span>Start Date: <span class="text-muted">{{$project->start_date}}</span></span>
                        </span>
                        <span class="d-none d-lg-inline">,</span>
                        <span class="d-block d-lg-inline">
                            <span>Deadline: <span class="text-muted">{{$project->deadline}}</span></span>
                        </span>
                    </div>
                    <div class="fs-6 mb-2">
                        <span>
                            Priority:
                            @if ($project->priority == 'high')
                            <span class="badge bg-danger">{{$project->priority}}</span>
                            @elseif ($project->priority == 'medium')
                            <span class="badge bg-warning">{{$project->priority}}</span>
                            @else
                            <span class="badge bg-primary">{{$project->priority}}</span>
                            @endif
                        </span>
                        <span>,</span>
                        <span>
                            Status:
                            @if ($project->status == 'in_progress')
                            <span class="badge bg-secondary">{{$project->status}}</span>
                            @elseif ($project->status == 'pending')
                            <span class="badge bg-warning">{{$project->status}}</span>
                            @else
                            <span class="badge bg-primary">{{$project->status}}</span>
                            @endif
                        </span>
                    </div>
                    <div class="mb-2">
                        <div class="fs-5">Description</div>
                        <p class="fs-6 mt-2">
                            {{$project->description}}
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="fs-5">Team Leaders</div>
                            <div class="d-flex mt-3">
                                @foreach ($project->leaders as $leader)
                                <div class="text-center me-2">
                                    @if ($leader->avatar)
                                    <img src="{{asset('storage/'.$leader->avatar)}}" alt="{{$leader->name}}" width="60" class="mb-1">
                                    @else
                                    <img src="/img/temp-profile-image.jpg" alt="{{$leader->name}}" width="60" class="mb-1">
                                    @endif

                                    <small class="d-block">{{$leader->name}}</small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="fs-5 mt-3">Team Members</div>
                            <div class="d-flex mt-3">
                                @foreach ($project->members as $member)
                                <div class="text-center me-2">
                                    @if ($member->avatar)
                                    <img src="{{asset('storage/'.$member->avatar)}}" alt="{{$member->name}}" width="60" class="mb-1">
                                    @else
                                    <img src="/image/temp_profile_img.jpg" alt="{{$member->name}}" width="60" class="mb-1">
                                    @endif

                                    <small class="d-block">{{$member->name}}</small>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="@if(!$project->images || !$project->images) d-none @endif">
                    <div class="mb-2 card card-body">
                        <div id="images">
                            @if ($project->images)
                                <div class="fs-5 fw-bold">Images</div>
                                @foreach ($project->images as $image)
                                    <img src="{{asset('/storage/project/images/'.$image)}}" class="img-thumbnail m-2" width="100">
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="mb-2 card card-body">
                        @if ($project->files)
                            <div class="fs-5 fw-bold">Files</div>
                            <div class="d-flex mt-3">
                                @foreach ($project->files as $file)
                                    <a href="{{asset('/storage/project/files/'.$file)}}" target="_blank" class="me-3 text-decoration-none text-danger">
                                        <i class="fa-solid fa-file-pdf fs-1"></i>
                                        <div class="fs-6 text-dark">File {{$loop->iteration}}</div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col-lg-10">
                <h3 class="text-muted">Tasks</h3>
                <div id="tasksRender"></div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function(){
                var taskMembersOption = "";
                var project_id = {{$project->id}};
                var leaders = @json($project->leaders);
                var members = @json($project->members);

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                leaders.forEach((leader)=>{
                    taskMembersOption += `<option value='${leader.id}'>${leader.name}</option>`;
                });

                members.forEach((member)=>{
                    taskMembersOption += `<option value='${member.id}'>${member.name}</option>`;
                });

                new Viewer(document.getElementById('images'));

                function getTasks(){
                    $.ajax({
                        url: `/tasksRender?project_id=${project_id}`,
                        type: "GET",
                        success:function(res){
                            $('#tasksRender').html(res);
                        }
                    });
                }

                function storeTask(title,status){
                    Swal.fire({
                        title: `${title} Task`,
                        showCancelButton: true,
                        confirmButtonText: "Save",
                        html: `
                            <form id="taskForm" class="text-start">
                                <input type="hidden" value="${project_id}" name="project_id"/>
                                <input type="hidden" value="${status}" name="status"/>
                                <x-form.input name="title"/>
                                <div class="mb-4">
                                    <select name="members[]" multiple class="form-select single-select-field" id="members" data-placeholder="Select Task Members">
                                        <option></option>
                                        ${taskMembersOption}
                                    </select>
                                </div>
                                <x-form.textarea name="description"/>
                                <x-form.input name="start_date" class="task_date"/>
                                <x-form.input name="deadline" class="task_date"/>
                                <div class="mb-4">
                                    <x-label name="priority"/>
                                    <select name="priority" class="form-select single-select-field" id="priority" data-placeholder="Select Priority">
                                        <option></option>
                                        <option selected value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                            </form>
                        `,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{route('tasks.store')}}",
                                type: "POST",
                                data: $('#taskForm').serialize(),
                                success: function(res){
                                    getTasks();

                                    Swal.fire({
                                        title: "Saved",
                                        icon: "success"
                                    });
                                }
                            });

                        }
                    });

                    $('.task_date').daterangepicker({
                        "singleDatePicker": true,
                        "showDropdowns": true,
                        "autoApply": true,
                        "locale": {
                            "format": "YYYY-MM-DD",
                        }
                    });

                    $('.single-select-field').select2( {
                        theme: "bootstrap-5",
                        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                        placeholder: $( this ).data( 'placeholder' ),
                    });
                }

                getTasks();

                $(document).on('click','#addPendingTaskBtn',function(){
                    storeTask('Pending','pending');
                });

                $(document).on('click','#addInProgressTaskBtn',function(){
                    storeTask('In Progress','in_progress');
                });

                $(document).on('click','#addCompleteTaskBtn',function(){
                    storeTask('Complete','complete');
                });

                $(document).on('click','#editTaskBtn',function(){
                    var task = JSON.parse(atob($(this).data('task')));
                    var task_members = JSON.parse(atob($(this).data('members')));
                    var selectedTaskMembers = "";

                    leaders.forEach((leader)=>{
                        selectedTaskMembers += `<option ${task_members.includes(leader.id) ? 'selected' : ''} value="${leader.id}">${leader.name}</option>`;
                    });

                    members.forEach((member)=>{
                        selectedTaskMembers += `<option ${task_members.includes(member.id) ? 'selected' : ''} value="${member.id}">${member.name}</option>`;
                    });

                    Swal.fire({
                        title: `Edit Task`,
                        showCancelButton: true,
                        confirmButtonText: "Update",
                        html: `
                            <form id="editTaskForm" class="text-start">
                                <x-form.input name="title" value="${task.title}"/>
                                <div class="mb-4">
                                    <select name="members[]" multiple class="form-select single-select-field" id="members" data-placeholder="Select Task Members">
                                        <option></option>
                                        ${selectedTaskMembers}
                                    </select>
                                </div>
                                <x-form.textarea name="description" value="${task.description}"/>
                                <x-form.input name="start_date" class="task_date" value="${task.start_date}"/>
                                <x-form.input name="deadline" class="task_date" value="${task.deadline}"/>
                                <div class="mb-4">
                                    <x-label name="priority"/>
                                    <select name="priority" class="form-select single-select-field" id="priority" data-placeholder="Select Priority">
                                        <option></option>
                                        <option ${task.priority == 'low' ? 'selected' : '' } value="low">Low</option>
                                        <option ${task.priority == 'medium' ? 'selected' : '' } value="medium">Medium</option>
                                        <option ${task.priority == 'high' ? 'selected' : '' } value="high">High</option>
                                    </select>
                                </div>
                            </form>
                        `,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/tasks/${task.id}`,
                                type: "PUT",
                                data: $('#editTaskForm').serialize(),
                                success: function(res){
                                    getTasks();

                                    Swal.fire({
                                        title: "Updated",
                                        icon: "success"
                                    });
                                }
                            });

                        }
                    });

                    $('.task_date').daterangepicker({
                        "singleDatePicker": true,
                        "showDropdowns": true,
                        "autoApply": true,
                        "locale": {
                            "format": "YYYY-MM-DD",
                        }
                    });

                    $('.single-select-field').select2( {
                        theme: "bootstrap-5",
                        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                        placeholder: $( this ).data( 'placeholder' ),
                    });
                });

                $(document).on('click','#deleteTaskBtn',function(){
                    var task_id = $(this).data('task_id');
                    Swal.fire({
                        title: "Do you want to remove this Task?",
                        showDenyButton: false,
                        showCancelButton: true,
                        confirmButtonText: "Remove",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/tasks/${task_id}`,
                                method: 'DELETE',
                            }).done(function(){
                                getTasks();

                                Toast.fire({
                                    icon: "success",
                                    title: "Removed Successful"
                                });
                            });
                        }
                    });
                });
            });
        </script>
    </x-slot>
</x-layout>
