@extends('layouts.master')
@section('title',__('pages.invoices'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.invoices')}}</h3>
                    <div class="pull-right">
                        <a href="#" class="btn btn-info" data-toggle="modal" data-target="#searchModal"><i
                                    class="fa fa-search"></i> {{__('buttons.search')}}</a>
                        {{--<a id="addToTable" class="btn btn-primary" href="{{route('invoices.create')}}">--}}
                        {{--<i class="fa fa-fw fa-plus" aria-hidden="true"></i> Add row--}}
                        {{--</a>--}}
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.code')}}</th>
                            <th>{{__('pages.hotel_tourism_list')}}</th>
                            <th>{{__('pages.month')}}</th>
                            <th>{{__('pages.year')}}</th>
                            <th>{{__('pages.price')}}</th>
                            <th>{{__('pages.tax')}} %</th>
                            <th>{{__('pages.created_at')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{__('pages.code')}}</th>
                            <th>{{__('pages.hotel_tourism_list')}}</th>
                            <th>{{__('pages.month')}}</th>
                            <th>{{__('pages.year')}}</th>
                            <th>{{__('pages.price')}}</th>
                            <th>{{__('pages.tax')}} %</th>
                            <th>{{__('pages.created_at')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection
@section('modals')
    <!-- Search Modal -->
    @include('companies.invoices.search-panel')
@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(function () {
            $('#example1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{route('invoices.index')}}",
                    type: 'GET',
                    data: function (d) {
                        d.from = $('#from').val();
                        d.to = $('#to').val();
                        d.code = $('#code').val();
                        d.client_id = $('#client_id').val();
                    }
                },
                columns: [
                    {data: 'code', name: 'code'},
                    {data: 'client.name', name: 'client.name'},
                    {data: 'deducted_month', name: 'deducted_month'},
                    {data: 'deducted_year', name: 'deducted_year'},
                    {data: 'price', name: 'price'},
                    {data: 'tax', name: 'tax'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'}

                ]
            });
        });

        function search() {

            $('#example1').DataTable().draw(true);
        }
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection