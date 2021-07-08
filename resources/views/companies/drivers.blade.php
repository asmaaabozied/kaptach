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
                            {{--<th>{{__('pages.actions')}}</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($company->drivers as $driver)
                            <td>{{$driver->id}}</td>
                            <td>{{$driver->employer->username}}</td>
                            <td>{{$driver->employer->first_name}}</td>
                            <td>{{$driver->employer->last_name}}</td>
                            <td>{{$driver->employer->phone}}</td>
                            <td>{{$driver->employer->email}}</td>
                            <td>@php
                                    $employer_status = $driver->employer->status;
            if ($employer_status == 'approved')
                $employer_status = 'Pending';
            else
                $employer_status = 'Approved';
                                @endphp
                                {{$employer_status}}</td>
                            <td><img class='profile-user-img img-responsive img-circle'
                                     src='{{asset('uploads/drivers/' . $driver->employer->profile_pic)}}'
                                     alt='profile picture'></td>
                            {{--<td></td>--}}
                        @endforeach
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