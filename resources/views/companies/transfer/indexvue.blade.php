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
                        <div class="btn btn-primary" id="btn_transfer">
                            <i class="fa fa-fw fa-plus" aria-hidden="true"></i>{{__('buttons.add_row')}}
                        </div>
                        <a href="#" class="btn btn-default" data-toggle="modal" data-target="#forSaleModal"> {{__('buttons.for_sale')}}</a>
                    </div>
                </div>
                <!-- /.box-header -->



                {{--<button class="btn btn-default pull-right" style="margin-right: 9px;"--}}
                {{--onclick="document.getElementById('datepicker').value='';search();">All--}}
                {{--</button>--}}

                {{--<div class="box-body">--}}
                    <transfers-component></transfers-component>
                {{--</div>--}}
                <!-- /.box-body -->
            </div>
            <!-- Transfers Modal -->
            <div class="modal fade" id="transfersModal" tabindex="-1" role="dialog"
                 aria-labelledby="transfersModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"></div>
                        <div class="modal-body">
                            @foreach ($airports as $airport)
                                <a class="hvrbox"
                                   href="{{ route('transfers.add',['id' => $airport->id,'type'=>'arrival'])}}">
                                    <img src="{{$airport->arrival_image['thumb']}}" width="200" height="200"
                                         class="hvrbox-layer_bottom" alt="{{$airport->name}}">
                                    <div class="hvrbox-layer_top">
                                        <div class="hvrbox-text"> Arrival To {{$airport->name}}</div>
                                    </div>
                                </a>
                                <a class="hvrbox"
                                   href="{{route('transfers.add',['id' => $airport->id,'type'=>'departure'])}}">
                                    <img src="{{$airport->departure_image['thumb']}}" width="200" height="200"
                                         class="hvrbox-layer_bottom" alt="{{$airport->name}}">
                                    <div class="hvrbox-layer_top">
                                        <div class="hvrbox-text"> Departure From {{$airport->name}}</div>
                                    </div>
                                </a>
                                <div>
                                    <hr size="30">
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{__('buttons.close')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
@section('modals')
    <!-- Search Modal -->
    @include('companies.transfer.for-sale-panel')
    @include('companies.transfer.search-panel')
@endsection
@section('appjs')
    <script src="{{asset('js/app.js')}}"></script>
@endsection
@section('scripts')
    <!-- DataTables -->
    {{--<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>--}}
    {{--<script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>--}}
    <script>
        $(document).ready(function () {

            {{--var table = $('#list').DataTable({--}}
                {{--processing: true,--}}
                {{--serverSide: true,--}}
                {{--ajax: {--}}
                    {{--url: "{{url('transfers')}}",--}}
                    {{--type: 'GET',--}}
                    {{--data: function (d) {--}}
                        {{--d.selected_date = $('#datepicker').val();--}}
                        {{--d.request_status = $('#request_status').val();--}}
                        {{--d.from = $('#from').val();--}}
                        {{--d.to = $('#to').val();--}}
                        {{--d.hotel_id = $('#hotel_id').val();--}}
                    {{--}--}}
                {{--},--}}
                {{--columns: [--}}
                    {{--{data: 'checkbox', name: 'checkbox', searchable: false},--}}
                    {{--{data: 'id', name: 'id'},--}}
                    {{--{data: 'type', name: 'type'},--}}
                    {{--{data: 'transfer_start_time', name: 'transfer_start_time'},--}}
                    {{--{data: 'transferable.name', name: 'transferable.name', searchable: false},--}}
                    {{--{data: 'airport', name: 'airport.name'},--}}
                    {{--{data: 'driver', name: 'driver'},--}}
                    {{--{data: 'car_model', name: 'car_model.model_name'},--}}
                    {{--{data: 'price', name: 'price'},--}}
                    {{--{data: 'request_status', name: 'request_status'},--}}
                    {{--{data: 'admin', name: 'admin', searchable: false},--}}
                    {{--{data: 'updated_at', name: 'updated_at'},--}}
                    {{--{data: 'action', name: 'action', searchable: false}--}}
                {{--],--}}
                {{--createdRow: function (row, data, index) {--}}
                    {{--if (data['status'] == 'Start') {--}}
                        {{--$(row).addClass('success');--}}
                    {{--} else if (data['status'] == 'End') {--}}
                        {{--$(row).addClass('danger');--}}
                    {{--}--}}
                {{--}--}}
            {{--});--}}
            {{--$('#list').on('click', 'tr', function () {--}}
                {{--var id = table.row(this).data().tid;--}}

{{--//                var href = $(id).attr('href');--}}
                {{--window.open('/transfers/' + id + '/edit');--}}
            {{--});--}}
            {{--//Date picker--}}
            {{--$('.datepicker').datepicker({--}}
                {{--autoclose: true--}}
            {{--});--}}
            {{--$('#prev_day').on("click", function () {--}}
                {{--if ($('#datepicker').val() != '') {--}}
                    {{--var prevDate = new Date($('#datepicker').val());--}}
                {{--} else {--}}
                    {{--var prevDate = new Date();--}}
                {{--}--}}
                {{--prevDate.setDate(prevDate.getDate() - 1);--}}
                {{--var date = prevDate.getFullYear() + "-" + (prevDate.getMonth() + 1) + "-" + prevDate.getDate();--}}
                {{--$('#datepicker').val(date);--}}
                {{--search();--}}
            {{--});--}}
            {{--$('#next_day').on("click", function () {--}}
                {{--if ($('#datepicker').val() != '') {--}}
                    {{--var nextDate = new Date($('#datepicker').val());--}}
                {{--} else {--}}
                    {{--var nextDate = new Date();--}}
                {{--}--}}
                {{--nextDate.setDate(nextDate.getDate() + 1);--}}
                {{--var date = nextDate.getFullYear() + "-" + (nextDate.getMonth() + 1) + "-" + nextDate.getDate();--}}
                {{--$('#datepicker').val(date);--}}
                {{--search();--}}
            {{--});--}}
            {{--$('#datepicker').on("change", function () {--}}
                {{--search();--}}
            {{--});--}}
            $('#btn_transfer').click(function () {
                $('#transfersModal').modal();
            });
        });

        {{--function search() {--}}
            {{--$('#list').DataTable().draw(true);--}}
        {{--}--}}
    </script>
    {{--<!--bootbox -->--}}
    {{--<script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>--}}
    {{--<script src="{{ url('assets/dist/js/index.js') }}"></script>--}}
    {{--<!-- bootstrap datepicker -->--}}
    {{--<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>--}}
@endsection