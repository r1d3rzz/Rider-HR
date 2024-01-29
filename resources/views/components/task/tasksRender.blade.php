<div class="row">
    <div class="col-lg-4 mb-2 mb-lg-0">
        <div class="card">
            <div class="card-header text-bg-warning">
                <div>Pending Tasks</div>
            </div>
            <div class="card-body alert alert-warning mb-0">
                @foreach (collect($project->tasks)->sortBy('serial_number')->where('status','pending') as $task)
                <div class="card card-body mb-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fs-5">{{$task->title}}</div>
                        <div>
                            <button class="btn btn-sm btn-warning" id="editTaskBtn" data-task="{{base64_encode(json_encode($task))}}" data-members="{{base64_encode(json_encode(collect($task->members)->pluck('id')->toArray()))}}"><i class="fa-solid fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" id="deleteTaskBtn" data-task_id="{{$task->id}}"><i class="fa-solid fa-trash-alt"></i></button>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div>
                                <span><i class="fa-solid fa-calendar-day"></i></span>
                                <span class="fw-bold">
                                    {{Carbon\Carbon::parse($task->start_date)->format('M d')}}
                                </span>
                            </div>
                            <div>
                                @if ($task->priority == 'low')
                                <div class="badge bg-primary">{{ucfirst($task->priority)}}</div>
                                @elseif ($task->priority == 'medium')
                                <div class="badge bg-warning">{{ucfirst($task->priority)}}</div>
                                @else
                                <div class="badge bg-danger">{{ucfirst($task->priority)}}</div>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if ($task->members)
                                @foreach ($task->members as $member)
                                    @if ($member->avatar)
                                        <img title="{{$member->name}}" src="{{asset('storage/'.$member->avatar)}}" class="rounded-circle" alt="{{$member->name}}" height="40px" width="40">
                                    @else
                                        <img src="/img/temp-profile-image.jpg" class="rounded-circle" alt="temp" width="40">
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <button class="btn bg-white border rounded-2 mt-2 w-100 d-flex justify-content-center align-items-center" id="addPendingTaskBtn">
                    <div class="me-2"><i class="fa-solid fa-plus-circle"></i></div>
                    <div>Add Task</div>
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-2 mb-lg-0">
        <div class="card">
            <div class="card-header text-bg-secondary">
                <div>In Progress Tasks</div>
            </div>
            <div class="card-body alert alert-secondary mb-0">
                @foreach (collect($project->tasks)->sortBy('serial_number')->where('status', 'in_progress') as $task)
                <div class="card card-body mb-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fs-5">{{$task->title}}</div>
                        <div>
                            <button class="btn btn-sm btn-warning" id="editTaskBtn" data-task="{{base64_encode(json_encode($task))}}" data-members="{{base64_encode(json_encode(collect($task->members)->pluck('id')->toArray()))}}"><i class="fa-solid fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" id="deleteTaskBtn" data-task_id="{{$task->id}}"><i class="fa-solid fa-trash-alt"></i></button>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div>
                                <span><i class="fa-solid fa-calendar-day"></i></span>
                                <span class="fw-bold">
                                    {{Carbon\Carbon::parse($task->start_date)->format('M d')}}
                                </span>
                            </div>
                            <div>
                                @if ($task->priority == 'low')
                                <div class="badge bg-primary">{{ucfirst($task->priority)}}</div>
                                @elseif ($task->priority == 'medium')
                                <div class="badge bg-warning">{{ucfirst($task->priority)}}</div>
                                @else
                                <div class="badge bg-danger">{{ucfirst($task->priority)}}</div>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if ($task->members)
                                @foreach ($task->members as $member)
                                    @if ($member->avatar)
                                        <img title="{{$member->name}}" src="{{asset('storage/'.$member->avatar)}}" class="rounded-circle" alt="{{$member->name}}" height="40px" width="40">
                                    @else
                                        <img src="/img/temp-profile-image.jpg" class="rounded-circle" alt="temp" width="40">
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <button class="btn bg-white border rounded-2 mt-2 w-100 d-flex justify-content-center align-items-center" id="addInProgressTaskBtn">
                    <div class="me-2"><i class="fa-solid fa-plus-circle"></i></div>
                    <div>Add Task</div>
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-2 mb-lg-0">
        <div class="card">
            <div class="card-header text-bg-primary">
                <div>Complete Tasks</div>
            </div>
            <div class="card-body alert alert-primary mb-0">
                @foreach (collect($project->tasks)->sortBy('serial_number')->where('status', 'complete') as $task)
                <div class="card card-body mb-1">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fs-5">{{$task->title}}</div>
                        <div>
                            <button class="btn btn-sm btn-warning" id="editTaskBtn" data-task="{{base64_encode(json_encode($task))}}" data-members="{{base64_encode(json_encode(collect($task->members)->pluck('id')->toArray()))}}"><i class="fa-solid fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" id="deleteTaskBtn" data-task_id="{{$task->id}}"><i class="fa-solid fa-trash-alt"></i></button>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div>
                                <span><i class="fa-solid fa-calendar-day"></i></span>
                                <span class="fw-bold">
                                    {{Carbon\Carbon::parse($task->start_date)->format('M d')}}
                                </span>
                            </div>
                            <div>
                                @if ($task->priority == 'low')
                                <div class="badge bg-primary">{{ucfirst($task->priority)}}</div>
                                @elseif ($task->priority == 'medium')
                                <div class="badge bg-warning">{{ucfirst($task->priority)}}</div>
                                @else
                                <div class="badge bg-danger">{{ucfirst($task->priority)}}</div>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if ($task->members)
                                @foreach ($task->members as $member)
                                    @if ($member->avatar)
                                        <img title="{{$member->name}}" src="{{asset('storage/'.$member->avatar)}}" class="rounded-circle" alt="{{$member->name}}" height="40px" width="40">
                                    @else
                                        <img src="/img/temp-profile-image.jpg" class="rounded-circle" alt="temp" width="40">
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <button class="btn bg-white border rounded-2 mt-2 w-100 d-flex justify-content-center align-items-center" id="addCompleteTaskBtn">
                    <div class="me-2"><i class="fa-solid fa-plus-circle"></i></div>
                    <div>Add Task</div>
                </button>
            </div>
        </div>
    </div>
</div>
