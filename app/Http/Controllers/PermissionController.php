<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermission;
use App\Http\Requests\UpdatePermission;
use App\Models\User;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!User::findOrFail(auth()->id())->can('view_permissions')) {
            return abort(401);
        }

        if (\request()->ajax()) {
            $data = Permission::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '';
                    $delete = '';

                    if (User::findOrFail(auth()->id())->can('edit_permission')) {
                        $edit = '<a href="' . route("permissions.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('remove_permission')) {
                        $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';
                    }

                    return "<div class='btn-group'>$edit$delete</div>";
                })
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }
        return view('permission.index');
    }

    public function create()
    {
        if (!User::findOrFail(auth()->id())->can('create_permission')) {
            return abort(401);
        }

        return view('permission.create');
    }

    public function store(StorePermission $request)
    {
        if (!User::findOrFail(auth()->id())->can('create_permission')) {
            return abort(401);
        }

        $permission = new Permission;
        $permission->name = $request->name;
        $permission->save();

        return redirect(route('permissions.index'))->with('created', 'New Permission Created Successful');
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_permission')) {
            return abort(401);
        }

        $permission = Permission::findOrFail($id);
        return view('permission.edit', [
            'permission' => $permission,
        ]);
    }

    public function update($id, UpdatePermission $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_permission')) {
            return abort(401);
        }

        request()->validate([
            "name" => [Rule::unique('permissions', 'name')->ignore($id)]
        ]);

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->update();

        return redirect(route('permissions.index'))->with('updated', 'Permission Updated Successful');
    }

    public function destroy($id)
    {
        if (!User::findOrFail(auth()->id())->can('remove_permission')) {
            return abort(401);
        }

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return "success";
    }
}
