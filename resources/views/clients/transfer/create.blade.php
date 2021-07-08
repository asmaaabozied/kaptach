@extends('layouts.master')
@section('title',__('pages.create'). __('pages.transfer'))
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
          href="{{asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            {!! BootForm::open('create', ['url'=>route('clients.transfers.store'),'id'=>'basic-form','class'=>'form-horizontal']) !!}

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('pages.create'). __('pages.transfer')}}</h3>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif
                <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-xs-5">
                            <div class="form-group">
                                <label class="col-xs-2 control-label">{{__('inputs.date')}}:</label>
                                <div class="input-group date form_datetime col-xs-8">
                                    <input class="form-control" size="16" type="text" value="" readonly required
                                           name="datetimepicker" id="datetimepicker">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                            @if($type=='arrival')
                                <div class="form-group">
                                    <label class="col-md-3 control-label">{{__('inputs.flight_no')}}:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="flight_number"
                                               id="flight_number" required/>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-md-2 control-label">{{__('inputs.car_models')}}:</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="car_model_id" id="car_model_id" required
                                            onchange="eventOnChange()">
                                        <option value="0">{{__('inputs.select_model')}}</option>
                                        @foreach ($car_models as $car_model)
                                            <option value="{{$car_model->id}}">{{$car_model->ModelWithSeats}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{{__('inputs.number_of_seats')}}:</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" value="1" min="1" name="number_of_booking"
                                           id="number_of_booking" required/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="callout callout-info">
                                <h7>{{$airport->name}}</h7>
                                <input name="airport_id" type="hidden" value="{{$airport->id}}">
                            </div>
                            <div class="callout callout-info">
                                <h7>{{$type}}</h7>
                                <input name="type" type="hidden" value="{{$type}}">
                            </div>
                            <div class="callout callout-info">
                                <h7 id="price">0</h7>
                                <input name="price" type="hidden" id="price_input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable"
                                   id="customers_table">
                                <thead>
                                <tr>
                                    <th>{{__('pages.select')}}</th>
                                    <th>{{__('pages.passport_number')}}*</th>
                                    <th>{{__('pages.first_name')}}</th>
                                    <th>{{__('pages.last_name')}}</th>
                                    <th>{{__('pages.gender')}}</th>
                                    <th>{{__('pages.nationality')}}</th>
                                    <th>{{__('pages.phone')}}</th>
                                    <th>{{__('pages.room_number')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><input type="checkbox" name="record" class="filled-in"></td>
                                    <td><input type="text" class=" form-control" min="1" name="identity_number[]"
                                               required>
                                    </td>
                                    <td><input type="text" class="form-control" name="first_name[]" id="first_name"
                                               required>
                                    </td>
                                    <td><input type="text" class="form-control" name="last_name[]" id="last_name"
                                               required>
                                    </td>
                                    <td><select name="gender[]" required class="form-control">
                                            <option value="female">{{__('inputs.female')}}</option>
                                            <option value="male">{{__('inputs.male')}}</option>
                                        </select></td>
                                    <td>
                                        <select name="nationality[]" required class="form-control">
                                            <option value="">{{__('pages.select'). __('pages.nationality')}}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->nationality}}">{{$country->nationality}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td><input type="text" class="form-control" name="phone[]" id="phone" required></td>
                                    <td><input type="text" class="form-control" min="1" name="room_number[]"
                                               id="room_number"
                                        @if($type =='departure') {{'required'}} @endif>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <button type="button" class="delete btn btn-sm btn-danger delete-row">{{__('buttons.delete_row')}}</button>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{__('inputs.notes')}}:</label>
                            <div class="col-md-6">
                       <textarea rows="4" class="form-control no-resize"
                                 placeholder="{{__('inputs.placeholder_notes')}}" name="notes"></textarea>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="box-footer">
                    {!! BootForm::submit() !!}
                </div>
            </div>


        {!! BootForm::close() !!}
        <!-- /.box-body -->
        </div>
        <!-- /.box -->
        </div>
        <!-- /.col -->
        </div>
        <!-- /.row -->
        <!-- time Picker -->
    </section>
@endsection
@section('scripts')
    <!-- bootstrap datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')}}"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script>

        $(document).ready(function () {
            //Date picker
            $(".form_datetime").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                startDate: new Date(),
                autoclose:true,
            });
        });
        //These codes takes from http://t4t5.github.io/sweetalert/
        function showBasicMessage() {
            swal("There are not enough seats,please change Car model Type!");
        }
        $('#number_of_booking').change(function (event) {
            if ($('#car_model_id').val() == '0') {
                swal("please select Car model Type!");
                $('#number_of_booking').val(1);
            } else {
                var count = $('#number_of_booking').val();
                var car_model = $('#car_model_id').val();
                var response;
                $.ajax({
                    type: 'Get',
                    async: false,
                    url: '{!! route('carmodels.getCarModelByID') !!}',
                    data: {id: car_model},
                    success: function (data) {
                        response = data;
                    },
                });
                if (response < count) {
                    showBasicMessage();
                    count = response;
                    $('#number_of_booking').val(response);
                }
                $("#customers_table tbody").empty();
                for (var n = 0; n < count; n++) {
                    var markup = "<tr><td><input type=\"checkbox\" name=\"record\" class=\"filled-in\"></td>" +
                        "<td><input type=\"text\" class=\" form-control\" min=\"1\" name=\"identity_number[]\"   required></td>" +
                        "<td><input type=\"text\" class=\"form-control\" name=\"first_name[]\"  required></td>" +
                        "<td><input type=\"text\" class=\"form-control\" name=\"last_name[]\"  required></td>" +
                        "<td><select name=\"gender[]\"  required class=\"form-control\">" +
                        "<option value=\"female\">Female</option>" +
                        "<option value=\"male\">Male</option>" +
                        "</select></td>" +
                        "<td><select  name=\"nationality[]\"  required class=\"form-control\">" +
                        "<option value=\"\">Select Nationality</option>" +
                        "@foreach ($countries as $country)" +
                        "<option value=\"{{$country->nationality}}\">{{$country->nationality}}</option>" +
                        "@endforeach" +
                        "</select></td>" +
                        "<td><input type=\"text\" class=\"form-control\" name=\"phone[]\"  required></td>" +
                        "<td><input type=\"text\" class=\"form-control\" min=\"1\" name=\"room_number[]\" @if($type =='departure') {{'required'}} @endif></td>" + "</tr>";
                    $("#customers_table tbody").append(markup);
                }
            }
        });
        // Find and remove selected table rows
        $(".delete-row").click(function () {
            $("table tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    if ($("#customers_table tr").length > 2) {
                        $(this).parents("tr").remove();
                        var no_passenger = $('#number_of_booking').val();
                        $('#number_of_booking').val(no_passenger - 1);
                    }

                }
            });
        })

        //get transfer price by hotel ,car model and type
        //transfer_price on hotel or car model change

        function eventOnChange() {
            getTransferPrice();
        }


        function getTransferPrice() {
            var car_model_id = $('#car_model_id').val();
            var airport_id ={{$airport->id}};
            var type = '{{$type}}';
            $.ajax({
                type: 'POST',
                headers: {
                    "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
                },
                url: '{!! route('clients.transfer_price') !!}',
                data: {car_model_id: car_model_id, airport_id: airport_id, type: type},
                success: function (data) {
                    if (data.price != undefined) {
                        $('#price').html(data.price);
                        $('#price_input').html(data.price);
                        $('input[name=price]').val(data.price);
                    }
                },
                error: function (data) {
                },
            });

        }


    </script>

@endsection