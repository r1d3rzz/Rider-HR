<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalary;
use App\Http\Requests\UpdateSalary;
use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!User::findOrFail(auth()->id())->can('view_salaries')) {
            return abort(401);
        }

        if (\request()->ajax()) {
            $data = Salary::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('employee_name', function ($row) {
                    return $row->employee ? $row->employee->name : '-';
                })
                ->editColumn('month', function ($row) {
                    return Carbon::createFromFormat('m', $row->month)->format('F');
                })
                ->editColumn('amount', function ($row) {
                    return $row->amount ? number_format($row->amount) : 0;
                })
                ->addColumn('actions', function ($row) {
                    $edit = '';
                    $delete = '';

                    if (User::findOrFail(auth()->id())->can('edit_salary')) {
                        $edit = '<a href="' . route("salaries.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('remove_salary')) {
                        $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';
                    }

                    return "<div class='btn-group'>$edit$delete</div>";
                })
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }
        return view('salary.index');
    }

    public function create()
    {
        if (!User::findOrFail(auth()->id())->can('create_salary')) {
            return abort(401);
        }

        return view('salary.create', [
            'employees' => User::orderBy('employee_id')->get(),
        ]);
    }

    public function store(StoreSalary $request)
    {
        if (!User::findOrFail(auth()->id())->can('create_salary')) {
            return abort(401);
        }

        $salary = new Salary();
        $salary->user_id = $request->user_id;
        $salary->month = $request->month;
        $salary->year = $request->year;
        $salary->amount = $request->amount;
        $salary->save();

        return redirect(route('salaries.index'))->with('created', 'New Salary Created Successful');
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_salary')) {
            return abort(401);
        }

        $salary = Salary::findOrFail($id);
        return view('salary.edit', [
            'salary' => $salary,
            'employees' => User::orderBy('employee_id')->get(),
        ]);
    }

    public function update($id, UpdateSalary $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_salary')) {
            return abort(401);
        }

        $salary = Salary::findOrFail($id);
        $salary->user_id = $request->user_id;
        $salary->month = $request->month;
        $salary->year = $request->year;
        $salary->amount = $request->amount;
        $salary->update();

        return redirect(route('salaries.index'))->with('updated', 'Salary Updated Successful');
    }

    public function destroy($id)
    {
        if (!User::findOrFail(auth()->id())->can('remove_salary')) {
            return abort(401);
        }

        $salary = Salary::findOrFail($id);
        $salary->delete();

        return "success";
    }
}
