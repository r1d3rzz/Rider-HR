<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\CompanySetting;
use App\Models\CheckinCheckout;

class MyPayrollController extends Controller
{
    public function my_payroll_table(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $countWeekdays = 0;
        while ($startDate <= $endDate) {
            if ($startDate->isWeekday()) {
                $countWeekdays++;
            }

            $startDate->addDay();
        }

        $startOfMonth = $year . '-' . $month . '-01';
        $endOfMonth = Carbon::parse($startOfMonth)->endOfMonth()->format('Y-m-d');

        return view('components.payroll.payrolls-table', [
            'periods' => CarbonPeriod::create($startOfMonth, $endOfMonth),
            'employees' => User::where('id', auth()->id())->get(),
            'company_setting' => CompanySetting::find(1),
            'attendances' => CheckinCheckout::whereMonth('date', $month)->whereYear('date', $year)->get(),
            'daysInMonth' => Carbon::create($year, $month, 1)->daysInMonth,
            'workingDays' => $countWeekdays,
            'month' => $month,
            'year' => $year,
        ])->render();
    }
}
