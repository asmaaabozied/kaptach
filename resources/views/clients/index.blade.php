@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/dist/css/hvrbox.css')}}">
    <style>
        .btn:focus {
            background-color: #d43f3a;
        }

        table#car_models_table {
            border-collapse: collapse;
        }

        #car_models_table tr {
            background-color: #eee;
            border-top: 1px solid #fff;
        }

        #car_models_table tr:hover {
            background-color: #ccc;
        }

        #car_models_table th {
            background-color: #fff;
        }

        #car_models_table th, #car_models_table td {
            padding: 3px 5px;
        }

        #car_models_table td:hover {
            cursor: pointer;
        }

        .btn-dir {
            margin: 0;
            padding: 0;
            border: none;
            outline: none;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <a href="#" class="box">
                <div class="btn btn-block btn-info btn-lg">
                    <div class="inner">
                        <p>GUNLUK TUR</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <a href="#" class="box">
                <div class="btn btn-block btn-success btn-lg">
                    <div class="inner">
                        <p>OZEL TUR</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="btn btn-block btn-warning btn-lg" id="btn_transfer">
                <div class="inner">
                    <p>TRANSFER</p>
                </div>
            </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <a href="#" class="box">
                <div class="btn btn-block btn-danger btn-lg">
                    <div class="inner">
                        <p>SHUTTLE</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
    </div>
    <!-- Info boxes -->
    <!-- /.row -->
    <div class="row" style="margin-top: 10px;">

        <!-- Left col -->
        <div class="col-md-8">
            <!-- Map box -->
            <div class="box box-success">
                <div class="box-header">
                    <i class="fa fa-map-marker"></i>
                    <h3 class="box-title"> Map</h3>
                </div>
                <div class="box-body">
                    <div id="map" style="height: 450px;"></div>
                </div>
                <!-- /.box-body-->
                <div class="box-footer no-border">
                    <div class="row">
                        <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                            <div id="sparkline-1"></div>
                            <div class="knob-label">Offline</div>
                        </div>
                        <div><span id="lat">{{$client->lat}}</span><span id="lang">{{$client->lang}}</span>
                        </div>
                        <!-- ./col -->
                        <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                            <div id="sparkline-2"></div>
                            <div class="knob-label">Online</div>
                        </div>
                        <!-- ./col -->
                        <div class="col-xs-4 text-center">
                            <div id="sparkline-3"></div>
                            <div class="knob-label">On The Road</div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3">
        @foreach ($airports as $airport)
            <!-- Info Boxes Style 2 -->

                <button class="btn-dir btn-block"
                        onclick="calculate({{$client->lat}},{{$client->lang}},{{$airport->lat}},{{$airport->lang}})">

                    <div class="info-box bg-aqua">

                        <img src="{{$airport->departure_image['thumb']}}" style="height: 90px;width: 90px;float: left"
                             class="hvrbox-layer_bottom" alt="{{$airport->name}}">
                        <div class="info-box-content">
                            <h3> {{ $airport->name }}<i class="fa fa-plane"></i></h3>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </button>

                <!-- /.info-box -->
                <button class="btn-dir btn-block" onclick="calculate({{$airport->lat}},{{$airport->lang}},{{$client->lat}},{{$client->lang}})">
                    <div class="info-box bg-green">
                        <img src="{{$airport->arrival_image['thumb']}}" style="height: 90px;width: 90px;float: left"
                             class="hvrbox-layer_bottom" alt="{{$airport->name}}">

                        <div class="info-box-content">
                            <h3> {{ $airport->name }}<i class="fa fa-plane fa-rotate-180"></i></h3>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </button>

            @endforeach
        </div>

        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Car Models</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table no-margin" id="car_models_table">
                            <tbody>
                            @foreach ($car_models as $car_model)
                                <tr>
                                    <td>
                                        <div class="sparkbar" data-color="#00a65a" data-height="20"><img
                                                    src='{{ $car_model->image['thumb']}}' width="100" height="50"
                                                    id='img-preview'></div>
                                    </td>
                                    <td><b>{{ $car_model->model_name }}</b><br/><i class="fa fa-male"></i>
                                        x {{ $car_model->max_seats }} <i class="fa fa-suitcase"></i>
                                        x {{ $car_model->max_bags }}
                                    </td>
                                    <td><b>Cars</b><br/><span style="color: rgba(0, 0, 0, 0.6)"><i class="fa fa-car"
                                                                                                   aria-hidden="true"></i> X {{rand(0, 5)}}</span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
            </div>
        </div>
        <!-- /.box-body -->


    </div>
    <!-- Transfers Modal -->
    @include('modals.transfermodel')
@endsection
@section('gmap')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCF-uTGI4iQOD6LIlR9yTECB-X0w5TO8no&callback=initMap"
            type="text/javascript"></script>
    <script type="text/javascript" src="{{asset('assets/dist/js/map.js')}}"></script>
@endsection
@section('scripts')
    <script>
        /*   Car model click */
        $(document).ready(function () {
            $('#btn_transfer').click(function () {
                $('#transfersModal').modal();
            });

                $("button.btn-dir").click(function() {  // on click...
                    $("button.btn-dir")
                        .hide()  // ...hide all other previus opened elements...
                        .eq($(this).index('.btn-dir')) // ... select right one by index of clicked .showJSON element...
                        .toggle(); // and show/hide it
                });


            $('#car_models_table tr').click(function () {
                if (document.getElementById('s_type').value == "hotel_airport") {
                    $('#airportsModal').modal();
                    document.getElementById('btn_hotel').focus();
                }
                else {
                    var id = document.getElementById('s_airport').value;
                    document.getElementById('btn_airport' + id).focus();
                    find_car();
                }
            });

        });

        /* modal position */
        $('#airportsModal').css("margin-top", $(window).height() / 2 - 100);

        /*********** buttons click **************/

        //car model selected
        function carmodel_selected(id) {
            //change_location($id,'airport');
            $('#airportsModal').modal('hide');
            document.getElementById('s_airport').value = id;
            document.getElementById('s_type').value = "hotel_airport";
            document.getElementById('btn_hotel').focus();
            find_car();
        }


    </script>
    <script>
        {{--function initMap() {--}}
        {{--var latitude = parseFloat(document.getElementById('lat').innerText);--}}
        {{--var longitude = parseFloat(document.getElementById('lang').innerText);--}}
        {{--var hotel_name = "{{$client->name}}";--}}

        {{--map = new google.maps.Map(document.getElementById('map'), {--}}
        {{--center: {lat: latitude, lng: longitude},--}}
        {{--zoom: 15--}}
        {{--});--}}
        {{--//Hotel Marker--}}
        {{--addMarker(latitude,longitude,hotel_name);--}}
        {{--//add small cars--}}
        {{--var lt = [latitude,latitude+0.005,latitude+0.010];--}}
        {{--var lg = [longitude,longitude+0.005,longitude+0.010];--}}
        {{--for (var i = 0; i < 3; i++) {--}}
        {{--AddCar(lt[i] ,lg[i],map);--}}
        {{--}--}}

        {{--}--}}
        /***************** AddMarker *****************/
        function addMarker(latitude, longitude, title) {
            var LatLng = {lat: latitude, lng: longitude};
            var marker = new google.maps.Marker({
                position: LatLng,
                map: map,
                title: title
            });
            return marker;
        }

        /****************** Add car*****************/

        //************************** change_location type: Airport or hotel ************************/
        function change_location(id, type) {

            $.ajax({
                type: 'GET',
                url: '{{url("clients/dashboard/changePosition")}}' + '/' + type + '/' + id,
                success: function (data) {
                    var marker = addMarker(parseFloat(data.lat), parseFloat(data.lang), data.name);
                    map.setCenter(marker.getPosition())
                    //alert(type);alert(id);alert(data.id);alert(data.name);
                    //add small cars
                    var latitude = parseFloat(data.lat);
                    var longitude = parseFloat(data.lng);
                    var lt = [latitude, latitude + 0.010, latitude + 0.050];
                    var lg = [longitude, longitude + 0.010, longitude + 0.030];
                    for (var i = 0; i < 3; i++) {
                        AddCar(lt[i], lg[i], map);
                    }
                },
                error: function (data) {
                    $('#results').html(data.responseText);
                },

            });

        }

        /******************* find car ************************/
        function find_car() {
            document.getElementById("span_loading").style.visibility = "visible";
            setTimeout(function () {
                document.getElementById("span_loading").style.visibility = "hidden";
            }, 2000);
        }
    </script>
@endsection