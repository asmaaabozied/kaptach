@extends('layouts.master')
@section('title',__('pages.push_notifications'))


@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.create'). __('pages.stations')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('push-notifications.store'),'id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'name', null, __('pages.name'), $errors, ['required','class'=>'form-control']) !!}
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