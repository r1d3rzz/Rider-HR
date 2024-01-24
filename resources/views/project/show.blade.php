<x-layout>
    <x-slot name="title">
        Projects | Detail
    </x-slot>

    <div class="container-fluid p-2 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-body mb-2 mb-lg-0">
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
                </div>

                <div class="card card-body my-3 @if(!$project->images || !$project->images) d-none @endif">
                    <div class="mb-2">
                        <div id="images">
                            @if ($project->images)
                                <div class="fs-5 fw-bold">Images</div>
                                @foreach ($project->images as $image)
                                    <img src="{{asset('/storage/project/images/'.$image)}}" class="img-thumbnail m-2" width="100">
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="mb-2">
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
            <div class="col-lg-4">
                <div class="card card-body">
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

    <x-slot name="script">
        <script>
            $(document).ready(function(){
                new Viewer(document.getElementById('images'));
            });
        </script>
    </x-slot>
</x-layout>
