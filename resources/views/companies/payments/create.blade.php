@extends('layouts.master')
@section('title',__('pages.create_payment'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.create_payment')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('payments.store'),'id'=>'basic-form','files'=>true]) !!}
                    <div class="box-body">
                        {!! BootForm::select('client_id',__('inputs.hotel_tourism'),$clients->prepend(__('inputs.select_hotel_tourism'),''),null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'amount', null, __('inputs.amount'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('month', 'deducted_date', null, __('inputs.for_date'), $errors, ['required','class'=>'form-control']) !!}
                        {{--{!! BootForm::input('year', 'deducted_year', null, 'Year', $errors, ['required','class'=>'form-control']) !!}--}}
                        {!! BootForm::select('payment_type_id',__('inputs.payment_type'),['1'=>'Cache','2'=>'Visa'],null,$errors,['required','class'=>'form-control']) !!}                    </div>
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