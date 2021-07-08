@extends('layouts.master')
@section('title',__('pages.shuttle'))
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/dist/css/hvrbox.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    @foreach ($airports as $airport)
                        <div class="box-header with-border">
                            <h3>{{$airport->name}}</h3>
                        </div>
                        <a href="{{route('shuttles.schedule',['id' => $airport->id,'type'=>'arrival'])}}">
                            <div class="hvrbox">
                                <h4><span class="glyphicon glyphicon-triangle-right">{{__('pages.arrival')}}</span></h4>
                                <img src="{{$airport->image['thumb']}}" width="200" height="200"
                                     class="hvrbox-layer_bottom" alt="{{$airport->name}}">
                                <div class="hvrbox-layer_top">
                                    <div class="hvrbox-text"> {{__('pages.arrival_to')}} {{$airport->name}}</div>
                                </div>
                            </div>
                        </a>
                        <a href="{{route('shuttles.schedule',['id' => $airport->id,'type'=>'departure'])}}">
                            <div class="hvrbox">
                                <h4><span class="glyphicon glyphicon-triangle-left">{{__('pages.departure')}}</span></h4>
                                <img src="{{$airport->image['thumb']}}" width="200" height="200"
                                     class="hvrbox-layer_bottom" alt="{{$airport->name}}">
                                <div class="hvrbox-layer_top">
                                    <div class="hvrbox-text"> {{__('pages.departure_from')}} {{$airport->name}}</div>
                                </div>
                            </div>
                        </a>
                        <div>
                            <hr size="30">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
