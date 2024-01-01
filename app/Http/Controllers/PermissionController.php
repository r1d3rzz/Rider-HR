<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermission;
use App\Http\Requests\UpdatePermission;
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
        if (\request()->ajax()) {
            $data = Permission::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '<a href="' . route("permissions.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';

                    $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';

                    return "<div class='btn-group'>$edit$delete</div>";
                })
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }
        return view('permission.index');
    }

    public function create()
    {
        return view('permission.create');
    }

    public function store(StorePermission $request)
    {
        $permission = new Permission;
        $permission->name = $request->name;
        $permission->save();

        return redirect(route('permissions.index'))->with('created', 'New Permission Created Successful');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permission.edit', [
            'permission' => $permission,
        ]);
    }

    public function update($id, UpdatePermission $request)
    {
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
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return "success";
    }
}
