@extends('layouts.master')
@section('title','Create Tours Price')
@section('styles')
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css')}}">
@endsection
@section('content')

    <div class="row">
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Create Tours Price</h3>
                </div>
                <!-- /.box-header -->
                {!! BootForm::open('create', ['url'=>route('tours-price.store'),'id'=>'basic-form']) !!}
                <div class="box-body">
                {!! BootForm::select('car_model_id','Model name',$car_models->prepend('Select Model',''),null,$errors,['required','class'=>'form-control']) !!}
                {!! BootForm::input('text', 'tourism_place', null, 'Tourism place', $errors, ['required','class'=>'form-control']) !!}
                {!! BootForm::input('number', 'number_hours', null, 'Number of Hours', $errors, ['required','class'=>'form-control']) !!}
                <!-- Date and time range -->
                    <div class="form-group">
                        <label>Start and End time :</label>

                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <input type="text" name="tour_time_range" class="form-control pull-right"
                                   id="reservationtime">
                        </div>
                        <!-- /.input group -->
                    </div>
                    {!! BootForm::input('text', 'price', null, 'Price', $errors, ['required','class'=>'form-control']) !!}
                    {!! BootForm::checkbox('with_food','With Food',1) !!}
                </div>
                <div class="box-footer">
                    {!! BootForm::submit() !!}
                </div>
            {!! BootForm::close() !!}
            <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
@section('scripts')
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script>
        $(function () {
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePicker24Hour:true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'MM/DD/YYYY HH:mm',
                }
            });
        });
    </script>
@endsection
