@extends('layouts.master')
@section('title',__('pages.edit_invoice'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.edit_invoice')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($invoice, 'edit', ['method'=>'put', 'route'=>['corporate.invoices.update',$invoice->id],'files'=>true],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'client', $invoice->client->name, __('inputs.hotel_tourism'), $errors, ['required','class'=>'form-control','readonly']) !!}
                        {!! BootForm::input('text', 'price', null, __('inputs.price'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'tax', null, __('inputs.tax').' %', $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('month', 'deducted_date', $invoice->deducted_year.'-0'.$invoice->deducted_month, __('inputs.for_Date'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::select('payment_type_id',__('inputs.payment_type'),['1'=>'Cache','2'=>'Visa'],null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::textarea('notes',  null, __('inputs.notes'), $errors, ['class'=>'form-control']) !!}

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