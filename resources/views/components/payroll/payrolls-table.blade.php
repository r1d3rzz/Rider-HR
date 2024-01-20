<div class="table-responsive">
    <table class="table table-striped border table-bordered">
        <thead>
            <tr class="text-center">
                <th class="p-2 align-middle">Employee</th>
                <th class="p-2 align-middle">Role</th>
                <th class="p-2 align-middle">Days in month</th>
                <th class="p-2 align-middle">Working Days</th>
                <th class="p-2 align-middle">Off Days</th>
                <th class="p-2 align-middle">Attendances</th>
                <th class="p-2 align-middle">Leave Days</th>
                <th class="p-2 align-middle">Per Day (MMK)</th>
                <th class="p-2 align-middle">Total (MMK)</th>
            </tr>
        </thead>
        <tbody class="align-middle">
            @foreach ($employees as $employee)
            @php

            $attendanceCount = 0;
            $salary = collect($employee->salaries)->where('month',$month)->where('year',$year)->first();
            $perday = $salary ? $salary->amount / $workingDays : 0;

            @endphp
            <tr>
                <th class="text-center p-2">{{$employee->name}}</th>
                <td class="text-center p-2">
                    @forelse ($employee->roles as $role)
                        <div>{{$role->name}}</div>
                    @empty
                        <div> - </div>
                    @endforelse
                </td>
                <td class="text-center">{{$daysInMonth}}</td>
                <td class="text-center">{{$workingDays}}</td>
                <td class="text-center">{{$daysInMonth - $workingDays}}</td>
                @foreach ($periods as $period)
                @php
                    $periodFormat = $period->format('Y-m-d');
                    $office_start_time = $periodFormat.' '.$company_setting->office_start_time;
                    $office_end_time = $periodFormat.' '.$company_setting->office_end_time;
                    $break_start_time = $periodFormat.' '.$company_setting->break_start_time;
                    $break_end_time = $periodFormat.' '.$company_setting->break_end_time;

                    $attendance = collect($attendances)->where('user_id',$employee->id)->where('date',$periodFormat)->first();

                    if($attendance  ){
                        if($attendance->checkin_time <= $office_start_time){
                            $attendanceCount += 0.5;
                        }else if($attendance->checkin_time > $office_start_time && $attendance->checkin_time < $break_start_time){
                            $attendanceCount += 0.5;
                        }else{
                            $attendanceCount += 0;
                        }

                        if($attendance->checkout_time >= $office_end_time){
                            $attendanceCount += 0.5;
                        }else if($attendance->checkout_time < $office_end_time && $attendance->checkout_time > $break_end_time){
                            $attendanceCount += 0.5;
                        }else{
                            $attendanceCount += 0;
                        }
                    }
                @endphp
                @endforeach
                @php
                    $total = $perday * $attendanceCount;
                @endphp
                <td class="text-center">{{$attendanceCount}}</td>
                <td class="text-center">{{$workingDays - $attendanceCount}}</td>
                <td class="text-center">{{number_format($perday)}}</td>
                <td class="text-center">{{number_format($total)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
