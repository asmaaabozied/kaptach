@extends('layouts.master')
@section('title','Create Role')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Create Role</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('roles.store'),'id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'name', null, 'name', $errors, ['required','class'=>'form-control']) !!}
                        @foreach($permissions as $value)
                            {!! BootForm::checkbox('permission[]',$value->name,$value->id,false) !!}
                        @endforeach
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