@extends('layouts.master')
@section('styles')
@endsection

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>Gunluk Tur</h3>

                    <p>2 New</p>
                </div>
                <div class="icon">
                    <i class="glyphicon glyphicon-heart-empty"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>Ozel Tur</h3>

                    <p>3 New</p>
                </div>
                <div class="icon">
                    <i class="glyphicon glyphicon-heart-empty"></i>
                </div>
                <a href="" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>Transfer</h3>

                    <p>1: To Airport 4: From Airport</p>
                </div>
                <div class="icon">
                    <i class="ion  ion-plane"></i>
                </div>
                <a href="{{url('/transfers')}}" class="small-box-footer">More info <i
                            class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>Shuttle</h3>

                    <p>2: To Airport 3: From Airport</p>
                </div>
                <div class="icon">
                    <i class="ion  ion-plane"></i>
                </div>
                <a href="{{url('/shuttles')}}" class="small-box-footer">More info <i
                            class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <example-component :company-id="{{auth('admin')->user()->adminable->id}}"
                       :admin-id="{{auth('admin')->user()->id}}"></example-component>

    <!-- /.row  Small boxes (Stat box)  -->
    <!-- Map box -->
    {{--<div class="box box-solid bg-light-blue-gradient">--}}
    {{--<div class="box-header">--}}
    {{--<i class="fa fa-map-marker"></i>--}}
    {{--<h3 class="box-title"> Cars</h3>--}}
    {{--</div>--}}
    {{--<div class="box-body" id="filter">--}}
    {{--<div class="col-lg-2">--}}
    {{--<select class="form-control" id="hotel">--}}
    {{--<option value="0">Select Hotel</option>--}}
    {{--@foreach($hotels as $hotel)--}}
    {{--<option value="{{$hotel->id}}">{{$hotel->name}}</option>--}}
    {{--@endforeach--}}
    {{--</select>--}}
    {{--</div>--}}
    {{--<div class="col-lg-2">--}}
    {{--<select class="form-control" id="car">--}}
    {{--<option value="0">Select Car</option>--}}
    {{--@foreach($cars as $car)--}}
    {{--<option value="{{$car->id}}">{{$car->name}}</option>--}}
    {{--@endforeach--}}
    {{--</select>--}}
    {{--</div>--}}
    {{--<div class="col-lg-2">--}}
    {{--<select class="form-control" id="status">--}}
    {{--<option value="0">Select Status</option>--}}
    {{--<option value="1">Online</option>--}}
    {{--<option value="2">Offline</option>--}}
    {{--<option value="3">On The Road</option>--}}
    {{--</select>--}}
    {{--</div>--}}
    {{--<div class="col-lg-6">&nbsp;</div>--}}
    {{--</div>--}}
    {{--<div class="box-body"> --}}
    {{--<div id="map" style="height: 500px;"></div>--}}
    {{--</div>--}}
    {{--<!-- /.box-body-->--}}
    {{--<div class="box-footer no-border">            --}}
    {{--<div class="row">--}}
    {{--<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">--}}
    {{--<div id="sparkline-2"></div>--}}
    {{--<div class="knob-label"><strong>Online</strong><small class="label pull-right bg-red" id="status1">{{$cars_count_by_status['1']}}</small></div>--}}
    {{--</div>--}}
    {{--<!-- ./col -->--}}
    {{--<div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">--}}
    {{--<div id="sparkline-1"></div>--}}
    {{--<div class="knob-label"><strong>Offline</strong><small class="label pull-right bg-green" id="status2">{{$cars_count_by_status['2']}}</small></div>--}}
    {{--</div>                --}}
    {{--<!-- ./col -->               --}}
    {{--<div class="col-xs-4 text-center">--}}
    {{--<div id="sparkline-3"></div>--}}
    {{--<div class="knob-label"><strong>On The Road</strong><small class="label pull-right bg-blue" id="status3">{{$cars_count_by_status['3']}}</small></div>--}}
    {{--</div>                            --}}
    {{--<!-- ./col -->--}}
    {{--</div>--}}
    {{--<!-- /.row -->--}}
    {{--<div><span id="lat">{{$position['lat']}}</span><span id="lng">{{$position['lng']}}</span></div>--}}
    {{--</div>--}}
    {{--</div>--}}


@endsection
@section('appjs')
    <script src="{{asset('js/app.js')}}"></script>
@endsection
@section('scripts')

@endsection