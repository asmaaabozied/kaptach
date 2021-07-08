@extends('layouts.master')
@section('title',__('pages.reset_password'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.reset_password')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($employer, 'edit', ['method'=>'put', 'route'=>['drivers.update_password',$employer->id],'id'=>'basic-form']) !!}

                    <div class="box-body">
                        {!! BootForm::input('password', 'password', null, __('inputs.new_password'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('password', 'password_confirmation', null, __('inputs.confirm_password'), $errors, ['required','class'=>'form-control']) !!}

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