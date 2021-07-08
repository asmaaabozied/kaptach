@extends('layouts.master')
@section('title',__('pages.edit'). __('pages.transfer'))
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
            {!! BootForm::model($transfer, 'edit', ['method'=>'put', 'route'=>['clients.transfers.update',$transfer->id],'id'=>'basic-form','class'=>'form-horizontal']) !!}
            <div class="col-xs-9">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('pages.edit'). __('pages.transfer')}}</h3>
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
                                    <input class="form-control" size="16" type="text"
                                           value="{{$transfer->transfer_start_time}}" required
                                           name="datetimepicker" id="datetimepicker">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                            @if($transfer->type=='arrival')
                                <div class="form-group">
                                    <label class="col-md-3 control-label">{{__('inputs.flight_no')}}:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="flight_number"
                                               value="{{$transfer->flight_number}}"
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
                                            <option value="{{$car_model->id}}" @if($transfer->car_model->id == $car_model->id){{'selected'}}@endif>{{$car_model->ModelWithSeats}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">{{__('pages.status')}}:</label>
                                <div class="col-md-9">
                                    <label class="col-md-3 control-label">@if($transfer->request_status==0){{'Pending'}}@else {{'Approved'}}@endif</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">#{{__('pages.guests') }}:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" readonly
                                           value="{{$transfer->number_of_booking}}"
                                           min="1" name="number_of_booking"
                                           id="number_of_booking" required/>
                                </div>
                            </div>


                        </div>
                        <div class="col-xs-4">
                            <div class="callout callout-info">
                                <h7>{{$transfer->airport->name}}</h7>
                                <input name="airport_id" type="hidden" value="{{$transfer->airport->id}}">
                            </div>
                            <div class="callout callout-info">
                                <h7> {{$transfer->transferable->name}}</h7>
                            </div>
                            <div class="callout callout-info">
                                <h7>{{$transfer->type}}</h7>
                                <input name="type" type="hidden" value="{{$transfer->type}}">
                            </div>
                            <div class="callout callout-info">
                                <h7 id="price">{{$transfer->price}}</h7>
                                <input name="price" type="hidden" id="price_input" value="{{$transfer->price}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-3">
                @if($transfer->driver)
                    <div class="box box-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-aqua-active">
                            <h3 class="widget-user-username">{{$transfer->driver->first_name}} {{$transfer->driver->last_name}}</h3>
                            <h5 class="widget-user-desc">{{$transfer->driver->phone}}</h5>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>

                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle"
                                 src="{{asset('uploads/drivers/'.$transfer->driver->image)}}"
                                 alt="User Avatar">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">300</h5>
                                        <span class="description-text">{{__('pages.transfers') }}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">40</h5>
                                        <span class="description-text">{{__('pages.shuttles') }}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">35</h5>
                                        <span class="description-text">{{__('pages.tours') }}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{__('pages.guests') }}</h3>
                    </div>
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
                                @foreach($transfer->guests as $guest)
                                    <tr>
                                        <td><input type="checkbox" name="record" class="filled-in"></td>
                                        <td><input type="text" value="{{$guest->identity_number}}"
                                                   class=" form-control" min="1" name="identity_number[]"
                                                   required>
                                        </td>
                                        <td><input type="text" class="form-control" name="first_name[]" id="first_name"
                                                   value="{{$guest->first_name}}"
                                                   required>
                                        </td>
                                        <td><input type="text" class="form-control" name="last_name[]" id="last_name"
                                                   value="{{$guest->last_name}}"
                                                   required>
                                        </td>
                                        <td><select name="gender[]" required class="form-control">
                                                <option value="female" @if($transfer->gender=='female'){{'selected'}}@endif>
                                                    {{__('inputs.female')}}
                                                </option>
                                                <option value="male" @if($transfer->gender=='male'){{'selected'}}@endif>
                                                    {{__('inputs.male')}}
                                                </option>
                                            </select></td>
                                        <td>
                                            <select name="nationality[]" required class="form-control">
                                                <option value="">{{__('pages.select'). __('pages.nationality')}}</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{$country->nationality}}" @if($country->nationality==$guest->nationality){{'selected'}}@endif>{{$country->nationality}}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td><input type="text" class="form-control" name="phone[]" id="phone" required
                                                   value="{{$guest->phone}}">
                                        </td>
                                        <td><input type="text" class="form-control" min="1" name="room_number[]"
                                                   value="{{$guest->pivot->room_number}}"
                                                   id="room_number"
                                                   required>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{__('inputs.notes')}}:</label>
                            <div class="col-md-6">
                       <textarea rows="4" class="form-control no-resize"
                                 placeholder="{{__('inputs.placeholder_notes')}}" name="notes">{{$transfer->notes}}</textarea>
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
    <script>

        $(document).ready(function () {
            //Date picker
            $(".form_datetime").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                startDate: new Date(),
                autoclose:true,
            });
        });


        //get transfer price by hotel ,car model and type
        //transfer_price on hotel or car model change

        function eventOnChange() {
            getTransferPrice();
        }


        function getTransferPrice() {
            var hotel_id = $('#hotel_id').val();
            var car_model_id = $('#car_model_id').val();
            var airport_id ={{$transfer->airport->id}};
            var type = '{{$transfer->type}}';
            $.ajax({
                type: 'POST',
                headers: {
                    "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
                },
                url: '{!! route('clients.transfer_price') !!}',
                data: {hotel_id: hotel_id, car_model_id: car_model_id, airport_id: airport_id, type: type},
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