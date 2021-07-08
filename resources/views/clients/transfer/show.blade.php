@extends('layouts.master')
@section('title','Reservation')
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection
@section('content')
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <!-- /.col -->
        </div>
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
                <b>Transfer is {{$transfer->request_status ==0 ? 'pending' : 'approved'}}</b><br>
                <b><a href="#">Transfer Path</a></b><br>
                <br>
                <b>Type:</b> {{$transfer->type}}<br>
                <b>Start Time :</b> {{$transfer->transfer_start_time}}<br>
                <b>End Time :</b>{{$transfer->transfer_end_time}}
            </div>
            <!-- /.col -->
            @if($transfer->cancelled == 1)
                <div class="pull-right" style="margin-top: -119px;
    transform: rotate(30deg);
    font-size: 2em;
    color: red;">
                    {{__('pages.cancelled')}}
                </div>
            @endif
        </div>
        <!-- /.row -->
        @if($transfer->shift)
            <div class="box box-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-aqua-active">
                    <h3 class="widget-user-username">{{$transfer->shift->driver->first_name}} {{$transfer->shift->driver->last_name}}</h3>
                    <h5 class="widget-user-desc">{{$transfer->shift->driver->phone}}</h5>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>

                </div>
                <div class="widget-user-image">
                    <img class="img-circle" src="{{asset('uploads/drivers/'.$transfer->shift->driver->image)}}" alt="User Avatar">
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
    @endif
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
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
@endsection