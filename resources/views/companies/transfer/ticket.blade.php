@extends('layouts.master')
@section('title','Reservation')
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
    <style>
        .top_p {

            float: left;
            padding: 10px;
        }

        .top_p p {
            width: 100%;
            float: left;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 13px;
            font-weight: normal;
            color: #33c151;
        }

        .top_p h1 {
            width: 100%;
            float: left;
            padding: 10px 0 5px;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 15px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }

        .top_l {

            float: left;
            padding: 10px;
        }

        .top_l p {
            width: 100%;
            float: left;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 13px;
            font-weight: normal;
            color: #777;
        }

        .top_l h1 {
            width: 100%;
            float: left;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 15px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }

        .box-bord {
            width: 100%;
            margin: 30px 0 0;
            padding: 15px 15px 0;
            border: 1px solid #000;
            float: left;
        }

        .box-bord h1 {
            width: auto;
            float: left;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 19px;
            font-weight: bold;
            margin: -27px 0 0 25px;
            color: #000;
            background-color: #fff;
            padding: 0px 15px;
        }

        .invoice-data {
            width: 100%;
            float: left;
            padding: 20px;
            border: none;
        }

        .invoice-data p {
            width: 100%;
            float: left;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 13px;
            font-weight: normal;
            color: #777;
        }

        .invoice-data h2 {
            width: 100%;
            float: left;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 16px;
            font-weight: normal;
            color: #2785dd;
            margin: 0 0 10px;
        }

        .invoice-data h3 {
            width: 100%;
            float: left;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 13px;
            font-weight: normal;
            color: #000;
            margin: 0 0 10px;
            line-height: 22px;
        }
    </style>

@endsection
@section('content')
    <div class="box col-lg-12">
        <div class="col-lg-12">
            <div class="col-lg-7"></div>
            <div class="top_p col-lg-5">
                <h1> Transfer Ticket </h1>
                <p> Please be ready 15 minutes before! </p>
            </div>
        </div>
        <div class="box-header with-border">
            <div class="col-lg-12" style="padding:0;">
                <div class="top_l col-lg-5" style="padding:0;">
                    <div class="row">
                        <div class="col-lg-4"><h1> Transfer Code: </h1></div>
                        <div class="col-lg-8"><p>{{$transfer->id}}</p></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4"><h1> Date: </h1></div>
                        <div class="col-lg-8"><p>{{$transfer->transfer_start_time}}</p></div>
                    </div>
                </div>
                <div class="col-lg-5">
                    @if($transfer->cancelled == 1)
                        <div class="pull-right" style="    margin-top: -45px;
    transform: rotate(30deg);
    font-size: 2em;
    color: red;
    margin-right: -227px;">
                            {{__('pages.cancelled')}}
                        </div>
                    @endif
                </div>
            </div>

            <div class="box-bord">
                <div class="row">
                    <h1>General Details</h1>

                    <div class="col-sm-12 invoice-data">

                        <div class="row">
                            <div class="col-lg-3"><h2> FROM: </h2></div>
                            <div class="col-lg-9"><p>{{$transfer->airport->name}}</p></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><h2> To: </h2></div>
                            <div class="col-lg-9"><p>{{$transfer->transferable->name}}</p></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><h2> Transfer Type: </h2></div>
                            <div class="col-lg-9"><p>{{$transfer->type}}</p></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><h2> Start Time: </h2></div>
                            <div class="col-lg-9"><p>{{$transfer->transfer_start_time}}</p></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><h2> End Time: </h2></div>
                            <div class="col-lg-9"><p>{{$transfer->transfer_end_time}}</p></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-3"><h2> Number of Adults: </h2></div>
                            <div class="col-lg-9"><p>{{count($transfer->guests)}}</p></div>
                        </div>
                        @if($transfer->shift)
                            <div class="row">
                                <div class="col-lg-3"><h2> Driver Name: </h2></div>
                                <div class="col-lg-9">
                                    <p>{{$transfer->shift->driver->first_name}} {{$transfer->shift->driver->last_name}}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>


            <div class="box-bord">
                <div class="row">
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
                </div>
            </div>
            @if($transfer->notes)
                <div class="box-bord">
                    <div class="row">
                        <h1>{{__('pages.notes')}} </h1>
                        <div class="col-sm-12 invoice-data">
                            <h3>{!! $transfer->notes !!}</h3>
                        </div>
                    </div>
                </div>
            @endif
            @if($transfer->cancelled == 1)
                <div class="box-bord">
                    <div class="row">
                        <h1>{{__('pages.cancelled')}} </h1>
                        <div class="col-sm-12 invoice-data">
                            <h3>  {{__('inputs.cancel_reason')}}:
                                {!! $transfer->cancel_reason !!}<br>
                                by : {{$transfer->cancellable->name }}
                                {{$transfer->cancellable->first_name.' ' .$transfer->cancellable->last_name}}<br>
                                {{$transfer->cancellable->type}}</h3>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row no-print" style="margin:20px 0 0; float:left;">
                <div class="col-xs-12">
                <!--  <a style="margin:0 20px 0 0;" href="{{url('corporate/annual_report_print')}}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>-->


                    {{--<button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">--}}
                    {{--<i class="fa fa-download"></i> Generate PDF--}}
                    </button>

                    <a href="{{route('transfers.downloadPDF',['id' => $transfer->id])}}"
                       class="btn btn-primary pull-right" style="margin-right: 5px;">

                        <i class="fa fa-download"></i> Generate PDF
                    </a>
                </div>
            </div>

        </div>
    </div>
    @endsection

    @section('scripts')
        <!-- DataTables -->
            <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
@endsection