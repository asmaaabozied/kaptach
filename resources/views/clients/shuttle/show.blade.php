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
                        {{$shuttle->type == 'arrival' ? $shuttle->airport->name : $shuttle->station->name}}
                    </strong><br>
                    {!! $shuttle->type == 'arrival' ? str_replace(',', '<br/>', $shuttle->airport->address) : str_replace(',', '<br/>',$shuttle->station->address)!!}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong> {{$shuttle->type == 'arrival' ?  $shuttle->station->name : $shuttle->airport->name}}</strong><br>
                    {!!$shuttle->type == 'arrival' ?  str_replace(',', '<br/>',$shuttle->station->address) : str_replace(',', '<br/>',$shuttle->airport->address)!!}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Shuttle #{{$shuttle->id}}</b><br>
                <b><a href="#">Shuttle Path</a></b><br>
                <br>
                <b>Type:</b> {{$shuttle->type}}<br>
                <b>Start Time :</b> {{$shuttle->shuttle_start_time}}<br>
                <b>End Time :</b>{{$shuttle->shuttle_end_time}}
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        @if($shuttle->shift)
            <div class="box box-widget widget-user">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-aqua-active">
                    <h3 class="widget-user-username">{{$shuttle->shift->employer->first_name}} {{$shuttle->shift->employer->last_name}}</h3>
                    <h5 class="widget-user-desc">{{$shuttle->shift->employer->phone}}</h5>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>
                    <i class="fa fa-star" aria-hidden="true"></i>

                </div>
                <div class="widget-user-image">
                    <img class="img-circle" src="{{asset('uploads/drivers/'.$shuttle->shift->employer->image)}}"
                         alt="User Avatar">
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
                <h3> {{  $corporate->name }}</h3>
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
                        @foreach($corporate->guests as $guest)
                            <tr>
                                <td>{{$guest->identity_number}}</td>
                                <td>{{$guest->first_name}}</td>
                                <td>{{$guest->last_name}}</td>
                                <td>{{$guest->gender}}</td>
                                <td>{{$guest->nationality}}</td>
                                <td>{{$guest->phone}}</td>
                                <td>{{$guest->room_number}}</td>
                            </tr>
                        @endforeach
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        <!-- /.row -->

    </section>
@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
@endsection