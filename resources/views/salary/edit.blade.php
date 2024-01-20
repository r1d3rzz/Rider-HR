<x-layout>
    <x-slot name="title">
        Salary Edit
    </x-slot>

    <div class="container p-2 mb-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-body">
                    <form action="{{route('salaries.update',$salary->id)}}" method="POST" id="salaries">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="mb-sm-1">

                                <div class="mb-4">
                                    <label class="fs-6">Employee Name</label>
                                    <select class="form-select single-select-field" name="user_id" data-placeholder="Choose Employee">
                                        <option></option>
                                        @foreach ($employees as $employee)
                                            <option @selected($employee->id == $salary->user_id) value="{{$employee->id}}">{{$employee->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="fs-6">Month</label>
                                    <select class="form-select single-select-field" name="month" data-placeholder="Choose Month">
                                        <option></option>
                                        <option value="01" @selected($salary->month == '01')>Jan</option>
                                        <option value="02" @selected($salary->month == '02')>Feb</option>
                                        <option value="03" @selected($salary->month == '03')>Mar</option>
                                        <option value="04" @selected($salary->month == '04')>Apr</option>
                                        <option value="05" @selected($salary->month == '05')>May</option>
                                        <option value="06" @selected($salary->month == '06')>Jun</option>
                                        <option value="07" @selected($salary->month == '07')>Jul</option>
                                        <option value="08" @selected($salary->month == '08')>Aug</option>
                                        <option value="09" @selected($salary->month == '09')>Sep</option>
                                        <option value="10" @selected($salary->month == '10')>Oct</option>
                                        <option value="11" @selected($salary->month == '11')>Nov</option>
                                        <option value="12" @selected($salary->month == '12')>Dec</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="fs-6">Year</label>
                                    <select class="form-select single-select-field" name="year" data-placeholder="Choose Year">
                                        <option></option>
                                        @foreach (range(0,5) as $year)
                                            <option @selected(now()->addYear(6)->subYear(3)->format('Y') - $year == $salary->year) value="{{now()->addYear(6)->subYear(3)->format('Y') - $year}}">{{now()->addYear(6)->subYear(3)->format('Y') - $year}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <x-form.input name="amount" type="number" value="{{$salary->amount}}"/>
                            </div>
                        </div>
                        <button class="btn btn-primary rounded-1">Update Salary</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="script">

        <script>
            $( '.single-select-field' ).select2( {
                theme: "bootstrap-5",
                width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
                placeholder: $( this ).data( 'placeholder' ),
            });
        </script>
        {!! JsValidator::formRequest('App\Http\Requests\UpdateSalary', '#salaries'); !!}
    </x-slot>
</x-layout>
