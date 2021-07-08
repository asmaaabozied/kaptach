@extends('layouts.master')
@section('title',__('pages.airports'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.airports')}}</h3>
                    <a id="addToTable" class="btn btn-primary pull-right" href="{{route('airports.create')}}">
                        <i class="fa fa-fw fa-plus" aria-hidden="true"></i> {{__('buttons.add_row')}}
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.name')}}</th>
                            <th>{{__('pages.arrival').' '.__('pages.image')}}</th>
                            <th>{{__('pages.departure').' '.__('pages.image')}}</th>
                            <th>{{__('pages.station')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.name')}}</th>
                            <th>{{__('pages.arrival').' '.__('pages.image')}}</th>
                            <th>{{__('pages.departure').' '.__('pages.image')}}</th>
                            <th>{{__('pages.station')}}</th>
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
                ajax: "{{ route('airports.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},  
                    {data: 'arrival_image', name: 'arrival_image'},
                    {data: 'departure_image', name: 'departure_image'},
                    {data: 'station.name', name: 'station'},
                    {data: 'action', name: 'action'}

                ]
            });
        });
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection