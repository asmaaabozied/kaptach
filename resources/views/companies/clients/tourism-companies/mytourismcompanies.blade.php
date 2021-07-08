@extends('layouts.master')
@section('title',__('pages.my_tourism_company'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.my_tourism_company')}}</h3>
                        {{--<a id="addToTable" class="btn btn-primary pull-right" href="{{route('companies.create')}}">--}}
                            {{--<i class="fa fa-fw fa-plus" aria-hidden="true"></i> Add row--}}
                        {{--</a>--}}
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>{{__('pages.id')}}</th>
                                <th>{{__('pages.name')}}</th>
                                <th>{{__('pages.contact_phone')}}</th>
                                <th>{{__('pages.contact_email')}}</th>
                                <th>{{__('pages.logo')}}</th>
                                <th>{{__('pages.status')}}</th>
                                <th>{{__('pages.actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>{{__('pages.id')}}</th>
                                <th>{{__('pages.name')}}</th>
                                <th>{{__('pages.contact_phone')}}</th>
                                <th>{{__('pages.contact_email')}}</th>
                                <th>{{__('pages.logo')}}</th>
                                <th>{{__('pages.status')}}</th>
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

@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(function () {
            $('#example1').DataTable({
                processing: true,
                serverSide: true,
                ServerMethod: "GET",
                ajax: "{{ route('mytourismcompanies.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'contact_phone', name: 'contact_phone'},
                    {data: 'contact_email', name: 'contact_email'},
                    {data: 'logo', name: 'logo'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action'}

                ]
            });
        });
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection