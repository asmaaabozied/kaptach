@extends('layouts.master')
@section('title',__('pages.settings'))
@section('styles')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-fw fa-cog"></i>{{__('pages.settings')}}</h3>
                </div>
                <!-- /.box-header -->
                {!! BootForm::model($update, 'edit', ['method'=>'put', 'route'=>['settings_update'],'files'=>true],['id'=>'basic-form']) !!}
                <div class="box-body">
                    <div class="col-lg-4">
                        {!! BootForm::input('text', 'name', null, __('inputs.name'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'contact_phone', null, __('inputs.contact_phone'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'contact_email', null, __('inputs.contact_email'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'website', null,  __('inputs.website'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::file('logo',  __('inputs.logo'), $errors, ['accept'=>'png,jpg,jpeg']) !!}
                        @if (empty($update->logo))
                            {{__('messages.there_no_image')}}
                        @else
                            <img src="{{$update->logo['original']}}" width="100" height="100">
                        @endif
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
        <!-- /.row -->
    </section>
@endsection

@section('scripts')

@endsection