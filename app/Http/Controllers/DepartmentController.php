<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartment;
use App\Http\Requests\UpdateDepartment;
use App\Models\Department;
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
        if (\request()->ajax()) {
            $data = Department::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '<a href="' . route("departments.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';

                    $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';

                    return "<div class='btn-group'>$edit$delete</div>";
                })
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }
        return view('department.index');
    }

    public function create()
    {
        return view('department.create');
    }

    public function store(StoreDepartment $request)
    {
        $department = new Department;
        $department->name = $request->name;
        $department->save();

        return redirect(route('departments.index'))->with('created', 'New Department Created Successful');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('department.edit', [
            'department' => $department,
        ]);
    }

    public function update($id, UpdateDepartment $request)
    {
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
        $department = Department::findOrFail($id);
        $department->delete();

        return "success";
    }
}
