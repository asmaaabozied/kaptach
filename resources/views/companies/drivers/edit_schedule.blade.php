@extends('layouts.master')
@section('title',__('pages.edit_driver'))
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
                        <h3 class="box-title">{{__('pages.edit_driver')}}</h3>
                        <div class="pull-right">
                            {!! BootForm::linkOfDelete('drivers.soft_delete_schedule', [$shift->id], 'Schedule', 'button', true) !!}
                            {!! BootForm::routeLink('drivers.change_driver', [$shift->id],['icon'=>'fa-user']) !!}
                        </div>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($shift, 'edit', ['method'=>'put', 'route'=>['drivers.update_schedule',$shift->id]],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {{--{!! BootForm::select('driver_id','Driver',$all_drivers,null,$errors,['required','class'=>'form-control','readonly']) !!}--}}
                        {!! BootForm::input('text', 'driver_id', $shift->driver->employer->username, 'Driver', $errors, ['readonly','class'=>'form-control','readonly']) !!}
                        {!! BootForm::select('car_id','Cars',$cars->prepend('Select Car',''),null,$errors,['required','class'=>'form-control']) !!}
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                                <label>Start shift time picker:</label>
                                <div class="input-group">
                                    <input type="text" name="shift_start_time" id="starttime"
                                           value="{{date("H:i",strtotime($shift->shift_start_time))}}"
                                           class="form-control timepicker" required>

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                        </div>
                        <div class="bootstrap-timepicker">
                            <div class="form-group">
                                <label>End shift time picker:</label>

                                <div class="input-group">
                                    <input type="text" name="shift_end_time" id="endtime"
                                           value="{{date("H:i",strtotime($shift->shift_end_time))}}"
                                           class="form-control timepicker"
                                           required>

                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                        </div>
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