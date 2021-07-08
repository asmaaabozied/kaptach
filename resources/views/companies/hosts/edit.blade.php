@extends('layouts.master')
@section('title',__('pages.edit_host'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.edit_host')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($host, 'edit', ['method'=>'put', 'route'=>['hosts.update',$host->id],'files'=>true],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'username', null, __('inputs.username'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'first_name', null,__('inputs.first_name'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'last_name', null, __('inputs.last_name'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('email', 'email', null, __('inputs.email'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::select('gender',__('inputs.select_gender'),['male'=>__('inputs.male'),'female'=>__('inputs.female')],null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::select('airport_id',__('inputs.airports'),$airports,$host->host->airport_id,$errors,['required','class'=>'form-control']) !!}
                        {{--{!! BootForm::input('text', 'phone', null, __('inputs.phone'), $errors, ['class'=>'form-control']) !!}--}}
                        <div class="form-group @error('phone'){{'has-error'}} @enderror">
                            <label>{{__('inputs.phone')}}</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i>+09</i>
                                </div>
                                <input class="form-control form-control" name="phone" type="text" id="phone" value="{{$host->phone}}">
                            </div>
                            @error('phone')
                            <div class="help-block">{{ $message }}</div>
                            @enderror
                        </div>
                        {!! BootForm::file('image',  __('inputs.image'), $errors, ['accept'=>'png,jpg,jpeg']) !!}

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