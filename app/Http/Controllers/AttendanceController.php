<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\CarbonPeriod;
use App\Models\CheckinCheckout;
use App\Http\Requests\StoreAttendance;
use App\Http\Requests\UpdateAttendance;
use App\Models\CompanySetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!User::findOrFail(auth()->id())->can('view_attendances')) {
            return abort(401);
        }

        if (\request()->ajax()) {
            $data = CheckinCheckout::with('employee')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('employee_name', function ($row) {
                    return $row->employee ? $row->employee->name : '-';
                })
                ->addColumn('actions', function ($row) {
                    $edit = '';
                    $delete = '';

                    if (User::findOrFail(auth()->id())->can('edit_attendance')) {
                        $edit = '<a href="' . route("attendances.edit", $row->id) . '" class="btn btn-sm btn-warning">' . '<i class="fa-solid fa-edit"></i>' . '</a>';
                    }

                    if (User::findOrFail(auth()->id())->can('remove_attendance')) {
                        $delete = '<a href="#" id="del-btn" data-id="' . $row->id . '" class="btn btn-sm btn-danger">' . '<i class="fa-solid fa-trash-alt"></i>' . '</a>';
                    }

                    return "<div class='btn-group'>$edit$delete</div>";
                })
                ->rawColumns(['action', 'actions'])
                ->make(true);
        }
        return view('attendance.index');
    }

    public function create()
    {
        if (!User::findOrFail(auth()->id())->can('create_attendance')) {
            return abort(401);
        }

        return view('attendance.create', [
            "employees" => User::orderBy('employee_id', 'desc')->get(),
        ]);
    }

    public function store(StoreAttendance $request)
    {
        if (!User::findOrFail(auth()->id())->can('create_attendance')) {
            return abort(401);
        }

        if (CheckinCheckout::where('user_id', $request->user_id)->where('date', $request->date)->exists()) {
            return back()->withErrors(['fail' => 'Already defined.'])->withInput();
        }

        $attendance = new CheckinCheckout();
        $attendance->user_id = $request->user_id;
        $attendance->date = $request->date;
        $attendance->checkin_time = $request->date . ' ' . $request->checkin_time;
        $attendance->checkout_time = $request->date . ' ' . $request->checkout_time;
        $attendance->save();

        return redirect(route('attendances.index'))->with('created', 'New Attendance Created Successful');
    }

    public function edit($id)
    {
        if (!User::findOrFail(auth()->id())->can('edit_attendance')) {
            return abort(401);
        }

        $attendance = CheckinCheckout::findOrFail($id);
        return view('attendance.edit', [
            "employees" => User::orderBy('employee_id', 'desc')->get(),
            "attendance" => $attendance,
        ]);
    }

    public function update($id, UpdateAttendance $request)
    {
        if (!User::findOrFail(auth()->id())->can('edit_attendance')) {
            return abort(401);
        }

        $attendance = CheckinCheckout::findOrFail($id);

        if (CheckinCheckout::where('user_id', $request->user_id)->where('date', $request->date)->where('id', '!=', $attendance->id)->exists()) {
            return back()->withErrors(['fail' => 'Already defined.'])->withInput();
        }

        $attendance->user_id = $request->user_id;
        $attendance->date = $request->date;
        $attendance->checkin_time = $request->date . ' ' . $request->checkin_time;
        $attendance->checkout_time = $request->date . ' ' . $request->checkout_time;
        $attendance->update();

        return redirect(route('attendances.index'))->with('updated', 'Attendance Updated Successful');
    }

    public function destroy($id)
    {
        if (!User::findOrFail(auth()->id())->can('remove_attendance')) {
            return abort(401);
        }

        $attendance = CheckinCheckout::findOrFail($id);
        $attendance->delete();

        return "success";
    }

    public function overview()
    {
        if (!User::findOrFail(auth()->id())->can('view_attendances')) {
            return abort(401);
        }

        return view('attendance.overview');
    }

    public function overview_table(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $employee_name = $request->employee_name;

        $startOfMonth = $year . '-' . $month . '-01';
        $endOfMonth = Carbon::parse($startOfMonth)->endOfMonth()->format('Y-m-d');

        return view('components.attendance.overview-table', [
            'periods' => CarbonPeriod::create($startOfMonth, $endOfMonth),
            'employees' => User::orderBy('employee_id', 'desc')->where('name', 'like', '%' . $employee_name . '%')->get(),
            'company_setting' => CompanySetting::find(1),
            'attendances' => CheckinCheckout::whereMonth('date', $month)->whereYear('date', $year)->get(),
        ])->render();
    }

    public function attendances_pdf_download()
    {
        $attendances = CheckinCheckout::with('employee')->get();
        $pdf = PDF::loadView('pdf.attendances_docs', [
            'attendances' => $attendances,
        ]);
        return $pdf->stream('attendances.pdf');
    }
}
