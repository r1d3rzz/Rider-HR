<x-layout>
    <x-slot name="title">
        Company Setting | Edit
    </x-slot>

    <x-slot name="style">
        <style>
            .calendar-table {
                display: none;
            }
        </style>
    </x-slot>

    <div class="container p-2">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <form action="{{route('company_settings.update',1)}}" method="POST" id="company_settings">
                    @csrf
                    @method('PUT')
                    <div class="card card-body">
                        <div class="row">
                            <div class="col-lg-6 my-3">
                                <label class="h5 fw-bold text-muted" for="name">Company Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{old('name',$company->name)}}" required>
                            </div>

                            <div class="col-lg-6 my-3">
                                <label class="h5 fw-bold text-muted" for="email">Company Mail</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{old('email',$company->email)}}" required>
                            </div>

                            <div class="col-lg-6 my-3">
                                <label class="h5 fw-bold text-muted" for="phone">Company Phone</label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                    value="{{old('phone',$company->phone)}}" required>
                            </div>

                            <div class="col-lg-6 my-3">
                                <label class="h5 fw-bold text-muted" for="address">Company Address</label>
                                <textarea id="address" name="address" class="form-control"
                                    required>{{old('address',$company->address)}}</textarea>
                            </div>

                            <div class="col-lg-6 my-3">
                                <label class="h5 fw-bold text-muted" for="office_start_time">Office Start Time</label>
                                <input type="text" id="office_start_time" name="office_start_time"
                                    class="form-control company_times"
                                    value="{{old('office_start_time',$company->office_start_time)}}" required>
                            </div>

                            <div class="col-lg-6 my-3">
                                <label class="h5 fw-bold text-muted" for="office_end_time">Office End Time</label>
                                <input type="text" id="office_end_time" name="office_end_time"
                                    class="form-control company_times"
                                    value="{{old('office_end_time',$company->office_end_time)}}" required>
                            </div>

                            <div class="col-lg-6 my-3">
                                <label class="h5 fw-bold text-muted" for="break_start_time">Break Start Time</label>
                                <input type="text" id="break_start_time" name="break_start_time"
                                    class="form-control company_times"
                                    value="{{old('break_start_time',$company->break_start_time)}}" required>
                            </div>

                            <div class="col-lg-6 my-3">
                                <label class="h5 fw-bold text-muted" for="break_end_time">Break End Time</label>
                                <input type="text" id="break_end_time" name="break_end_time"
                                    class="form-control company_times"
                                    value="{{old('break_end_time',$company->break_end_time)}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-warning bg-gradient w-100">Update Company Setting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function(){
                $('.company_times').daterangepicker({
                    "singleDatePicker": true,
                    "timePicker": true,
                    "timePicker24Hour": true,
                    "timePickerSeconds": true,
                    "locale": {
                        "format": "HH:mm:ss"
                    }
                })
            });
        </script>
        {!! JsValidator::formRequest('App\Http\Requests\UpdateCompanySetting', '#company_settings'); !!}
    </x-slot>
</x-layout>
