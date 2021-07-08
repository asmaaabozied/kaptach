@extends('layouts.master')
@section('title','Languages')
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Languages</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>code</th>
                            <th>Name</th>
                            <th>action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>tur</td>
                            <td>Türk</td>
                            <td>{!!  BootForm::linkOfEdit('languages.edit', 1, 'Edit Türk') !!}</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>en</td>
                            <td>English</td>
                            <td>{!!  BootForm::linkOfEdit('languages.edit', 2, 'Edit English') !!}</td>
                        </tr>
                        </tbody>
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
            $('#example1').DataTable({});
        });
    </script>
    <!--bootbox -->
@endsection