@extends('layouts.master')
@section('title','Edit Transfer Company')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Edit Transfer Company</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($company, 'edit', ['method'=>'put', 'route'=>['companies.update',$company->id],'files'=>true],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'name', null, 'Name', $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'contact_phone', null, 'Contact Phone', $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'contact_email', null, 'Contact Email', $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::select('type',__('inputs.type'),[''=>__('pages.select').' '.__('inputs.type'),'personal'=>__('pages.personal'),'commercial'=>__('pages.commercial')],null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::file('logo', __('inputs.logo'), $errors, ['accept'=>'png,jpg,jpeg']) !!}

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