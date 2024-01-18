<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\CompanySetting;
use App\Models\CheckinCheckout;
use Yajra\DataTables\Facades\DataTables;

class MyAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (\request()->ajax()) {
            $data = CheckinCheckout::with('employee')->where('user_id', auth()->id());

            if ($request->month) {
                $data = $data->whereMonth('date', $request->month);
            }

            if ($request->month) {
                $data = $data->whereYear('date', $request->year);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->addColumn('employee_name', function ($row) {
                    return $row->employee ? $row->employee->name : '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('attendance_scan');
    }

    public function my_overview_table(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $startOfMonth = $year . '-' . $month . '-01';
        $endOfMonth = Carbon::parse($startOfMonth)->endOfMonth()->format('Y-m-d');

        return view('components.attendance.overview-table', [
            'periods' => CarbonPeriod::create($startOfMonth, $endOfMonth),
            'employees' => User::where('id', auth()->id())->get(),
            'company_setting' => CompanySetting::find(1),
            'attendances' => CheckinCheckout::whereMonth('date', $month)->whereYear('date', $year)->get(),
        ])->render();
    }
}
