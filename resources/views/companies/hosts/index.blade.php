@extends('layouts.master')
@section('title',__('pages.hosts'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.hosts')}}</h3>
                    <a id="addToTable" class="btn btn-primary pull-right" href="{{route('hosts.create')}}">
                        <i class="fa fa-fw fa-plus" aria-hidden="true"></i> {{__('buttons.add_row')}}
                    </a>
                    <a href="#" class="btn btn-default" data-toggle="modal" data-target="#searchModal"> {{__('buttons.search')}}</a>

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.username')}}</th>
                            <th>{{__('pages.first_name')}}</th>
                            <th>{{__('pages.last_name')}}</th>
                            <th>{{__('pages.phone')}}</th>
                            <th>{{__('pages.email')}}</th>
                            <th>{{__('pages.airports')}}</th>
                            <th>{{__('pages.status')}}</th>
                            <th>{{__('pages.profile_pic')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.username')}}</th>
                            <th>{{__('pages.first_name')}}</th>
                            <th>{{__('pages.last_name')}}</th>
                            <th>{{__('pages.phone')}}</th>
                            <th>{{__('pages.email')}}</th>
                            <th>{{__('pages.airports')}}</th>
                            <th>{{__('pages.status')}}</th>
                            <th>{{__('pages.profile_pic')}}</th>
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
    @include('companies.hosts.search-panel')
@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            var table =  $('#example1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('hosts.index') }}",
                    type: 'GET',
                    data: function (d) {
                        d.phone = $('#phone').val();
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'employer.username', name: 'employer.username'},
                    {data: 'employer.first_name', name: 'employer.first_name'},
                    {data: 'employer.last_name', name: 'employer.last_name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'employer.email', name: 'employer.email'},
                    {data: 'airport', name: 'airport.name'},
                    {data: 'status', name: 'status'},
                    {data: 'profile_pic', name: 'profile_pic'},
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