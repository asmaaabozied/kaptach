@extends('layouts.master')
@section('title',' Transportation Report')
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">

@endsection

@section('content')
    <div class="box col-xs-10">
        <div class="box-header with-border">
            <div class="col-xs-9"><h3>Transportation Report</h3></div>
            <div class="col-xs-3"><a href="{{route('annual_report')}}" class="btn btn-primary pull-right"
                                     style="margin-right: 5px;">
                    <i class="glyphicon glyphicon-folder-open"></i>Reports</a></div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-3 form-group">
                    <label>{{__('pages.month')}}/{{__('pages.year')}}:</label>
                    <input type="text" class="form-control from" style="width: 100px;" id="date"
                           value="{{$month.'/'.$year}}" readonly>
                </div>
                <div class="col-xs-4">
                    <label>{{__('inputs.hotel_tourism')}}:</label>
                    <input type="text" class="form-control" style="width: 100px;" value="{{$client->name}}"
                           readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 table-responsive" id="transportation">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background-color: darkgray">
                        <tr>
                            <th>Code</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Airport</th>
                            <th>Number of seats</th>
                            <th>Direction</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $sum=0;@endphp
                        @if(count($transfers) >0 )
                            @foreach($transfers as $transfer)
                                <tr>
                                    <td>#{{$transfer->id}}</td>
                                    <td>{{$transfer->transfer_start_time}}</td>
                                    <td>Transfer</td>
                                    <td>{{$transfer->airport->name}}</td>
                                    <td>{{$transfer->number_seats}}</td>
                                    <td>{{$transfer->type}}</td>
                                    <td>{{$transfer->price}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No transportation found</td>
                            </tr>
                        @endif
                        </tbody>
                        <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="background-color: lightskyblue">
                            Total: {{(count($transfers)>0)? $transfers->sum('price'): 0}}</th>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row no-print">
                <div class="col-xs-12">
                    <button onclick="window.print()" target="_blank" class="btn btn-default"><i
                                class="fa fa-print"></i> {{__('buttons.print')}}</button>
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                        <i class="fa fa-download"></i> {{__('buttons.generate_dpf')}}
                    </button>
                </div>
            </div>
            <div class="modal fade" id="transfersModal" tabindex="-1" role="dialog"
                 aria-labelledby="transfersModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">Transportation Details</div>
                        <div class="modal-body">
                            <div><label><strong>Date: </strong>01/01/2019</label></div>
                            <div><label><strong>Type: </strong>Transfer</label></div>
                            <div><label><strong>Direction: </strong>Arrival</label></div>
                            <div><label><strong>Airport: </strong>Sabiha</label></div>
                            <div><label><strong>Customers No: </strong>2</label></div>
                            <div><label><strong>Customer Names: </strong>Ahmad Mahmoud</label></div>
                            <div><label><strong>Idenity No: </strong>123456</label></div>
                            <div><label><strong>Room/Flight No: </strong>08</label></div>
                            <div><label><strong>Car Model: </strong>Vito 3x4</label></div>
                            <div><label><strong>Notes</strong>-</label></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <!-- bootstrap datepicker -->
            <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
            <script>
                $('#transportation table tr').click(function () {
                    $('#transfersModal').modal();
                });
                var startDate = new Date();
                var fechaFin = new Date();
                var FromEndDate = new Date();
                var ToEndDate = new Date();

                $('.from').datepicker({
                    autoclose: true,
                    minViewMode: 1,
                    format: 'mm/yyyy'
                }).on('changeDate', function (selected) {
                    startDate = new Date(selected.date.valueOf());
                    startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                    $('.to').datepicker('setStartDate', startDate);
                });
            </script>
@endsection       