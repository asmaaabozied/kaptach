@extends('layouts.master')

@section('title',__('pages.edit_airport'))
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.edit_airport')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($airport, 'edit', ['method'=>'put', 'route'=>['airports.update',$airport->id],'files'=>true],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        {!! BootForm::input('text', 'name', null, __('inputs.name'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::select('station_id',__('inputs.stations'),$stations->prepend(__('inputs.select_station'),''),null,$errors,['required','class'=>'form-control']) !!}
                        {!! BootForm::file('arrival_image', __('inputs.arrival').' '.__('inputs.image'), $errors, ['accept'=>'png,jpg,jpeg']) !!}
                        {!! BootForm::file('departure_image', __('inputs.departure').' '.__('inputs.image'), $errors, ['accept'=>'png,jpg,jpeg']) !!}
                        {!! BootForm::input('text','address' , null, __('inputs.address'), $errors, ['required','readonly','placeholder'=>__('inputs.enter_location_on_map'),'class'=>'form-control','id'=>'address']) !!}
                    </div>
                    <div class="box-footer">
                        {!! BootForm::submit() !!}
                    </div>
                    <div id="divPosition" style="visibility: hidden; display:inline;">
                        {!! BootForm::input('text', 'lat', $airport->lat, __('inputs.lat'), $errors, ['required','class'=>'form-control','id'=>'lat']) !!}
                        {!! BootForm::input('text', 'lng', $airport->lang,__('inputs.lang'), $errors, ['required','class'=>'form-control','id'=>'lang']) !!}
                    </div>
                {!! BootForm::close() !!}
                <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.location')}}</h3>
                    </div>
                    <div class="box-body">
                        <div style="display: none">
                            <input id="map_input"
                                   class="controls form-control"
                                   type="text"
                                   placeholder="{{__('inputs.enter_location')}}">
                        </div>
                        <div id="map" style="height: 250px; width: 100%;"></div>
                        <div id="infowindow-content">
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
    <script src="{{asset('/assets/dist/js/gmap.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCF-uTGI4iQOD6LIlR9yTECB-X0w5TO8no&libraries=places&callback=initMap"
            async defer>
    </script>
@endsection