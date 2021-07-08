@extends('layouts.master')
@section('title','Create Transfer Company')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Create Transfer Company</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('companies.store'),'id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'name', null, 'Name', $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'contact_phone', null, 'Contact Phone', $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'contact_email', null, 'Contact Email', $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::select('type',__('inputs.type'),[''=>__('pages.select').' '.__('inputs.type'),'personal'=>__('pages.personal'),'commercial'=>__('pages.commercial')],null,$errors,['required','class'=>'form-control']) !!}
                        <h3 class="box-title">Login Data</h3>
                        {!! BootForm::input('text', 'username', null, 'Username', $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('password', 'password', null, 'Password', $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('email', 'email', null, 'Email', $errors, ['required','class'=>'form-control']) !!}

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