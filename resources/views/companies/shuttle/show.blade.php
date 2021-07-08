@extends('layouts.master')
@section('title',__('pages.reservation'))
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
                {{__('pages.from')}}
                <address>
                    <strong>
                        {{$shuttle->type == 'arrival' ? $shuttle->airport->name : $shuttle->station->name}}
                    </strong><br>
                    {!! $shuttle->type == 'arrival' ? str_replace(',', '<br/>', $shuttle->airport->address) : str_replace(',', '<br/>',$shuttle->station->address)!!}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                {{__('pages.to')}}
                <address>
                    <strong> {{$shuttle->type == 'arrival' ?  $shuttle->station->name : $shuttle->airport->name}}</strong><br>
                    {!!$shuttle->type == 'arrival' ?  str_replace(',', '<br/>',$shuttle->station->address) : str_replace(',', '<br/>',$shuttle->airport->address)!!}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>{{__('pages.shuttle')}} #{{$shuttle->id}}</b><br>
                <b><a href="#">{{__('pages.shuttle'). __('pages.path')}}</a></b><br>
                <br>
                <b>{{__('pages.type')}}:</b> {{$shuttle->type}}<br>
                <b>{{__('pages.start_time')}} :</b> {{$shuttle->shuttle_start_time}}<br>
                <b>{{__('pages.end_time')}} :</b>{{$shuttle->shuttle_end_time}}
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
                                <span class="description-text">{{__('pages.transfers')}}</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header">40</h5>
                                <span class="description-text">{{__('pages.shuttles')}}</span>
                            </div>
                            <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header">35</h5>
                                <span class="description-text">{{__('pages.tours')}}</span>
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
        @foreach($shuttle->corporates as $corporate)
            <div class="row">
                <h3> {{  $corporate->name }} price : {{$corporate->pivot->price}}</h3>
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.passport_number')}}*</th>
                            <th>{{__('pages.first_name')}}</th>
                            <th>{{__('pages.last_name')}}</th>
                            <th>{{__('pages.gender')}}</th>
                            <th>{{__('pages.nationality')}}</th>
                            <th>{{__('pages.phone')}}</th>
                            <th>{{__('pages.room_number')}}</th>
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
    @endforeach
    <!-- /.row -->

    </section>
@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
@endsection