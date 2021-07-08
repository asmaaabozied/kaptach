@extends('layouts.master')
@section('title',__('pages.transfers'))
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('assets/plugins/iCheck/flat/blue.css')}}">

    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
@endsection

@section('content')
    <div class="row">

        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Transfers</h3>

                    <div class="box-tools pull-right">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search Transfer">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <a href="{{route('store.index')}}" class="btn btn-default btn-sm"><i
                                    class="fa fa-refresh"></i></a>

                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#searchModal"><i
                                    class="fa fa-search"></i> {{__('buttons.search')}}</a>
                        <a href="#" class="btn btn-default" data-toggle="modal"
                           data-target="#forSaleModal"> {{__('buttons.for_sale')}}</a>
                        <!-- /.pull-right -->
                    </div>
                    <div class="table-responsive mailbox-messages">
                        <table id="list" class="table table-hover table-striped">

                            <tbody>
                            <tr>
                                <td></td>
                                <td><b>{{__('pages.id')}}</b></td>
                                <td><b>{{__('pages.type')}}</b></td>
                                <td><b>{{__('pages.time')}}</b></td>
                                <td><b>{{__('pages.hotel_tourism_list')}}</b></td>
                                <td><b>{{__('pages.airports')}}</b></td>
                                <td></td>
                            </tr>
                            @if($transfers->isEmpty())
                                <tr>
                                    <td><p>There are no data</p></td>
                                </tr>
                            @else
                                @foreach($transfers as $transfer)
                                    <tr
                                            data-href="{{route('transfers.show',$transfer->id)}}">
                                        <td>
                                            @if($transfer->store)
                                                <div style="color: red; text-transform: uppercase;font-weight: bold;">
                                                    @if($transfer->store->type =='sale')
                                                        @if(!empty($transfer->store->buyable_id))
                                                            {{__('pages.sold')}}
                                                        @else
                                                            {{__('pages.offered_for_sale')}}
                                                        @endif
                                                    @else
                                                        {{__('pages.offered_for_exchange')}}
                                                    @endif
                                                </div>
                                            @else
                                                <input type="checkbox" name="record" value="{{$transfer->id}}">

                                            @endif

                                        </td>
                                        <td>{{$transfer->id}}</td>
                                        <td> {{$transfer->type}}</td>
                                        <td>{{$transfer->transfer_start_time}}</td>
                                        <td>{{$transfer->transferable['name']}}</td>
                                        <td>{{$transfer->airport->name}}</td>
                                        <td>
                                            @if($transfer->store && $transfer->store->buyable_id == NULL)
                                                <button id="undo" class="btn btn-default undo" onclick="undo(this,{{$transfer->id}})"
                                                        value="{{$transfer->store->id}}">
                                                    Undo
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>

                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.box-body -->

                <div class="box-footer no-padding">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <!-- /.btn-group -->
                        <a href="{{route('store.index')}}" class="btn btn-default btn-sm"><i
                                    class="fa fa-refresh"></i></a>
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#searchModal"><i
                                    class="fa fa-search"></i> {{__('buttons.search')}}</a>
                        <div class="pull-right">
                            @if(!$transfers->isEmpty())
                                Total Rows: {{$transfers->total()}} | Total
                                Pages: {{ceil($transfers->total()/$transfers->perPage())}} | Current
                                Page: {{$transfers->currentPage()}}
                            @endif
                            <div class="btn-group">
                                @if(!$transfers->isEmpty())
                                    {{ $transfers->render() }}
                                @endif
                            </div>
                            <!-- /.btn-group -->
                        </div>
                        <!-- /.pull-right -->
                    </div>
                </div>
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
@section('modals')
    <!-- Search Modal -->
    @include('companies.transfer.for-sale-panel')
    @include('companies.transfer.store-search-panel')
@endsection

@section('scripts')

    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <!-- iCheck -->
    <script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
    <script>

        $(function () {
            //Enable iCheck plugin for checkboxes
            //iCheck for checkbox and radio inputs
            $('table tbody input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });

            //Enable check and uncheck all functionality
            $(".checkbox-toggle").click(function () {
                var clicks = $(this).data('clicks');
                if (clicks) {
                    //Uncheck all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                    $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                } else {
                    //Check all checkboxes
                    $(".mailbox-messages input[type='checkbox']").iCheck("check");
                    $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                }
                $(this).data("clicks", !clicks);
            });
            //Date picker
            $('.datepicker').datepicker({
                autoclose: true
            });
            jQuery(document).ready(function ($) {
                $(".clickable-row").click(function () {
                    window.location = $(this).data("href");
                });
            });



        });
        function undo(elm, transfer_id) {
            var id = $(elm).val();

            $.ajax({
                type: 'POST',
                headers: {
                    "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
                },
                url: '{!! route('transfers.undoOfferForSale') !!}',
                data: {id: id},
                success: function (data) {
                    $(elm).closest("tr").find("td:first-child").text('');
                    $(elm).closest("tr").find("td:first-child").append('<input type="checkbox" name="record" value="' + transfer_id + '">');
                    $(elm).hide();
//
                },
                error: function (data) {
                },
            });
        }

    </script>
@endsection