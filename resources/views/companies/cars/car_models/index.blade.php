@extends('layouts.master')
@section('title',__('pages.car_models'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.car_models')}}</h3>
                    @if(auth('admin')->user()->adminable->id == 1)
                        <a id="addToTable" class="btn btn-primary pull-right" href="{{route('carmodels.create')}}">
                            <i class="fa fa-fw fa-plus" aria-hidden="true"></i> {{__('buttons.add_row')}}
                        </a>
                    @endif
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.model_name')}}</th>
                            <th>{{__('pages.max_seats')}}</th>
                            <th>{{__('pages.max_bags')}}</th>
                            <th>{{__('pages.model_description')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.model_name')}}</th>
                            <th>{{__('pages.max_seats')}}</th>
                            <th>{{__('pages.max_bags')}}</th>
                            <th>{{__('pages.model_description')}}</th>
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
                ajax: "{{ route('carmodels.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'model_name', name: 'model_name'},
                    {data: 'max_bags', name: 'max_bags'},
                    {data: 'max_seats', name: 'max_seats'},
                    {data: 'model_description', name: 'model_description'},
                    {data: 'action', name: 'action'}

                ]
            });
        });
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection