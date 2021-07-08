@extends('layouts.master')
@section('title',__('pages.push_notifications'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.push_notifications')}}</h3>
                    {{--<a id="addToTable" class="btn btn-primary pull-right" href="{{route('notifications.create')}}">--}}
                        {{--<i class="fa fa-fw fa-plus" aria-hidden="true"></i> {{__('buttons.add_row')}}--}}
                    {{--</a>--}}
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Body</th>
                            <th>Send At</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Title</th>
                            <th>Body</th>
                            <th>Send At</th>
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
    @include('companies.push-notifications.search-panel')
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
                ajax: "{{ route('notifications.index') }}",
                columns: [
                    {data: 'data.title', name: 'data.title'},
                    {data: 'data.message', name: 'data.message'},
                    {data: 'send_at', name: 'send_at'}

                ]
            });
        });
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection