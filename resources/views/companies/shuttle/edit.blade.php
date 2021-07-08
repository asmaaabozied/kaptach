@extends('layouts.master')
@section('title',__('pages.reservation'))
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
          href="{{asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            {!! BootForm::model($shuttle, 'edit', ['method'=>'put', 'route'=>['corporate.shuttles.update',$shuttle->id],'id'=>'basic-form','class'=>'form-horizontal']) !!}

            <div class="col-xs-9">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('pages.reservation')}}</h3>
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
                                <label class="col-xs-2 control-label">Date:</label>
                                <div class="input-group date form_datetime col-xs-8">
                                    <input class="form-control" size="16" type="text"
                                           value="{{$shuttle->shuttle_start_time}}" readonly required
                                           name="datetimepicker" id="datetimepicker">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">{{__('inputs.car_models')}}:</label>
                                <div class="col-md-10">
                                    <select class="form-control" name="car_model_id" id="car_model_id" required>
                                        <option value="0">{{__('inputs.select_model')}}</option>
                                        @foreach ($car_models as $car_model)
                                            <option value="{{$car_model->id}}" @if($shuttle->car_model->id == $car_model->id){{'selected'}}@endif>{{$car_model->ModelWithSeats}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{{__('inputs.number_of_seats')}}:</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" value="{{$shuttle->number_seats}}" min="1"
                                           name="number_seats"
                                           id="number_of_booking" required/>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="callout callout-info">
                                <h7 id="lbl_station">{{$shuttle->station->name}}</h7>
                            </div>
                            <div class="callout callout-info">
                                <h7>{{$shuttle->airport->name}}</h7>
                                <input name="airport_id" type="hidden" value="{{$shuttle->airport->id}}">
                            </div>
                            <div class="callout callout-info">
                                <h7>{{$shuttle->type}}</h7>
                                <input name="type" type="hidden" value="{{$shuttle->type}}">
                            </div>
                        </div>

                    </div>
                    <div class="box-footer">
                        {!! BootForm::submit() !!}
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('inputs.shifts')}}</h3>
                    </div>
                    <a class="search_shift btn btn-default">{{__('pages.search_for')}} {{__('inputs.shift')}} <i
                                class="fa fa-search"></i></a>
                    <div class="box-body" id="shifts">
                        <div class="radio">
                            @if($shuttle->shift)
                                <label>
                                    <input type="radio" name="shift" checked value="{{$shuttle->shift->id}}">
                                    {{$shuttle->shift->employer->first_name}} {{$shuttle->shift->employer->last_name}}
                                    <p>{{ date('H:i', strtotime($shuttle->shift->shift_start_time))}}
                                        - {{date('H:i', strtotime($shuttle->shift->shift_end_time))}}</p>
                                </label>
                            @endif
                        </div>
                    </div>
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
    <script>

        $(document).ready(function () {
            //Date picker
            $(".form_datetime").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                startDate: new Date(),
            });
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

        $('.form_datetime,#car_model_id').on('change', function () {
            $("#shifts").empty();
        });
        //get transfer price by hotel ,car model and type
        //transfer_price on hotel or car model change

        $('.search_shift').click(function () {
            getDriverShift()
        });


        //find driver shift by datetime and  car model
        function getDriverShift() {
            var car_model_id = $('#car_model_id').val();
            var date = $('#datetimepicker').val();

            $.ajax({
                type: 'Post',
                headers: {
                    "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
                },
                url: '{!! route('drivers.get_driver_shift') !!}',
                data: {date: date, car_model_id: car_model_id},
                success: function (data) {
                    $("#shifts").empty();
                    $("#shifts").append(data);
                },
                error: function (data) {
                },
            });
        }
    </script>

@endsection