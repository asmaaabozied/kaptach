@extends('layouts.master')
@section('title',__('pages.edit'). __('pages.shuttle'). __('pages.price'))

@section('content')

    <div class="row">
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.edit'). __('pages.shuttle'). __('pages.price')}}</h3>
                </div>
                <!-- /.box-header -->
                {!! BootForm::model($shuttle_price, 'edit', ['method'=>'put', 'route'=>['shuttles-price.update',$shuttle_price->id]],['id'=>'basic-form']) !!}
                <div class="box-body">
                    {!! BootForm::select('hotel_id',__('inputs.hotel_tourism'),$hotels->prepend(__('inputs.select_hotel_tourism'),''),null,$errors,['required','class'=>'form-control']) !!}
                    {!! BootForm::select('airport_id',__('inputs.airports'),$airports->prepend(__('inputs.select_airports'),''),null,$errors,['required','class'=>'form-control']) !!}
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
