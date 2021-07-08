@extends('layouts.master')
@section('title',__('pages.admins'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.admins')}}</h3>
                <!--                <a id="addToTable" class="btn btn-primary pull-right" href="{{route('admins.create')}}">-->
                <!--                    <i class="fa fa-fw fa-plus" aria-hidden="true"></i> {{__('buttons.add_row')}}-->
                    <!--                </a>-->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.username')}}</th>
                            <th>{{__('pages.role')}}</th>
                            <th>{{__('pages.email')}}</th>
                            <th>{{__('pages.status')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($admins as $admin)
                            <td>{{$admin->id}}</td>
                            <td>{{$admin->username}}</td>
                            <td>{{$admin->role->name}}</td>
                            <td>{{$admin->email}}</td>
                            <td>{{$admin->status == 1 ? 'Active' : 'blocked'}}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default">Action</button>
                                    <button type="button" class="btn btn-default dropdown-toggle"
                                            data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        {!!
                                        '<li>'.BootForm::routeLink('admins.reset_password', $admin->id,['value' => __('buttons.reset_password')]).'</li>';
                                         !!}
                                    </ul>
                                </div>

                            </td>

                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.username')}}</th>
                            <th>{{__('pages.role')}}</th>
                            <th>{{__('pages.email')}}</th>
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
            $('#example1').DataTable();
        });
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection