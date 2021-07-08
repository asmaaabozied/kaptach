@extends('layouts.master')
@section('title',__('pages.transfers'))
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dist/css/hvrbox.css')}}">
    <style>
        .tarih {
            padding: 7px;
            background-color: #c1c1c1;
            border-radius: 5px;
        }

        .bos {
            border-radius: 5px;
            background: #fea223;
            color: #fff;
            font-size: 10px;
            padding: 0 5px;
            position: absolute;
            right: 15px;
            top: -5px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.transfers')}}</h3>
                    <div class="pull-right">
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#searchModal"><i
                                    class="fa fa-search"></i> {{__('buttons.search')}}</a>
                        <div class="btn btn-primary" id="btn_transfer"><i class="fa fa-fw fa-plus"
                                                                                     aria-hidden="true"></i>{{__('buttons.add_row')}}
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->

                <div class="row col-md-offset-2" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <div class="col-md-3">
                            <a id="prev_day" name="prev_day" class="btn btn-block btn-default btn-lg">
                                <i class="fa fa-arrow-left"></i>Bir Önceki Gün
                            </a>
                        </div>
                        <div class="col-md-3 tarih">
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right datepicker" id="datepicker"
                                       name="datepicker" data-date-format="yyyy-mm-dd" value="{{$search_date}}"
                                       required>
                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <a id="next_day" name="next_day" class="btn btn-block btn-default btn-lg">
                                Bir Sonraki Gün <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                {{--<button class="btn btn-default pull-right" style="margin-right: 9px;"--}}
                        {{--onclick="document.getElementById('datepicker').value='';search();">All--}}
                {{--</button>--}}

                <div class="box-body">
                    <table id="list" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.type')}}</th>
                            <th>{{__('pages.time')}}</th>
                            <th>{{__('pages.airports')}}</th>
                            <th>{{__('pages.drivers')}}</th>
                            <th>{{__('pages.car_models')}}</th>
                            <th>{{__('pages.price')}}</th>
                            <th>{{__('pages.status')}}</th>
                            <th>{{__('pages.by_admin')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.type')}}</th>
                            <th>{{__('pages.time')}}</th>
                            <th>{{__('pages.airports')}}</th>
                            <th>{{__('pages.drivers')}}</th>
                            <th>{{__('pages.car_models')}}</th>
                            <th>{{__('pages.price')}}</th>
                            <th>{{__('pages.status')}}</th>
                            <th>{{__('pages.by_admin')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- Transfers Modal -->
        @include('modals.transfermodel')
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
@section('modals')
    <!-- Search Modal -->
    @include('clients.transfer.search-panel')
@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('clients.transfers.index')}}",
                    type: 'GET',
                    data: function (d) {
                        d.selected_date = $('#datepicker').val();
                        d.request_status = $('#request_status').val();
                        d.from = $('#from').val();
                        d.to = $('#to').val();
                    }
                },"fnDrawCallback": function() {
                    var api = this.api()
                    var json = api.ajax.json();
                    $(api.column(6).footer()).html(json.sum_price);
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'type', name: 'type'},
                    {data: 'transfer_start_time', name: 'transfer_start_time'},
                    {data: 'airport', name: 'airport.name'},
                    {data: 'driver', name: 'driver'},
                    {data: 'car_model', name: 'car_model.model_name'},
                    {data: 'price', name: 'price'},
                    {data: 'request_status', name: 'request_status'},
                    {data: 'admin', name: 'admin', searchable: false},
                    {data: 'action', name: 'action', searchable: false}
                ]
            });
            //Date picker
            $('.datepicker').datepicker({});
            $('#prev_day').on("click", function () {
                if ($('#datepicker').val() != '') {
                    var prevDate = new Date($('#datepicker').val());
                } else {
                    var prevDate = new Date();
                }
                prevDate.setDate(prevDate.getDate() - 1);
                var date = prevDate.getFullYear() + "-" + (prevDate.getMonth() + 1) + "-" + prevDate.getDate();
                $('#datepicker').val(date);
                search();
            });
            $('#next_day').on("click", function () {
                if ($('#datepicker').val() != '') {
                    var nextDate = new Date($('#datepicker').val());
                } else {
                    var nextDate = new Date();
                }
                nextDate.setDate(nextDate.getDate() + 1);
                var date = nextDate.getFullYear() + "-" + (nextDate.getMonth() + 1) + "-" + nextDate.getDate();
                $('#datepicker').val(date);
                search();
            });
            $('#datepicker').on("change", function () {
                search();
            });
            $('#btn_transfer').click(function () {
                $('#transfersModal').modal();
            });
        });

        function search() {
            $('#list').DataTable().draw(true);
        }
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
@endsection