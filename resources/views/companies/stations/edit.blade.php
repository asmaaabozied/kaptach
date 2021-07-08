@extends('layouts.master')
@section('title',__('pages.edit'). __('pages.stations'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.edit'). __('pages.stations')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($station, 'edit', ['method'=>'put', 'route'=>['stations.update',$station->id]],['id'=>'basic-form']) !!}
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