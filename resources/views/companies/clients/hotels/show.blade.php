@extends('layouts.master')
@section('title',__('pages.info'))
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle"
                         src="{{$client->logo['original']}}" alt="User profile picture">

                    <h3 class="profile-username text-center">{{$client->name}}</h3>
                    <h3 class="profile-username text-center">{{($client->type == 'hotel' ? 'Hotel' : 'Tourism Company')}}</h3>

                    <p class="text-muted text-center">{{$client->contact_phone}}</p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>{{__('pages.transfers')}}</b> <a class="pull-right">{{count($client->transfers)}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{__('pages.shuttles')}}</b> <a class="pull-right">{{count($client->shuttles)}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{__('pages.tours')}}</b> <a class="pull-right">0</a>
                        </li>
                    </ul>

                    <a href="{!! route('my-clients.add_payment',$client->id) !!}" class="btn btn-info btn-block"><b>Add
                            {{__('pages.payment')}}</b></a>
                    <a href="{!! route('my-clients.add_invoice',$client->id) !!}" class="btn btn-success btn-block"><b>Add
                            {{__('pages.invoices')}}</b></a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->

            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('pages.about')}}</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <strong><i class="fa fa-book margin-r-5"></i>{{__('pages.contact_email')}}</strong>

                    <p class="text-muted">
                        {{$client->contact_email}}
                    </p>

                    <hr>

                    <strong><i class="fa fa-book margin-r-5"></i>{{__('pages.contact_phone')}}</strong>

                    <p class="text-muted">
                        {{$client->contact_phone}}
                    </p>

                    <hr>

                    <strong><i class="fa fa-map-marker margin-r-5"></i>{{__('pages.location')}}</strong>

                    <p class="text-muted">{{$client->address}}</p>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#admins" data-toggle="tab">{{__('pages.admins')}}</a></li>
                    <li><a href="#timeline" data-toggle="tab"> {{__('pages.requested') .__('pages.transfers')}}</a></li>
                    <li><a href="#prices" data-toggle="tab">{{__('pages.price')}}</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="admins">
                        <!-- Post -->
                        @foreach($client->admins as $admin)
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm"
                                         src="{{asset('uploads/admins/'.$admin->image)}}"
                                         alt="user image">
                                    <span class="username">
                          <a href="#">{{$admin->username}}</a>
                          <a href="#" class="pull-right btn-box-tool"></a>
                        </span>
                                    <span class="description">{{date('M Y',strtotime($admin->created_at))}}</span>
                                </div>
                                <!-- /.user-block -->
                            </div>
                    @endforeach
                    <!-- /.post -->
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                        <!-- The timeline -->
                        <ul class="timeline timeline-inverse">
                        @php $old_date=''; @endphp
                        @foreach($requested_transfers as $requested_transfer)

                            @if($old_date != date('Y-m-d', strtotime($requested_transfer->transfer_start_time)))
                                @php
                                    echo $found=false;
                                 $old_date = date('Y-m-d', strtotime($requested_transfer->transfer_start_time));
                                @endphp
                            @else
                                @php $found=true;@endphp
                            @endif
                            <!-- timeline time label -->
                                @if(!$found)
                                    <li class="time-label">
                                    <span class="bg-red">
                                        {{date('d M. Y', strtotime($requested_transfer->transfer_start_time))}}
                                      </span>
                                    </li>
                            @endif
                            <!-- /.timeline-label -->
                                <!-- timeline item -->

                                <li>
                                    <i class="fa fa-car bg-blue"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i
                                                    class="fa fa-clock-o"></i> {{date('H:i', strtotime($requested_transfer->transfer_start_time))}}</span>

                                        <h3 class="timeline-header"><a
                                                    href="{!! route('transfers.show',$requested_transfer->id) !!}">#{{$requested_transfer->id}}</a>
                                        </h3>

                                        <div class="timeline-body">
                                            <ul>
                                                <li>{{$requested_transfer->car_model['ModelWithSeats']}}</li>
                                                <li>{{$requested_transfer->airport->name}}</li>
                                                <li>{{$requested_transfer->type}}</li>
                                            </ul>
                                        </div>
                                        <div class="timeline-footer">
                                            <a class="btn btn-primary btn-xs"
                                               href="{!! route('transfers.show',$requested_transfer->id) !!}">Read
                                                more</a>
                                        </div>
                                    </div>
                                </li>
                        @endforeach
                        <!-- END timeline item -->
                        </ul>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="prices">
                        <!-- The timeline -->
                        {!! BootForm::open('create', ['url'=>route('my-clients.store_price',[$client->id]),'id'=>'basic-form']) !!}

                        <ul class="timeline timeline-inverse">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">{{__('pages.shuttles'). __('pages.price')}}</h3>
                                </div>
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
                                            <select class="form-control" disabled=""
                                                    name="shuttle[{{$airport->id}}][id]">
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
                                                <input type="text" class="form-control"
                                                       placeholder="{{__('inputs.arrival'). __('inputs.price')}}"
                                                       name="shuttle[{{$airport->id}}][departure_price]" required
                                                       value="{{$shuttle_price->departure_price}}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control"
                                                       placeholder="{{__('inputs.departure'). __('inputs.price')}}"
                                                       name="shuttle[{{$airport->id}}][arrival_price]" required
                                                       value="{{$shuttle_price->arrival_price}}">
                                            </div>
                                            <br> <br>
                                        @else
                                            <div class="col-md-3">
                                                <input type="text" class="form-control"
                                                       placeholder="{{__('inputs.departure'). __('inputs.price')}} "
                                                       name="shuttle[{{$airport->id}}][departure_price]" required
                                                       value="0.00">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control"
                                                       placeholder="{{__('inputs.arrival'). __('inputs.price')}}"
                                                       required
                                                       name="shuttle[{{$airport->id}}][arrival_price]"
                                                       value="0.00">
                                            </div>
                                            <br> <br>
                                        @endif
                                    @endforeach
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- END timeline item -->
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">{{__('pages.transfers'). __('pages.price')}}</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    @foreach($transfer_prices as $transfer_price)
                                        @if(isset($transfer_price['car_model']))
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {{__('pages.airports')}} : <select class="form-control" disabled=""
                                                                                       name="transfer[{{$transfer_price['airport_id']}}][airport_id]">
                                                        <option value="{{$transfer_price['airport_id']}}">{{$transfer_price['airport_name']}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                {{__('pages.car_models')}}
                                            </div>
                                            <div class="col-md-3">
                                                {{__('pages.arrival')}}
                                            </div>
                                            <div class="col-md-3">
                                                {{__('pages.departure')}}
                                            </div>
                                            @foreach($transfer_price['car_model'] as $model)

                                                <div class="col-md-4">
                                                    <select class="form-control" disabled=""
                                                            name="transfer[{{$transfer_price['airport_id']}}][{{$model['id']}}]">
                                                        <option value="{{$model['id']}}">{{$model['car_model_name']}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control"
                                                           placeholder="{{__('inputs.arrival'). __('inputs.price')}}"
                                                           required
                                                           name="transfer[{{$transfer_price['airport_id']}}][{{$model['id']}}][arrival_price]"
                                                           value=" {{$model['arrival_price']}}">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="text" class="form-control"
                                                           placeholder="{{__('inputs.departure'). __('inputs.price')}}"
                                                           required
                                                           name="transfer[{{$transfer_price['airport_id']}}][{{$model['id']}}][departure_price]"
                                                           value="{{$model['departure_price']}}">
                                                </div>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </div>

                                <div class="box-footer">
                                    {!! BootForm::submit() !!}
                                </div>
                                <!-- /.box-body -->
                            </div>
                        </ul>
                        {!! BootForm::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
@endsection