@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" href="{{asset('assets/dist/css/hvrbox.css')}}">
    <style>
        .btn:focus{
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
    <div class="box box-info" style="margin-top: 50px;">
        <div id="selected_airport_hotel">
            <input type="hidden" id="s_airport"/>
            <input type="hidden" id="s_hotel" value="{{$client->id}}"/>
            <input type="hidden" id="s_type" value="hotel_airport"/>
        </div>
        <div class="row" style="margin-top: 10px; margin-left: 10px; margin-bottom: 10px; ">
            <button id="btn_hotel" type="button" class="btn btn-danger btn-lg" title="Find Hotel Location"
                    onclick="hotel_click({{$client->id}})">
                <span class="glyphicon glyphicon-map-marker"></span> {{ $client->name }}
                <span style="color: rgba(0, 0, 0, 0.6)"><i class="fa fa-car"
                                                           aria-hidden="true"></i>{{rand(0, 5)}}</span>
            </button>
            @foreach ($airports as $airport)
                <button id="btn_airport{{$airport->id}}" type="button" class="btn btn-info btn-lg"
                        title="Find Airport Location" onclick="airport_click('airport')">
                    <span class="glyphicon glyphicon-map-marker"></span> {{ $airport->name }}
                    <span style="color: rgba(0, 0, 0, 0.6)"><i class="fa fa-car"
                                                               aria-hidden="true"></i>{{rand(0, 5)}}</span>
                </button>
            @endforeach
            <span style="visibility: hidden" id="span_loading"><img src="{{ asset('assets/img/loading.gif') }}"
                                                                    width="100" height="100" id="img_loading"/>Find a car..please wait! </span>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
                <!-- Map box -->
                <div class="box box-solid bg-light-blue-gradient">
                    <div class="box-header">
                        <i class="fa fa-map-marker"></i>
                        <h3 class="box-title"> Cars</h3>
                    </div>
                    <div class="box-body">
                    <!--
    <div id="floating-panel" class="col-lg-2" >
    <b>Start: </b>
    <select id="start" class="form-control"  disabled>
    <option value="@if(isset($start)){{$start}}@endif">@if(isset($start)){{$start}}@endif</option>
    </select>
    <b>End: </b>
    <select id="end" class="form-control"  disabled>
    <option value="@if(isset($end)){{$end}}@endif">@if(isset($end)){{$end}}@endif</option>
    </select>
    </div>
    -->
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
                    <!-- /.box-body -->
                    <!-- Modal -->
                @include('modals.airportmodal')
                <!-- Transfers Modal -->
                    @include('modals.transfermodel')

                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>

@endsection
@section('gmap')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCF-uTGI4iQOD6LIlR9yTECB-X0w5TO8no&callback=initMap"
            type="text/javascript"></script>
    {{--<script type="text/javascript" src="{{asset('assets/dist/js/map.js')}}"></script>--}}
@endsection
@section('scripts')
    <script>
        /*   Car model click */
        $(document).ready(function () {
            $('#btn_transfer').click(function () {
                $('#transfersModal').modal();
            });

            $('#car_models_table tr').click(function () {
                if (document.getElementById('s_type').value == "hotel_airport"){
                    $('#airportsModal').modal();
                    document.getElementById('btn_hotel').focus();
                }
                else{
                    var id = document.getElementById('s_airport').value;
                    document.getElementById('btn_airport'+id).focus();
                    find_car();
                }
            });

        });

        /* modal position */
        $('#airportsModal').css("margin-top", $(window).height() / 2 - 100);

        /*********** buttons click **************/
        function hotel_click(id){
            document.getElementById('s_hotel').value = id;
            document.getElementById('s_type').value = "hotel_airport";
            document.getElementById('s_airport').value = "";
            change_location(id,'hotel');
                    @for ($i=0 ; $i<count($airports); $i++)
            var aid = {{$airports[$i]->id}};
            document.getElementById('btn_airport'+aid).className = "btn btn-info btn-lg";
            @endfor
        }
        function airport_click(id){
            document.getElementById('s_airport').value = id;
            document.getElementById('s_type').value = "airport_hotel";
            change_location(id,'airport');
            document.getElementById('btn_hotel').className = "btn btn-warning btn-lg";
                    @for ($i=0 ; $i<count($airports); $i++)
            var id1 = {{$airports[$i]->id}};
            if(id1 != id)
                document.getElementById('btn_airport'+id1).className = "btn btn-info btn-lg";
            else
                document.getElementById('btn_airport'+id1).className = "btn btn-danger btn-lg";
            @endfor
        }
        //car model selected
        function carmodel_selected($id){
            //change_location($id,'airport');
            $('#airportsModal').modal('hide');
            document.getElementById('s_airport').value = $id;
            document.getElementById('s_type').value = "hotel_airport";
            document.getElementById('btn_hotel').focus();
            find_car();
        }
        //transfer selected
        function transfer_selected(airport_id,type){
            window.location = '{{url("hotel/transfers/create/")}}'+'/'+type+'/'+airport_id;
        }

    </script>
    <script>
        function initMap() {
            var latitude = parseFloat(document.getElementById('lat').innerText);
            var longitude = parseFloat(document.getElementById('lang').innerText);
            var hotel_name = "{{$client->name}}";

            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: latitude, lng: longitude},
                zoom: 15
            });
            //Hotel Marker
            addMarker(latitude,longitude,hotel_name);
            //add small cars
            var lt = [latitude,latitude+0.005,latitude+0.010];
            var lg = [longitude,longitude+0.005,longitude+0.010];
            for (var i = 0; i < 3; i++) {
                AddCar(lt[i] ,lg[i],map);
            }

        }
        /***************** AddMarker *****************/
        function addMarker(latitude,longitude,title){
            var LatLng = {lat: latitude, lng: longitude};
            var marker = new google.maps.Marker({
                position: LatLng,
                map: map,
                title: title
            });
            return marker;
        }
        /****************** Add car*****************/
        function AddCar(latitude,longitude,map) {
            var icon = { // car icon
                path: 'M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759   c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336   h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805',
                scale: 0.4,
                fillColor: "#427af4", //<-- Car Color, you can change it
                fillOpacity: 1,
                strokeWeight: 1,
                anchor: new google.maps.Point(0, 5),
                rotation: 180//data.val().angle //<-- Car angle
            };
            var LatLng = {lat: latitude, lng: longitude};
            var marker = new google.maps.Marker({
                position: LatLng,
                icon: icon,
                map: map,
                title: 'car model'
            });
        }
        //************************** change_location type: Airport or hotel ************************/
        function change_location(id,type){

            $.ajax({
                type:'GET',
                url:'{{url("clients/dashboard/changePosition")}}'+'/'+type+'/'+id,
                success:function(data){
                    var marker = addMarker(parseFloat(data.lat),parseFloat(data.lang),data.name);
                    map.setCenter(marker.getPosition())
                    //alert(type);alert(id);alert(data.id);alert(data.name);
                    //add small cars
                    var latitude = parseFloat(data.lat);
                    var longitude = parseFloat(data.lng);
                    var lt = [latitude,latitude+0.010,latitude+0.050];
                    var lg = [longitude,longitude+0.010,longitude+0.030];
                    for (var i = 0; i < 3; i++) {
                        AddCar(lt[i] ,lg[i],map);
                    }
                },
                error: function(data) {
                    $('#results').html(data.responseText);
                },

            });

        }
        /******************* find car ************************/
        function find_car(){
            document.getElementById("span_loading").style.visibility = "visible";
            setTimeout(function () {
                document.getElementById("span_loading").style.visibility = "hidden";
            }, 2000);
        }
    </script>
@endsection