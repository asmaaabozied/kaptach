@extends('layouts.master')
@section('title','change Driver')
@section('styles')
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Change Driver</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($shifts->shifts, 'edit', ['method'=>'put', 'route'=>['drivers.update_schedule',$driver->id,$shifts->shifts->id]],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::select('driver_id','Driver',$all_drivers->prepend('Select Drivers',''),null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::select('car_id','Cars',$cars->prepend('Select Car',''),null,$errors,['required','class'=>'form-control']) !!}
                        <div class="form-group">
                            <label>
                                <span> Shift Time : ( {{date("H:i",strtotime($shifts->shifts->shift_start_time))}} -
                                    {{date("H:i",strtotime($shifts->shifts->shift_end_time))}} )
                                </span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Start shift time picker:</label>
                            <select class="form-control" id="start_time">
                                {{selectTimesOfDay(date("H:i",strtotime($shifts->shifts->shift_start_time)),date("H:i",strtotime($shifts->shifts->shift_end_time)))}}
                            </select>
                        </div>
                        <div class="form-group">
                            <label>End shift time picker:</label>
                                <select class="form-control" id="end_time" onchange="changeEndTime()" >
                                    {{selectTimesOfDay(date("H:i",strtotime($shifts->shifts->shift_start_time)),date("H:i",strtotime($shifts->shifts->shift_end_time)),1)}}
                                </select>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
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
    </section>
@endsection
@section('scripts')
    <!-- bootstrap time picker -->
    <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script>
        $(function () {
            //Timepicker
            $(".timepicker").timepicker({
                showInputs: false,
                showMeridian: false
            });

        });
    </script>
@endsection