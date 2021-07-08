@extends('layouts.master')
@section('title',__('pages.create'). __('pages.transfer'). __('pages.price'))

@section('content')

        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.create'). __('pages.transfer'). __('pages.price')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('transfers-price.store'),'id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::select('car_model_id',__('inputs.model_name'),$car_models->prepend(__('inputs.select_model'),''),null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::select('hotel_id',__('inputs.hotel_tourism'),$hotels->prepend(__('inputs.select_hotel_tourism'),''),null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::select('airport_id',__('inputs.airport'),$airports->prepend(__('inputs.select_airports'),''),null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'departure_price', null, __('inputs.departure'). __('inputs.price'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'arrival_price', null, __('inputs.arrival'). __('inputs.price'), $errors, ['required','class'=>'form-control']) !!}

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
@endsection
