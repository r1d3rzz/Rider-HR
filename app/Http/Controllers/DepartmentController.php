<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartment;
use App\Http\Requests\UpdateDepartment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!User::findOrFail(auth()->id())->can('view_departments')) {
            return abort(401);
        }

        if (\request()->ajax()) {
            $data = Department::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '';
                    $delete = '';

                    if (User::findOrFail(auth()->id())->can('edit_department')) {
                        $edit = '<a href="' . route("departments.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('remove_department')) {
                        $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';
                    }

                    return "<div class='btn-group'>$edit$delete</div>";
                })
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }
        return view('department.index');
    }

    public function create()
    {
        if (!User::findOrFail(auth()->id())->can('create_department')) {
            return abort(401);
        }

        return view('department.create');
    }

    public function store(StoreDepartment $request)
    {
        if (!User::findOrFail(auth()->id())->can('create_department')) {
            return abort(401);
        }

        $department = new Department;
        $department->name = $request->name;
        $department->save();

        return redirect(route('departments.index'))->with('created', 'New Department Created Successful');
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_department')) {
            return abort(401);
        }

        $department = Department::findOrFail($id);
        return view('department.edit', [
            'department' => $department,
        ]);
    }

    public function update($id, UpdateDepartment $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_department')) {
            return abort(401);
        }

        request()->validate([
            "name" => [Rule::unique('departments', 'name')->ignore($id)]
        ]);

        $department = Department::findOrFail($id);
        $department->name = $request->name;
        $department->update();

        return redirect(route('departments.index'))->with('updated', 'Department Updated Successful');
    }

    public function destroy($id)
    {
        if (!User::findOrFail(auth()->id())->can('remove_department')) {
            return abort(401);
        }

        $department = Department::findOrFail($id);
        $department->delete();

        return "success";
    }
}
