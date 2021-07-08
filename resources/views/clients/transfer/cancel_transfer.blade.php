@extends('layouts.master')
@section('title',__('pages.cancel').  __('pages.transfers'))
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-8">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.cancel')}} {{ __('pages.transfers')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($transfer, 'cancel', ['method'=>'put', 'route'=>['clients',$transfer->id]],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::textarea('cancel_reason', null, __('inputs.cancel_reason'), $errors, ['required','class'=>'form-control']) !!}
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