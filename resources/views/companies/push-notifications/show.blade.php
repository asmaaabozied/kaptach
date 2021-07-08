@extends('layouts.popup')

@section('content')
    @component('partials.content-header')
        @slot('title')
        Push Notifications
        @endslot
        
    @endcomponent

    @component('partials.content-body')
        @slot('body')
        
        <form class="form-horizontal">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="control-label col-sm-2" style="text-align: left">Created At:</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $notification->created_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="control-label col-sm-2" style="text-align: left">Send At:</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">@if($notification->send_at != Null) {{$notification->send_at}}@else  {{ date("Y-m-d H:i:00", strtotime($notification->created_at ))}} @endif</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label col-sm-2" style="text-align: left">Body (EN):</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $notification->body_en }}</p>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label col-sm-2" style="text-align: left">Body (AR):</label>
                        <div class="col-sm-10">
                            <p class="form-control-static">{{ $notification->body_ar }}</p>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="control-label col-sm-2" style="text-align: left">Criteria:</label>
                        <div class="col-sm-10">
                            <p class="form-control-static"><b>Corporates:</b> 
                                @foreach($notification->criteria['companies'] as $corporate)
                                <span class="label label-default">{{$corporate}}</span>
                                @endforeach
                            </p>
                            <p class="form-control-static"><b>Domains:</b>
                                    @foreach($notification->criteria['domains'] as $domain)
                                    <span class="label label-default">{{$domain}}</span>
                                    @endforeach
                            </p>
                            <p class="form-control-static"><b>Cities:</b>
                                    @foreach($notification->criteria['cities'] as $city)
                                    <span class="label label-default">{{$city}}</span>
                                    @endforeach
                            </p>
                            <p class="form-control-static"><b>Gender:</b>
                                    @foreach($notification->criteria['genders'] as $gender)
                                    <span class="label label-default">{{$gender}}</span>
                                    @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        
        </form>
        @endslot
    @endcomponent




    <!-- /.content -->
@endsection
