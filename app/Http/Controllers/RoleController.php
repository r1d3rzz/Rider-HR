<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRole;
use App\Http\Requests\UpdateRole;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (\request()->ajax()) {
            $data = Role::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '<a href="' . route("roles.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';

                    $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';

                    return "<div class='btn-group'>$edit$delete</div>";
                })
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }
        return view('role.index');
    }

    public function create()
    {
        return view('role.create');
    }

    public function store(StoreRole $request)
    {
        $role = new Role;
        $role->name = $request->name;
        $role->save();

        return redirect(route('roles.index'))->with('created', 'New Role Created Successful');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('role.edit', [
            'role' => $role,
        ]);
    }

    public function update($id, UpdateRole $request)
    {
        request()->validate([
            "name" => [Rule::unique('roles', 'name')->ignore($id)]
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->update();

        return redirect(route('roles.index'))->with('updated', 'Role Updated Successful');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return "success";
    }
}
