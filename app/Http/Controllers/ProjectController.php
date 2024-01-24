<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreProject;
use App\Http\Requests\UpdateProject;
use App\Models\ProjectLeader;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!User::findOrFail(auth()->id())->can('view_projects')) {
            return abort(401);
        }

        if (\request()->ajax()) {
            $data = Project::with(['leaders', 'members']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->editColumn('description', function ($row) {
                    $description = Str::limit($row->description, 200);
                    return $description;
                })
                ->addColumn('leaders', function ($row) {
                    $output = '';
                    foreach ($row->leaders as $leader) {
                        if (!$leader->avatar) {
                            $output .= "<img title='$leader->name' src='" . asset('img/temp-profile-image.jpg') . "' width='30' class='rounded-2 m-1' alt='$leader->name'/>";
                        } else {
                            $output .= "<img title='$leader->name' src='" . asset('storage/' . $leader->avatar) . "' width='30' height='30px'  class='rounded-2 m-1' alt='$leader->name'/>";
                        }
                    }
                    return "<div class='d-flex justify-content-center flex-wrap'>$output</div>";
                })
                ->addColumn('members', function ($row) {
                    $output = '';
                    foreach ($row->members as $member) {
                        if (!$member->avatar) {
                            $output .= "<img title='$member->name' src='" . asset('img/temp-profile-image.jpg') . "' width='30' class='rounded-2 m-1' alt='$member->name'/>";
                        } else {
                            $output .= "<img title='$member->name' src='" . asset('storage/' . $member->avatar) . "' width='30' height='30px'  class='rounded-2 m-1' alt='$member->name'/>";
                        }
                    }
                    return "<div class='d-flex justify-content-center flex-wrap'>$output</div>";
                })
                ->editColumn('priority', function ($row) {
                    $output = '';
                    if ($row->priority === 'low') {
                        $output .= "<span class='badge p-2 bg-primary'>$row->priority</span>";
                    } else if ($row->priority === 'medium') {
                        $output .= "<span class='badge p-2 bg-warning'>$row->priority</span>";
                    } else {
                        $output .= "<span class='badge p-2 bg-danger'>$row->priority</span>";
                    }

                    return "<div class='text-center text-uppercase'>$output</div>";
                })
                ->editColumn('status', function ($row) {
                    $output = '';
                    if ($row->status === 'complete') {
                        $output .= "<span class='badge p-2 bg-primary'>$row->status</span>";
                    } else if ($row->status === 'pending') {
                        $output .= "<span class='badge p-2 bg-warning'>$row->status</span>";
                    } else {
                        $output .= "<span class='badge p-2 bg-secondary'>$row->status</span>";
                    }

                    return "<div class='text-center text-uppercase'>$output</div>";
                })
                ->addColumn('actions', function ($row) {
                    $info = '';
                    $edit = '';
                    $delete = '';

                    if (User::findOrFail(auth()->id())->can('view_projects')) {
                        $info = '<a href="' . route("projects.show", $row->id) . '" class="btn btn-sm btn-primary">' . '<i class="fa-solid fa-circle-info"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('edit_project')) {
                        $edit = '<a href="' . route("projects.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('remove_project')) {
                        $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';
                    }

                    return $info . " " . $edit . " " . $delete;
                })
                ->rawColumns(['action', 'actions', 'priority', 'status', 'leaders', 'members'])
                ->make(true);
        }
        return view('project.index');
    }

    public function show($id)
    {
        if (!User::findOrFail(auth()->id())->can('view_projects')) {
            return abort(401);
        }

        $project = Project::findOrFail($id);

        return view('project.show', [
            'project' => $project
        ]);
    }

    public function create()
    {
        if (!User::findOrFail(auth()->id())->can('create_project')) {
            return abort(401);
        }

        return view('project.create', [
            'employees' => User::orderBy('employee_id')->get()
        ]);
    }

    public function store(StoreProject $request)
    {
        if (!User::findOrFail(auth()->id())->can('create_project')) {
            return abort(401);
        }

        $images = null;
        $files = null;

        if ($request->file('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $imageName = uniqid() . '-' . time() . '.' . $image->getClientOriginalExtension();
                Storage::disk('public')->put('/project/images/' . $imageName, file_get_contents($image));
                $images[] = $imageName;
            }
        }

        if ($request->file('files')) {
            $files = [];
            foreach ($request->file('files') as $file) {
                $fileName = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->put('/project/files/' . $fileName, file_get_contents($file));
                $files[] = $fileName;
            }
        }

        $project = new Project;
        $project->title = $request->title;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->deadline = $request->deadline;
        $project->priority = $request->priority;
        $project->status = $request->status;
        $project->images = $images;
        $project->files = $files;
        $project->save();

        $project->leaders()->sync($request->leaders);
        $project->members()->sync($request->members);

        return redirect(route('projects.index'))->with('created', 'New Project Created Successful');
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_project')) {
            return abort(401);
        }

        $project = Project::findOrFail($id);
        return view('project.edit', [
            'project' => $project,
            'employees' => User::orderBy('employee_id')->get()
        ]);
    }

    public function update($id, UpdateProject $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_project')) {
            return abort(401);
        }

        $project = Project::findOrFail($id);

        $images = null;
        $files = null;

        if ($request->file('images')) {
            $images = [];
            foreach ($project->images as $old_image) {
                Storage::disk('public')->delete('/project/images/' . $old_image);
            }
            foreach ($request->file('images') as $image) {
                $imageName = uniqid() . '-' . time() . '.' . $image->getClientOriginalExtension();
                Storage::disk('public')->put('/project/images/' . $imageName, file_get_contents($image));
                $images[] = $imageName;
            }
        } else {
            $images = $project->images;
        }

        if ($request->file('files')) {
            $files = [];
            foreach ($project->files as $old_file) {
                Storage::disk('public')->delete('/project/files/' . $old_file);
            }
            foreach ($request->file('files') as $file) {
                $fileName = uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->put('/project/files/' . $fileName, file_get_contents($file));
                $files[] = $fileName;
            }
        } else {
            $files = $project->files;
        }

        $project->title = $request->title;
        $project->description = $request->description;
        $project->start_date = $request->start_date;
        $project->deadline = $request->deadline;
        $project->priority = $request->priority;
        $project->status = $request->status;
        $project->images = $images;
        $project->files = $files;
        $project->update();

        $project->leaders()->sync($request->leaders);
        $project->members()->sync($request->members);

        return redirect(route('projects.index'))->with('updated', 'Project Updated Successful');
    }

    public function destroy($id)
    {
        if (!User::findOrFail(auth()->id())->can('remove_project')) {
            return abort(401);
        }

        $project = Project::findOrFail($id);
        $leaders = ProjectLeader::where('project_id', $project->id)->get();
        $members = ProjectMember::where('project_id', $project->id)->get();


        foreach ($leaders as $leader) {
            $leader->delete();
        }

        foreach ($members as $member) {
            $member->delete();
        }

        foreach ($project->images as $old_image) {
            Storage::disk('public')->delete('/project/images/' . $old_image);
        }

        foreach ($project->files as $old_file) {
            Storage::disk('public')->delete('/project/files/' . $old_file);
        }

        $project->delete();

        return "success";
    }
}
