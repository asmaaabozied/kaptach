@extends('layouts.master')
@section('title',__('pages.edit_payment'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.edit_payment')}}/h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($payment, 'edit', ['method'=>'put', 'route'=>['payments.update',$payment->id],'files'=>true],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::select('client_id',__('inputs.hotel_tourism'),$clients->prepend(__('inputs.select_hotel_tourism'),''),null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'amount', null, __('inputs.amount'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('month', 'deducted_date', $payment->deducted_year.'-0'.$payment->deducted_month, __('inputs.for_date'), $errors, ['required','class'=>'form-control']) !!}
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