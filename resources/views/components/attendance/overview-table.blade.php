<div class="table-responsive">
    <table class="table table-striped border table-bordered">
        <thead>
            <tr class="text-center">
                <th class="p-2 align-middle">Employee</th>
                @foreach ($periods as $period)
                    <th class="@if($period->format('D') == 'Sat' || $period->format('D') == 'Sun') text-bg-primary @endif">
                        {{$period->format('d')}}
                        {{$period->format('D')}}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="align-middle">
            @foreach ($employees as $employee)
            <tr>
                <th class="text-center p-2">{{$employee->name}}</th>
                @foreach ($periods as $period)
                @php
                    $periodFormat = $period->format('Y-m-d');
                    $office_start_time = $periodFormat.' '.$company_setting->office_start_time;
                    $office_end_time = $periodFormat.' '.$company_setting->office_end_time;
                    $break_start_time = $periodFormat.' '.$company_setting->break_start_time;
                    $break_end_time = $periodFormat.' '.$company_setting->break_end_time;

                    $attendance = collect($attendances)->where('user_id',$employee->id)->where('date',$periodFormat)->first();

                    $checkin_icon = '';
                    $checkout_icon = '';


                    if($attendance  ){
                        if($attendance->checkin_time <= $office_start_time){
                            $checkin_icon = '<i class="fa-solid fa-circle-check text-success fs-5 mb-2"></i>';
                        }else if($attendance->checkin_time > $office_start_time && $attendance->checkin_time < $break_start_time){
                            $checkin_icon = '<i class="fa-solid fa-circle-check text-warning fs-5 mb-2"></i>';
                        }else{
                            $checkin_icon = '<i class="fa-regular fa-circle-xmark text-danger fs-5 mb-2"></i>';
                        }

                        if($attendance->checkout_time >= $office_end_time){
                            $checkout_icon = '<i class="fa-solid fa-circle-check text-success fs-5"></i>';
                        }else if($attendance->checkout_time < $office_end_time && $attendance->checkout_time > $break_end_time){
                            $checkout_icon = '<i class="fa-solid fa-circle-check text-warning fs-5"></i>';
                        }else{
                            $checkout_icon = '<i class="fa-regular fa-circle-xmark text-danger fs-5"></i>';
                        }
                    }
                @endphp
                <td @if($period->format('D') == 'Sat' || $period->format('D') == 'Sun') class="bg-info" @endif>
                    {!! $checkin_icon !!}
                    <br>
                    {!! $checkout_icon !!}
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
