@extends('layouts.master')
@section('title',__('pages.shuttles'))
@section('styles')
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
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
        <div class="col-md-8 col-md-offset-2">
            <div class="box">
                <div class="box-header with-border">
                    <div class="col-lg-9">{{$airport->name." / ". $type}}</div>
                </div>
                <div class="box-body">
                    <form>
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <a id="prev_day" name="prev_day" class="btn btn-block btn-default btn-lg"><i
                                                class="fa fa-arrow-left"></i>Bir Önceki Gün</a>
                                </div>
                                <div class="col-md-6 tarih">
                                    <div class="input-group date">
                                        <input type="text" name="date" id="datepicker"
                                               class="form-control datepicker required"
                                               value="{{$search_date}}" data-date-format="yyyy-mm-dd"
                                               data-date-viewmode="years">
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <a id="next_day" name="next_day" class="btn btn-block btn-default btn-lg">
                                        Bir Sonraki
                                        Gün
                                        <i class="fa fa-arrow-right"></i></a></div>
                            </div>
                        </div>
                    </form>
                    {{--<div class="row">--}}
                    <div class="box-body">
                        <table class="table table-bordered table-striped" id="list">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th style="text-align: center">{{__('pages.time')}}</th>
                                <th>{{__('pages.car_models')}}</th>
                                <th>{{__('pages.drivers')}}</th>
                                <th style="width: 80px">{{__('pages.seats')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{--</div>--}}

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script>
        $(document).ready(function () {

            $('#list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('clients',['id'=>$airport->id,'type'=>$type])}}",
                    type: 'GET',
                    data: function (d) {
                        d.search = $('#datepicker').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'time', name: 'time'},
                    {data: 'car_model', name: 'car_model'},
                    {data: 'driver', name: 'driver'},
                    {data: 'seats', name: 'seats'},
                ]
            });
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
        });

        function search() {
            $('#list').DataTable().draw(true);
        }
    </script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection