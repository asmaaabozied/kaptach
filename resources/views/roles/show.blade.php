@extends('layouts.master')
@section('title','Role')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Role</h3>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Name:</strong>
                                {{ $role->name }}
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Permissions:</strong>
                                    @if(!empty($role->permissions))
                                        @foreach($role->permissions as $permission)
                                            <label class="label label-success" style="font-size: 100%;">{{ $permission->name }},</label>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>


                <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection