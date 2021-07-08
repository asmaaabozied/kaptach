@extends('layouts.master')
@section('title',__('pages.create').' '.__('pages.my_clients'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.create').' '.__('pages.my_clients')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::open('create', ['url'=>route('my-clients.store'),'id'=>'basic-form','files'=>true]) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'name', null, __('inputs.name'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::select('type',__('inputs.type'),[''=>__('pages.select').' '.__('inputs.type'),'hotel'=>'Hotel','tourism_company'=>'Tourism Company'],null,$errors,['required','class'=>'form-control','id'=>'type']) !!}
                       <div id="station_div">
                           {!! BootForm::select('station_id',__('inputs.stations'),$stations->prepend(__('inputs.select_station'),''),null,$errors,['class'=>'form-control']) !!}
                       </div>
                        {!! BootForm::file('logo', __('inputs.logo'), $errors, ['accept'=>'png,jpg,jpeg','required']) !!}
                        {!! BootForm::input('text', 'address', null, __('inputs.address'), $errors, ['required','readonly','placeholder'=>__('inputs.enter_location_on_map'),'class'=>'form-control','id'=>'address']) !!}
                    </div>
                    <div class="box-footer">
                        {!! BootForm::submit() !!}
                    </div>
                    <div id="divPosition" style="visibility: hidden; display:inline;">
                        {!! BootForm::input('text', 'lat', $value= "41.259899", __('inputs.lat'), $errors, ['required','class'=>'form-control','id'=>'lat']) !!}
                        {!! BootForm::input('text', 'lang', $value = "28.74273340000002", __('inputs.lang'), $errors, ['required','class'=>'form-control','id'=>'lang']) !!}
                    </div>
                {!! BootForm::close() !!}
                <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <!-- MAP -->
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.location')}}</h3>
                    </div>
                    <div class="box-body">
                        <input id="map_input"
                               class="controls form-control"
                               type="text"
                               placeholder="{{__('inputs.enter_location')}}" name="address"></div>
                    <div id="map" style="height: 250px; width: 100%;"></div>
                    <div class="form-group" id="infowindow-content">
                        <span id="place-name" class="title"></span><br>
                        <strong>Place ID:</strong> <span id="place-id"></span><br>
                        <span id="place-address"></span>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- /.row -->
    </section>

@endsection
@section('scripts')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCF-uTGI4iQOD6LIlR9yTECB-X0w5TO8no&libraries=places&callback=initMap"
            async defer>
    </script>
    <script src="{{asset('/assets/dist/js/gmap.js')}}"></script>

    <script>
        $(document).ready(function(){
            $('#station_div').hide();
            $('#type').change(function(){
                var value=$('#type').val();
                if(value=='hotel')
                    $('#station_div').show();
                else
                    $('#station_div').hide();
            })

        });
    </script>
@endsection
