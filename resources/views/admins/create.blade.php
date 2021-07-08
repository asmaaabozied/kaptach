@extends('layouts.master')
@section('title',__('pages.create_admin'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.create_admin')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('admins.store'),'id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'username', null, __('inputs.username'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('password', 'password', null,__('inputs.password'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'email', null,__('inputs.email'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'phone', null, __('inputs.phone'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::select('gender','Gender',[''=>__('inputs.select_gender'),'male'=>__('inputs.male'),'female'=>__('inputs.female')],null,$errors,['class'=>'form-control']) !!}
                        {!! BootForm::select('role_id','Roles',$roles->prepend(__('inputs.select_role'),''),null,$errors,['class'=>'form-control']) !!}

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