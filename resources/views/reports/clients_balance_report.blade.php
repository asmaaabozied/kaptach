@extends('layouts.master')
@section('title','Schedule')
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/iCheck/all.css')}}">
    <style>

    </style>
@endsection

@section('content')
    <div class="box col-lg-10">
        <div class="box-header with-border">
            <div class="col-lg-9"><h3>Clients Balance Report</h3></div>
            <div class="col-lg-3"><a href="{{route('annual_report')}}" class="btn btn-primary pull-right"
                                     style="margin-right: 5px;"><i
                            class="glyphicon glyphicon-folder-open"></i>Reports</a></div>
        </div>
        <div class="box-body">
            <div class="row col-md-offset-3">

                <div class="col-lg-2 form-group">
                    <label>{{__('pages.month')}}/{{__('pages.year')}}:</label>
                    <input type="text" class="form-control from" id="date" style="width: 100px;"
                           value="{{ now()->month}}/{{ now()->year}}">
                </div>
                @if(isset($hotels))
                    <div class="col-lg-4">
                        <label>{{__('pages.hotel_tourism_list')}}:</label>
                        <select class="form-control" style="width: 200px;" id="client">
                            <option value="all">All</option>
                            @foreach ($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}} </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-lg-2" style="margin-top: 25px">
                    <a class="btn btn-primary pull-right" id="search">{{__('buttons.search')}}</a>
                </div>
                <div class="col-lg-2 form-group">
                    <label>Zero Balance:</label>
                    <label>
                        <input type="checkbox" class="minimal icheckbox_minimal-blue" id="zero_check" checked>
                    </label>
                </div>

            </div>
            <div id="load_data"></div>
            <div class="row no-print">
                <div class="col-xs-12">
                    <button onclick="window.print()" target="_blank" class="btn btn-default"><i
                                class="fa fa-print"></i> {{__('buttons.print')}}</button>
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                        <i class="fa fa-download"></i> {{__('buttons.generate_dpf')}}
                    </button>
                </div>
            </div>
            <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">Indebtedness Details</div>
                        <div class="modal-body">
                            <table class="table">
                                <tr>
                                    <td><strong>transfers</strong></td>
                                    <td>250</td>
                                </tr>
                                <tr>
                                    <td><strong>Shuttles</strong></td>
                                    <td>150</td>
                                </tr>
                                <tr>
                                    <td><strong>Payments</strong></td>
                                    <td>100</td>
                                </tr>
                                <tr style="background-color: aquamarine">
                                    <td><strong>Balance</strong></td>
                                    <td>300</td>
                                </tr>
                            </table>
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
                $('input[type="checkbox"].minimal').click(function () {
                    if ($(this).prop("checked") == true) {
                        $('#main  table .IndebtednessCell').each(function () {
                            $(this).closest("tr").show();
                        });
                    }
                    else {
                        $('#main  table .IndebtednessCell').each(function () {
                            if ($(this).html() == 0) $(this).closest("tr").hide();
                        });
                    }
                });
                $('#main table tr').click(function () {
                    $('#detailsModal').modal();
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

                $(document).ready(function () {
                    search();
                });
                $('#search').click(function () {
                    search();
                });

                function search() {
                    $.ajax({
                        type: 'POST',
                        url: '{!! route('client_report.load_client_balance') !!}',
                        headers: {
                            "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
                        },
                        data: {
                            item: $('#client').val(),
                            date: $('#date').val(),
                        },
                        success: function (data) {
                            $('#load_data').html(data);
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });

                }
            </script>
@endsection       