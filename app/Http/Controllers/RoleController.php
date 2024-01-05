<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRole;
use App\Http\Requests\UpdateRole;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
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
        if (!User::findOrFail(auth()->id())->can('view_roles')) {
            return abort(401);
        }

        if (\request()->ajax()) {
            $data = Role::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '';
                    $delete = '';

                    if (User::findOrFail(auth()->id())->can('edit_role')) {
                        $edit = '<a href="' . route("roles.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('remove_role')) {
                        $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';
                    }

                    return "<div class='btn-group'>$edit$delete</div>";
                })
                ->addColumn('permissions', function ($row) {
                    $lists = '';
                    foreach ($row->permissions as $permission) {
                        $lists .= "<span class='badge bg-primary m-1'>" . $permission->name . "</span>";
                    }
                    $listsContainer = "<div class='d-flex flex-wrap'>$lists</div>";

                    return $listsContainer;
                })
                ->rawColumns(['action', 'actions', 'permissions'])
                ->make(true);
        }
        return view('role.index');
    }

    public function create()
    {
        if (!User::findOrFail(auth()->id())->can('create_role')) {
            return abort(401);
        }

        return view('role.create', ['permissions' => Permission::all()]);
    }

    public function store(StoreRole $request)
    {
        if (!User::findOrFail(auth()->id())->can('create_role')) {
            return abort(401);
        }

        $role = new Role;
        $role->name = $request->name;
        $role->save();

        $role->givePermissionTo($request->permissions);

        return redirect(route('roles.index'))->with('created', 'New Role Created Successful');
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_role')) {
            return abort(401);
        }

        $role = Role::findOrFail($id);
        return view('role.edit', [
            'role' => $role,
            'permissions' => Permission::all(),
            'old_permissions' => $role->permissions()->pluck('id')->toArray(),
        ]);
    }

    public function update($id, UpdateRole $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_role')) {
            return abort(401);
        }

        request()->validate([
            "name" => [Rule::unique('roles', 'name')->ignore($id)]
        ]);

        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->update();

        $old_permissions = $role->permissions()->pluck('name')->toArray();
        $role->revokePermissionTo($old_permissions);
        $role->givePermissionTo($request->permissions);

        return redirect(route('roles.index'))->with('updated', 'Role Updated Successful');
    }

    public function destroy($id)
    {
        if (!User::findOrFail(auth()->id())->can('remove_role')) {
            return abort(401);
        }

        $role = Role::findOrFail($id);
        $role->delete();

        return "success";
    }
}
