<x-layout>
    <x-slot name="title">
        Payrolls
    </x-slot>

    <div class="container p-2">
        <div class="row justify-content-center">
            <div class="col-12">
                <form>
                    <div class="card card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <input type="text" id="employee_name" class="form-control" placeholder="Search Employee Name">
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="month" class="form-select text-center">
                                    <option disabled selected>-- Select Month --</option>
                                    <option value="01" @if(now()->format('m') == '01') selected @endif>Jan</option>
                                    <option value="02" @if(now()->format('m') == '02') selected @endif>Feb</option>
                                    <option value="03" @if(now()->format('m') == '03') selected @endif>Mar</option>
                                    <option value="04" @if(now()->format('m') == '04') selected @endif>Apr</option>
                                    <option value="05" @if(now()->format('m') == '05') selected @endif>May</option>
                                    <option value="06" @if(now()->format('m') == '06') selected @endif>Jun</option>
                                    <option value="07" @if(now()->format('m') == '07') selected @endif>Jul</option>
                                    <option value="08" @if(now()->format('m') == '08') selected @endif>Aug</option>
                                    <option value="09" @if(now()->format('m') == '09') selected @endif>Sep</option>
                                    <option value="10" @if(now()->format('m') == '10') selected @endif>Oct</option>
                                    <option value="11" @if(now()->format('m') == '11') selected @endif>Nov</option>
                                    <option value="12" @if(now()->format('m') == '12') selected @endif>Dec</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="year" class="form-select text-center">
                                    <option disabled selected>-- Select Year --</option>
                                    @foreach (range(0,4) as $item)
                                        <option @if(now()->format('Y') == now()->format('Y') - $item) selected @endif value="{{now()->format('Y') - $item}}">
                                            {{now()->format('Y') - $item}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <button id="filterBtn" class="btn btn-primary rounded-1 w-100">Search</button>
                            </div>
                        </div>
                        <div id="payrolls-table"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script type="text/javascript">
           $(document).ready(function(){
                function getPayrolls(){
                    var employee_name = $('#employee_name').val();
                    var month = $('#month').val();
                    var year = $('#year').val();

                    $.ajax({
                        url: `/payrolls-table?employee_name=${employee_name}&month=${month}&year=${year}`,
                        type: "GET",
                        success: function(res){
                            $('#payrolls-table').html(res);
                        }
                    });

                }

                $('#filterBtn').on('click',function(e){
                    e.preventDefault();
                    getPayrolls();
                });

                getPayrolls();
           });
        </script>
    </x-slot>
</x-layout>

