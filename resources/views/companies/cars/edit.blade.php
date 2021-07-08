@extends('layouts.master')
@section('title',__('pages.edit_car'))
@section('styles')
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/colorpicker/bootstrap-colorpicker.min.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-8">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.edit_car')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($car, 'edit', ['method'=>'put', 'route'=>['cars.update',$car->id]],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'name', null, __('inputs.name'), $errors, ['required','class'=>'form-control']) !!}
                        <div class="form-group ">
                            <label for="car_model_id[]">{{__('inputs.model_name')}}</label>
                            <select required="" class="form-control form-control" multiple="" name="car_model_id[]">
                                @foreach($carmodels as $carmodel)
                                    <?php $found=false;?>
                                    @foreach($car->carModel as $value)
                                        @if($carmodel->id == $value->id)
                                            <?php $found=true;
                                            break;
                                          ?>
                                        @endif
                                    @endforeach
                                    <option value="{{$carmodel->id}}" {!! ($found)? "selected":''; !!}>{{$carmodel->ModelWithSeats}}</option>

                                @endforeach
                            </select>
                        </div>
                        {{--{!! BootForm::select('car_model_id[]',__('inputs.model_name'),$carmodels, null,$errors,['required','class'=>'form-control','multiple']) !!}--}}
                        {!! BootForm::input('text', 'plate_number', null, __('inputs.plate_number'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'color', null,  __('inputs.color'), $errors, ['required','class'=>'form-control my-colorpicker1']) !!}
                        {!! BootForm::input('text', 'brand', null,  __('inputs.brand'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'licence_plate', null, __('inputs.licence_plate'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'manufacture_year', null,__('inputs.manufacture_year'), $errors, ['id'=>'datepicker','class'=>'form-control']) !!}
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
    <!-- bootstrap datepicker -->
    <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('assets/plugins/colorpicker/bootstrap-colorpicker.min.js')}}"></script>
    <script>
        //Colorpicker
        $(".my-colorpicker1").colorpicker();
        //Date picker
        $('#datepicker').datepicker({
            autoclose: true,
            minViewMode: 2,
            format: 'yyyy'
        });
    </script>
@endsection