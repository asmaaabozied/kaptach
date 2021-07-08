@extends('layouts.master')
@section('title',__('pages.change_password'))
@section('styles')
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.change_password')}}</h3>
                </div>
                <!-- /.box-header -->
                {!! BootForm::model($admin, 'change_password', ['method'=>'put', 'route'=>['update_password',$admin->id]],['id'=>'basic-form']) !!}                
                <div class="box-body">
                    <div class="col-lg-3">
                    {!! BootForm::input('password', 'old_password', null, __('inputs.old_password'), $errors, ['required','class'=>'form-control']) !!}
                    {!! BootForm::input('password', 'new_password', null, __('inputs.new_password'), $errors, ['required','class'=>'form-control']) !!}
                    {!! BootForm::input('password', 'new_password_confirmation', null, __('inputs.confirm_password'), $errors, ['required','class'=>'form-control']) !!}

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

@endsection