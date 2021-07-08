@extends('layouts.master')
@section('title','Edit Role')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Edit Role</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($role, 'edit', ['method'=>'put', 'route'=>['roles.update',$role->id]],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'name', null, 'Name', $errors, ['required','class'=>'form-control']) !!}
                        @foreach($permissions as $value)
                            {!! BootForm::checkbox('permission[]',$value->name,$value->id,in_array($value->id, $rolePermissions) ? true : false) !!}
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