@extends('layouts.master')
@section('title',__('pages.annual_report'))
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
    <style>
        @media print {
            #searchTable td:last-child {display:none}
            #searchTable th:last-child {display:none}
        }
    </style>
@endsection

@section('content')
    <div class="box col-xs-10">
        <div class="box-header with-border">
            <h3>{{__('pages.annual_report')}}</h3>
        </div>
        <div class="box-body">
            <div class="row col-md-offset-3">
                <div class="col-lg-2 form-group">
                    <label>{{__('pages.year')}}:</label>
                    <input class="date-own form-control" style="width: 100px;" id="year" name="year" type="text"
                           value="{{ now()->year}}">
                </div>
                @if(isset($clients))
                    <div class="col-lg-4">
                        <label>{{__('pages.hotel_tourism_list')}}:</label>
                        <select class="form-control" name="client" id="client" style="width: 200px;">
                            @foreach ($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}} </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-lg-4" style="margin-top: 25px;">
                    <a class="btn btn-primary" id="search">{{__('buttons.search')}}</a>
                </div>

            </div>

            <div id="load_data">

            </div>
            <div class="row no-print">
                <div class="col-xs-12">
                    <button onclick="window.print()"  class="btn btn-default"><i
                                class="fa fa-print"></i> {{__('buttons.print')}}</button>
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                        <i class="fa fa-download"></i> {{__('buttons.generate_dpf')}}
                    </button>
                </div>
            </div>
        </div>
    @endsection
    @section('scripts')
        <!-- bootstrap datepicker -->
            <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
            <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
            <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
            <script>
                $(document).ready(function () {
                    search();
                });
                $('.date-own').datepicker({
                    minViewMode: 2,
                    format: 'yyyy'
                });
                $('#search').click(function () {
                    search();
                });

                function search() {
                    $.ajax({
                        type: 'POST',
                        url: '{!! route('annual_report.load_data') !!}',
                        headers: {
                            "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
                        },
                        data: {
                            item: $('#client').val(),
                            year: $('#year').val(),
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