<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployee;
use App\Http\Requests\UpdateEmployee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!User::findOrFail(auth()->id())->can('view_employees')) {
            return abort(401);
        }

        if (\request()->ajax()) {
            $data = User::with('department')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '';
                    $detail = '';
                    $delete = '';

                    if (User::findOrFail(auth()->id())->can('edit_employee')) {
                        $edit = '<a href="' . route("employees.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('view_employees')) {
                        $detail = '<a href="' . route("employees.show", $row->id) . '" class="btn btn-sm btn-outline-secondary">' . '<i class="fa-solid fa-circle-info"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('remove_employee')) {
                        $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';
                    }

                    return "<div class='btn-group'>$edit$detail$delete</div>";
                })
                ->addColumn('department', function ($row) {
                    return $row->department ? $row->department->name : '-';
                })
                ->addColumn('roles', function ($row) {
                    $lists = '';
                    foreach ($row->roles as $role) {
                        $lists .= "<span class='badge bg-primary m-1'>" . $role->name . "</span>";
                    }
                    $listsContainer = "<div class='d-flex justify-content-center flex-wrap'>$lists</div>";
                    return $listsContainer;
                })
                ->editColumn('avatar', function ($row) {
                    return $row->avatar ? "<img style='width: 80px;' src='/storage/$row->avatar'/>" : '-';
                })
                ->editColumn('is_present', function ($row) {
                    return $row->is_present ? "<span class='badge bg-primary'>Yes</span>" : "<span class='badge bg-danger'>Leave</span>";
                })
                ->rawColumns(['action', 'actions', 'avatar', 'is_present', 'roles'])
                ->make(true);
        }
        return view('employee.index');
    }

    public function show($id)
    {
        if (!User::findOrFail(auth()->id())->can('view_employees')) {
            return abort(401);
        }

        $employee = User::findOrFail($id);

        return view("employee.show", ["employee" => $employee]);
    }

    public function create()
    {
        if (!User::findOrFail(auth()->id())->can('create_employee')) {
            return abort(401);
        }

        return view('employee.create', [
            'departments' => Department::all(),
            'roles' => Role::all(),
        ]);
    }

    public function store(StoreEmployee $request)
    {
        if (!User::findOrFail(auth()->id())->can('create_employee')) {
            return abort(401);
        }

        $employee = new User;
        $employee->employee_id = $request->employee_id;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->nrc_number = $request->nrc_number;
        $employee->gender = $request->gender;
        $employee->birthday = $request->birthday;
        $employee->date_of_join = $request->date_of_join;
        $employee->address = $request->address;
        $employee->department_id = $request->department_id;
        $employee->is_present = $request->is_present;
        $employee->password = $request->password;
        $employee->pin_code = $request->pin_code;
        if ($request->file('avatar')) {
            $employee->avatar = $request->file('avatar')->store('Employee');
        }
        $employee->save();

        $employee->syncRoles($request->roles);

        return redirect(route('employees.index'))->with('created', 'New Employee Created Successful');
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_employee')) {
            return abort(401);
        }

        $employee = User::findOrFail($id);

        return view('employee.edit', [
            'employee' => $employee,
            'departments' => Department::all(),
            'roles' => Role::all(),
            'old_roles' => $employee->roles()->pluck('id')->toArray(),
        ]);
    }

    public function update($id, UpdateEmployee $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_employee')) {
            return abort(401);
        }

        $employee = User::findOrFail($id);
        $employee->employee_id = $request->employee_id;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->nrc_number = $request->nrc_number;
        $employee->gender = $request->gender;
        $employee->birthday = $request->birthday;
        $employee->date_of_join = $request->date_of_join;
        $employee->address = $request->address;
        $employee->department_id = $request->department_id;
        $employee->is_present = $request->is_present;

        if ($request->password) {
            if (strlen($request->password) >= 8) {
                $employee->password = $request->password;
            } else {
                return back()->withErrors(["password" => "The password field must be at least 8 characters."]);
            }
        } else {
            $employee->password;
        }

        if ($request->pin_code) {
            if (strlen($request->pin_code) >= 6) {
                $employee->pin_code = $request->pin_code;
            } else {
                return back()->withErrors(["pin_code" => "The pic code field must be at least 6 characters."]);
            }
        } else {
            $employee->pin_code;
        }

        if (isset($request->deleteAvatar) && $request->deleteAvatar == 'on') {
            $employee->avatar = null;
        } else {
            $request->file('avatar') ? $employee->avatar = $request->file('avatar')->store('Employee') : $employee->avatar;
        }

        $employee->update();

        $employee->syncRoles($request->roles);

        if (auth()->id() == $id && $request->password) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        }

        if (auth()->id() == $id && $request->pin_code) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        }

        return redirect(route('employees.index'))->with('updated', 'Employee Updated Successful');
    }

    public function destroy($id)
    {
        if (!User::findOrFail(auth()->id())->can('remove_employee')) {
            return abort(401);
        }

        $employee = User::findOrFail($id);
        $employee->delete();

        return "success";
    }
}
