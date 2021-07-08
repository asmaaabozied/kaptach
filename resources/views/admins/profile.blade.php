@extends('layouts.master')
@section('title',__('pages.admin_profile'))
@section('styles')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.admin_profile')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($admin, 'profile', ['method'=>'put', 'route'=>['profile_update',$admin->id],'files'=>true],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        <div class="col-lg-3">
                            {!! BootForm::input('text', 'username', null,  __('inputs.username'), $errors, ['required','class'=>'form-control']) !!}
                            {!! BootForm::input('text', 'email', null,  __('inputs.email'), $errors, ['required','class'=>'form-control']) !!}
                            {!! BootForm::input('text', 'phone', null,  __('inputs.phone'), $errors, ['class'=>'form-control']) !!}
                            {!! BootForm::file('image',  __('inputs.image'), $errors, ['accept'=>'png,jpg,jpeg']) !!}
                            @if (empty($admin->image))
                               {{__('messages.there_no_image')}}
                            @else
                                <img src="{{url('uploads/admins/'.$admin->image)}}" width="100" height="100">
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
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('scripts')

@endsection