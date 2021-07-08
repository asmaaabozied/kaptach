@extends('layouts.master')
@section('title','Reservation')
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dist/css/hvrbox.css')}}">
@endsection
@section('content')
    <div class="row">
        <!-- /.col -->
        @if($transfer->status == 'Start')
            <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#changeStatusModal"
               style="margin-left: 40px;"><i
                        class="fa fa-pause"></i> End</a>
        @elseif($transfer->status == 'Pending')
            {!! BootForm::routeLink('transfers.start',$transfer->id,['class'=>'btn btn-success','icon'=>'fa-play','label'=>'Start','style'=>'margin-left: 40px;']) !!}
        @else
            <div class="pad margin no-print">
                <div class="callout callout-success" style="margin-bottom: 0!important;">
                    <h4><i class="fa fa-check"></i> Transfer ended:</h4>
                    <a href="#">Transfer path</a>
                </div>
            </div>
            {!! BootForm::routeLink('transfers.start',$transfer->id,['class'=>'btn btn-success','icon'=>'fa-play','label'=>'Start','style'=>'margin-left: 40px;']) !!}
        @endif
        {!! BootForm::routeLink('transfers.reset',$transfer->id,['class'=>'btn btn-primary','icon'=>'fa fa-repeat','label'=>'Reset']) !!}
        <a href="#" class="sub-menu btn btn-default btn_transfer"
           id="{!! $transfer->id !!}">{!!  __('buttons.duplicate') . ' ' . __('pages.transfer')!!}</a>

    </div>
    <div class="pad margin no-print">
        {!! BootForm::routeLink('transfers.edit', $transfer->id, ['value' => __('buttons.edit'),'class'=>'btn btn-default']); !!}
        {!! BootForm::routeLink('transfers.approve', $transfer->id, ['value' => ($transfer->request_status == 1 ? __('buttons.pending') : __('buttons.approve')),'class'=>'btn btn-default']) !!}
        @if(in_array('Start',$statuses))
            {{''}}
        @else
            {!!  BootForm::routeLink('transfers.show_cancel', $transfer->id, ['value' => ($transfer->cancelled == 1 ? __('buttons.open') : __('buttons.cancel')), 'id' => 'btn_cancel','class'=>'btn btn-default']) !!}
            {!! BootForm::linkOfDelete('transfers.soft_delete', $transfer->id, $transfer->id, 'default', true, 'Delete') !!}
        @endif
        {!! BootForm::routeLink('transfers.showTicket', $transfer->id, ['value' => __('buttons.ticket'), 'target' => '_blank','class'=>'btn btn-default'], true) !!}
        <a href="#" class="sub-menu btn btn-default btn_transfer"
           id="{!! $transfer->id !!}">{!!  __('buttons.duplicate') . ' ' . __('pages.transfer')!!}</a>
    </div>

    <section class="invoice">

        <!-- title row -->
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>
                        {{$transfer->type == 'arrival' ? $transfer->airport->name : $transfer->transferable->name}}
                    </strong><br>
                    {!! $transfer->type == 'arrival' ? str_replace(',', '<br/>', $transfer->airport->address) : str_replace(',', '<br/>',$transfer->transferable->address)!!}
                </address>

            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong> {{$transfer->type == 'arrival' ?  $transfer->transferable->name : $transfer->airport->name}}</strong><br>
                    {!!$transfer->type == 'arrival' ?  str_replace(',', '<br/>',$transfer->transferable->address) : str_replace(',', '<br/>',$transfer->airport->address)!!}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Transfer #{{$transfer->id}}</b><br>
                <b><a href="#">Transfer Path</a></b><br>
                <br>
                <b>Type:</b> {{$transfer->type}}<br>
                <b>Start Time :</b> {{$transfer->transfer_start_time}}<br>
                <b>End Time :</b>{{$transfer->transfer_end_time}}
            </div>
            @if($transfer->cancelled == 1)
                <div class="pull-right" style="margin-top: -119px;
    transform: rotate(30deg);
    font-size: 2em;
    color: red;">
                    {{__('pages.cancelled')}}
                </div>
        @endif
        <!-- /.col -->
        </div>
        <!-- /.row -->
        @if($transfer->shift)
            <div class="col-md-4">
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-aqua-active">
                        <h3 class="widget-user-username">{{$transfer->driver->first_name}} {{$transfer->driver->last_name}}</h3>
                        <h5 class="widget-user-desc">{{$transfer->driver->phone}}</h5>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>

                    </div>
                    <div class="widget-user-image">
                        @if($transfer->driver->image)
                            <img class="img-circle"
                                 src="{{asset('uploads/drivers/'.$transfer->driver->image)}}"
                                 alt="User Avatar">
                        @else
                            <img class="img-circle" src="{{asset('assets/img/no-image-available.jpg')}}" style="    width: 90px;
    height: 92px;"
                                 alt="User Avatar">
                        @endif
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">300</h5>
                                    <span class="description-text">Transfer</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">40</h5>
                                    <span class="description-text">Shuttles</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">Tours</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
            </div>
    @endif
    {{--<div class="col-md-8">--}}
    {{--<div id="map" style="width: 100%; height: 500px;"></div>--}}
    {{--</div>--}}


    <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Passport Number / TC Identity Number *</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Gender</th>
                        <th>Nationality</th>
                        <th>Phone</th>
                        <th>Room Number</th>
                    </tr>
                    </thead>
                    @foreach($transfer->guests as $guest)
                        <tr>
                            <td>{{$guest->identity_number}}</td>
                            <td>{{$guest->first_name}}</td>
                            <td>{{$guest->last_name}}</td>
                            <td>{{$guest->gender}}</td>
                            <td>{{$guest->nationality}}</td>
                            <td>{{$guest->phone}}</td>
                            <td>{{$guest->pivot->room_number}}</td>
                        </tr>
                    @endforeach
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p class="lead">Payment Methods:</p>
                @if($transfer->paymentType->type_name == 'Cash')
                    {{'Cash'}}
                @else
                    <img src="{{asset('assets/dist/img/credit/visa.png')}}" alt="Visa">
                @endif
                @if($transfer->notes)
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {!! $transfer->notes !!}
                    </p>
                @endif
                @if($transfer->cancelled == 1)
                    <div class="pad">
                        <div class="callout callout-danger" style="margin-bottom: 0!important;">
                            <h4><i class="fa fa-ban"></i> {{__('pages.cancelled')}}</h4>
                            {{__('inputs.cancel_reason')}}:
                            {!! $transfer->cancel_reason !!}<br>
                            by : {{$transfer->cancellable->name }}
                            {{$transfer->cancellable->first_name.' ' .$transfer->cancellable->last_name}}<br>
                            {{$transfer->cancellable->type}}
                        </div>
                    </div>
                @endif
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <p class="lead">Amount Due {{ date('Y-m-d',strtotime($transfer->created_at))}}</p>

                <div class="table-responsive">
                    <table class="table">
                        {{--<tr>--}}
                        {{--<th style="width:50%">Subtotal:</th>--}}
                        {{--<td>$250.30</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<th>Tax (9.3%)</th>--}}
                        {{--<td>$10.34</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <th>Total:</th>
                            <td>{{$transfer->price}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
@endsection
@section('modals')
    <!-- Search Modal -->
    @include('companies.transfer.change_status_model')
@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
    <script>
        $('#btn_transfer').click(function () {
            $('#transfersModal').modal();
        });

        $(document).on("click", '.btn_transfer', function (event) {
            // AJAX request
            var transfer_id = $(this).attr('id');
            $.ajax({
                url: "transfers/" + transfer_id + "/viewModalForDuplicate",
                type: 'get',
                success: function (response) {
                    // Add response in Modal body
                    $('.modal-body').html(response);

                    // Display Modal
                    $('#duplicateModel').modal('show');
                }
            });
        });

        //        function initialize() {
        //            var map;
        //            var poly;
        //            var path;
        //
        //            // Put all locations into array
        //            var locations = [
        //                [3.14120000, 101.68653000],
        //                [3.13989733, 101.68153036]
        //            ];
        ////            for (i = 0; i < locations.length; i++) {
        ////                if(i==0)
        ////                {
        //            // Initialise the map
        //            var map_options = {
        //                center: new google.maps.LatLng(locations[0][0], locations[0][1]),
        //                //position: new google.maps.LatLng(locations[i][0], locations[i][1]),
        //                zoom: 16,
        //                mapTypeId: google.maps.MapTypeId.ROADMAP
        //            };
        //
        //            map = new google.maps.Map(document.getElementById('map'), map_options);
        //            poly = new google.maps.Polyline({
        //                strokeColor: '#000000',
        //                strokeOpacity: 1.0,
        //                strokeWeight: 3
        //            });
        //            poly.setMap(map);
        //            path = poly.getPath();
        ////                }
        //            var marker = new google.maps.Marker({
        //                position: new google.maps.LatLng(locations[0][0], locations[0][1]),
        //                //center:location,
        //                map: map,
        //                title: '#' + path.getLength(),
        ////                    icon:'jeep.png'
        //                //animation:google.maps.Animation.BOUNCE
        //            });
        //            var marker = new google.maps.Marker({
        //                position: new google.maps.LatLng(locations[1][0], locations[1][1]),
        //                //center:location,
        //                map: map,
        //                title: '#' + path.getLength(),
        ////                    icon:'jeep.png'
        //                //animation:google.maps.Animation.BOUNCE
        //            });
        //
        //
        //            path.push(new google.maps.LatLng(locations[0][0], locations[0][1]));
        //            path.push(new google.maps.LatLng(locations[1][0], locations[1][1]));
        ////            }
        //
        //        }
    </script>
    {{--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFkSLH-JOeEK-00L0sE8-usUl7yH_ZK4Y&callback=initialize"--}}
    {{--async defer>--}}
    {{--</script>--}}
@endsection