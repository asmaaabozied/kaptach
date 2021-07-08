@extends('layouts.master')
@section('title',__('pages.create_driver'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.create_driver')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('drivers.store'),'files'=>true,'id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'username', null, __('inputs.username'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'first_name', null, __('inputs.first_name'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'last_name', null, __('inputs.last_name'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('email', 'email', null, __('inputs.email'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::select('gender',__('inputs.select_gender'),['male'=>__('inputs.male'),'female'=>__('inputs.female')],null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::input('password', 'password', null, __('inputs.password'), $errors, ['required','class'=>'form-control']) !!}
                       <div class="form-group @error('phone'){{'has-error'}} @enderror">
                           <label>{{__('inputs.phone')}}</label>
                           <div class="input-group">
                               <div class="input-group-addon">
                                   <i>+09</i>
                               </div>
                               <input class="form-control form-control" name="phone" type="text" id="phone">
                           </div>
                           @error('phone')
                           <div class="help-block">{{ $message }}</div>
                           @enderror
                       </div>

                        {{--{!! BootForm::input('text', 'phone', null, __('inputs.phone'), $errors, ['class'=>'form-control']) !!}--}}
                        {!! BootForm::file('profile_pic',  __('inputs.image'), $errors, ['accept'=>'png,jpg,jpeg']) !!}
                        @if(auth('admin')->user()->adminable->id == 1)
                            {!! BootForm::select('driver_type',__('inputs.type'),[''=>__('pages.select').' '.__('inputs.type'),'personal'=>__('pages.personal'),'commercial'=>__('pages.commercial')],null,$errors,['class'=>'form-control']) !!}                        @endif
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