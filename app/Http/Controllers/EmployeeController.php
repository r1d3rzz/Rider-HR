<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployee;
use App\Http\Requests\UpdateEmployee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (\request()->ajax()) {
            $data = User::with('department')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '<a href="' . route("employees.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';

                    $detail = '<a href="' . route("employees.show", $row->id) . '" class="btn btn-sm btn-outline-secondary">' . '<i class="fa-solid fa-circle-info"></i>' . '</a>';

                    $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';

                    return "<div class='btn-group'>$edit$detail$delete</div>";
                })
                ->addColumn('department', function ($row) {
                    return $row->department ? $row->department->name : '-';
                })
                ->editColumn('avatar', function ($row) {
                    return $row->avatar ? "<img style='width: 80px;' src='/storage/$row->avatar'/>" : '-';
                })
                ->editColumn('is_present', function ($row) {
                    return $row->is_present ? "<span class='badge bg-primary'>Yes</span>" : "<span class='badge bg-danger'>Leave</span>";
                })
                ->rawColumns(['action', 'actions', 'avatar', 'is_present'])
                ->make(true);
        }
        return view('employee.index');
    }

    public function show($id)
    {
        $employee = User::findOrFail($id);

        return view("employee.show", ["employee" => $employee]);
    }

    public function create()
    {
        return view('employee.create', [
            'departments' => Department::all(),
        ]);
    }

    public function store(StoreEmployee $request)
    {
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
        if ($request->file('avatar')) {
            $employee->avatar = $request->file('avatar')->store('Employee');
        }
        $employee->save();

        return redirect(route('employees.index'))->with('created', 'New Employee Created Successful');
    }

    public function edit($id)
    {
        $employee = User::findOrFail($id);
        return view('employee.edit', [
            'employee' => $employee,
            'departments' => Department::all(),
        ]);
    }

    public function update($id, UpdateEmployee $request)
    {
        request()->validate([
            "employee_id" => Rule::unique('users', 'employee_id')->ignore($id),
            "email" => Rule::unique('users', 'email')->ignore($id),
            "nrc_number" => Rule::unique('users', 'nrc_number')->ignore($id),
            "phone" => Rule::unique('users', 'phone')->ignore($id),
        ]);

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

        if (isset($request->deleteAvatar) && $request->deleteAvatar == 'on') {
            $employee->avatar = null;
        } else {
            $request->file('avatar') ? $employee->avatar = $request->file('avatar')->store('Employee') : $employee->avatar;
        }

        $employee->update();

        if (auth()->id() == $id && $request->password) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        }

        return redirect(route('employees.index'))->with('updated', 'Employee Updated Successful');
    }

    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();

        return "success";
    }
}
