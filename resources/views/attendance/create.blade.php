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
                            <label class="fs-6">Employee Name</label>
                            <select class="form-select single-select-field" name="user_id" data-placeholder="Choose Employee">
                                <option></option>
                                @foreach ($employees as $employee)
                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
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
                $( '.single-select-field' ).select2( {
                    theme: "bootstrap-5",
                    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                    placeholder: $( this ).data( 'placeholder' ),
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
