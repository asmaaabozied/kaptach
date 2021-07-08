@extends('layouts.master')
@section('title',__('pages.create'). __('pages.price'))

@section('content')

    <div class="row">
        {!! BootForm::open('create', ['url'=>route('my-clients.store_price',[$hotelId]),'id'=>'basic-form']) !!}
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.create'). __('pages.price')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-4">
                        {{__('pages.airports')}}
                    </div>
                    <div class="col-md-3">
                        {{__('pages.arrival')}}
                    </div>
                    <div class="col-md-3">
                        {{__('pages.departure')}}
                    </div>
                    <br> <br>
                    @foreach($airports as $airport)
                        @php $found= 'false' @endphp
                        <div class="col-md-4">
                            <select class="form-control" disabled="" name="shuttle[{{$airport->id}}][id]">
                                <option value="{{$airport->id}}">{{$airport->name}}</option>
                            </select>
                        </div>
                        @foreach($shuttles_price as $shuttle_price)
                            @if($shuttle_price->airport_id == $airport->id)
                                @php $found= 'true' @endphp
                                @break;
                            @endif
                        @endforeach
                        @if($found === 'true')
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="arrival price"
                                       name="shuttle[{{$airport->id}}][departure_price]" required
                                       value="{{$shuttle_price->departure_price}}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="departure price"
                                       name="shuttle[{{$airport->id}}][arrival_price]" required
                                       value="{{$shuttle_price->arrival_price}}">
                            </div>
                            <br> <br>
                        @else
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="departure price "
                                       name="shuttle[{{$airport->id}}][departure_price]" required
                                       value="0.00">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="arrival price" required
                                       name="shuttle[{{$airport->id}}][arrival_price]"
                                       value="0.00">
                            </div>
                            <br> <br>
                        @endif
                    @endforeach
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.transfer'). __('pages.price')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @foreach($transfer_prices as $transfer_price)
                        <div class="row">
                            <div class="col-md-4">
                                {{__('inputs.airports')}} : <select class="form-control" disabled=""
                                                  name="transfer[{{$transfer_price['airport_id']}}][airport_id]">
                                    <option value="{{$transfer_price['airport_id']}}">{{$transfer_price['airport_name']}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            {{__('inputs.car_models')}}
                        </div>
                        <div class="col-md-3">
                            {{__('inputs.arrival')}}
                        </div>
                        <div class="col-md-3">
                            {{__('inputs.departure')}}
                        </div>
                        @foreach($transfer_price['car_model'] as $model)

                            <div class="col-md-4">
                                <select class="form-control" disabled=""
                                        name="transfer[{{$transfer_price['airport_id']}}][{{$model['id']}}]">
                                    <option value="{{$model['id']}}">{{$model['car_model_name']}}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="{{__('inputs.arrival'). __('inputs.price')}}" required
                                       name="transfer[{{$transfer_price['airport_id']}}][{{$model['id']}}][arrival_price]"
                                       value=" {{$model['arrival_price']}}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="{{__('inputs.departure'). __('inputs.price')}} price" required
                                       name="transfer[{{$transfer_price['airport_id']}}][{{$model['id']}}][departure_price]"
                                       value="{{$model['departure_price']}}">
                            </div>
                        @endforeach
                    @endforeach
                </div>


                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>

        <div class="pull-left">
            {!! BootForm::submit() !!}
        </div>
        {!! BootForm::close() !!}
    </div>
    <!-- /.row -->
@endsection
