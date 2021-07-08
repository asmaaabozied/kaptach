@extends('layouts.master')
@section('title',__('pages.drivers'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.drivers')}}</h3>
                    @if(auth('admin')->user()->adminable->type == 'commercial')
                    <a id="addToTable" class="btn btn-primary pull-right" href="{{route('drivers.create')}}">
                        <i class="fa fa-fw fa-plus" aria-hidden="true"></i> {{__('buttons.add_row')}}
                    </a>
                    @endif
                        <a class="btn btn-default pull-right" style="margin-right: 9px;"
                           href="{{route('shifts.index')}}">
                            <i class="fa fa-fw fa-calendar " aria-hidden="true"></i> {{__('pages.shifts')}}
                        </a>

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
                ajax: "{{ route('drivers.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'employer.username', name: 'employer.username'},
                    {data: 'employer.first_name', name: 'employer.first_name'},
                    {data: 'employer.last_name', name: 'employer.last_name'},
                    {data: 'phone', name: 'phone'},
                    {data: 'employer.email', name: 'employer.email'},
                    {data: 'status', name: 'status'},
                    {data: 'profile_pic', name: 'profile_pic'},
                    {data: 'action', name: 'action'}

                ]
            });
        });
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection