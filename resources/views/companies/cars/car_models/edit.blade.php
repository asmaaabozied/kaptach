@extends('layouts.master')
@section('title',__('pages.edit_car_model'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.edit_car_model')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($carmodel, 'edit', ['method'=>'put', 'route'=>['carmodels.update',$carmodel->id],'files'=>true],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'model_name', null, __('inputs.model_name'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('number', 'max_bags', null, __('inputs.max_bags').' <i class="fa fa-suitcase"></i> ', $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('number', 'max_seats', null, __('inputs.max_seats').' <i class="fa fa-male"></i>', $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::file('image', __('inputs.image'), $errors, ['accept'=>'png,jpg,jpeg']) !!}
                        {!! BootForm::textarea( 'model_description', null, __('inputs.model_description'), $errors, ['class'=>'form-control']) !!}
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