<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MyProjectController extends Controller
{
    public function index()
    {
        if (\request()->ajax()) {
            $data = Project::with(['leaders', 'members']);
            $data->where(function ($query) {
                $query
                    ->whereHas('leaders', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->orWhereHas('members', function ($query) {
                        $query->where('user_id', auth()->id());
                    });
            });
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
                    $info = '<a href="' . route("my_projects.show", $row->id) . '" class="btn btn-sm btn-primary">' . '<i class="fa-solid fa-circle-info"></i>' . '</a>';
                    return "<div class='text-center'>$info</div>";
                })
                ->rawColumns(['action', 'actions', 'priority', 'status', 'leaders', 'members'])
                ->make(true);
        }
        return view('project.my_projects');
    }

    public function show($id)
    {
        $project = Project::where('id', $id)
            ->where(function ($query) {
                $query
                    ->whereHas('leaders', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->orWhereHas('members', function ($query) {
                        $query->where('user_id', auth()->id());
                    });
            })->findOrFail($id);

        return view('project.show_my_project', [
            'project' => $project
        ]);
    }
}
