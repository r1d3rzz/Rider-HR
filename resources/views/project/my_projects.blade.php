<x-layout>
    <x-slot name="title">
        Project
    </x-slot>

    <div class="container-fluid p-2">
        <div class="row justify-content-center">
            <div class="col-10">
                @can('create_project')
                <a href="{{route('projects.create')}}" class="btn btn-primary rounded-1 mb-2">
                    <i class="fa-solid fa-plus"></i>
                    Create Project
                </a>
                @endcan
                <div class="card card-body">
                    <table id="projects" class="table table-bordered nowrap align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th class="no-sort">Title</th>
                                <th class="no-sort">Description</th>
                                <th class="no-sort">Leaders</th>
                                <th class="no-sort">Members</th>
                                <th>start Date</th>
                                <th>Deadline</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th class="hidden">Updated At</th>
                                <th class="no-sort">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
            $(function () {
                var table = $('#projects').DataTable({
                    ajax: "{{ route('my_projects.index') }}",
                    order: [[6, 'desc']],
                    columns: [
                        {data: 'title', name: 'title', class:'fw-bold'},
                        {data: 'description', name: 'description',class:'text-wrap'},
                        {data: 'leaders', name: 'leaders'},
                        {data: 'members', name: 'members'},
                        {data: 'start_date', name: 'start_date'},
                        {data: 'deadline', name: 'deadline'},
                        {data: 'priority', name: 'priority'},
                        {data: 'status', name: 'status'},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'actions', name: 'actions'},
                    ],
                });
            });
        </script>
    </x-slot>
</x-layout>
