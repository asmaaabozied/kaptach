@extends('layouts.master')
@section('title',__('pages.create_invoice'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.create_invoice')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('my-clients.store_invoice',$client_id),'id'=>'basic-form','files'=>true]) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'price', null, __('inputs.price'), $errors, ['required','class'=>'form-control']) !!}
                        {{--<div class="form-group col-md-12">--}}
                            {{--<div class="col-md-6">--}}
                                {{--<label for="tax">tax</label>--}}
                                {{--<input type="number" class="form-control">--}}
                            {{--</div>--}}
                            {{--<div class="col-md-6">%</div>--}}
                        {{--</div>--}}
                        {!! BootForm::input('text', 'tax', null, __('inputs.tax').' %', $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('month', 'deducted_date', null, __('inputs.for_date'), $errors, ['required','class'=>'form-control']) !!}
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