<x-layout>
    <x-slot name="title">
        Attendance Create
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-body">
                    <x-errors-all />
                    <form action="{{route('attendances.store')}}" method="POST" id="attendances">
                        @csrf
                        <div class="mb-4">
                            <label for="employee_name" class="fs-6">Employee</label>
                            <select class="select2multi form-select" name="user_id" id="employee_name">
                                <option value="" disabled selected></option>
                                @foreach ($employees as $employee)
                                <option {{old('user_id')==$employee->id ? "selected" : ''}}
                                    value="{{$employee->id}}">
                                    {{$employee->name}} ({{$employee->employee_id}})</option>
                                @endforeach
                            </select>
                        </div>

                        <x-form.input name="date" />
                        <x-form.input name="checkin_time" class="checkInOut_times" />
                        <x-form.input name="checkout_time" class="checkInOut_times" />
                        <button class="btn btn-primary rounded-1">Create Attendance</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function(){
                $('#employee_name').select2({
                    placeholder: '-- Select Employee --',
                });

                $('#date').daterangepicker({
                    "singleDatePicker": true,
                    "showDropdowns": true,
                    "autoApply": true,
                    "maxDate": moment(),
                    "locale": {
                        "format": "YYYY/MM/DD",
                    }
                });

                $('.checkInOut_times').daterangepicker({
                    "singleDatePicker": true,
                    "timePicker": true,
                    "timePicker24Hour": true,
                    "timePickerSeconds": true,
                    "locale": {
                        "format": "HH:mm:ss"
                    }
                }).on('show.daterangepicker',function(ev,picker){
                    picker.container.find('.calendar-table').hide();
                });
            });
        </script>
        {!! JsValidator::formRequest('App\Http\Requests\StoreAttendance', '#attendances'); !!}
    </x-slot>
</x-layout>