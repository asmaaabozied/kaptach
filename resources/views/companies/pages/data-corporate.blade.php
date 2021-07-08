@extends('layouts.master')
@section('title',__('pages.data'))

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{__('pages.data')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-sm-6">
                            {{$row->name}} ({{$row->type}}) {{__('pages.was_add')}}
                            <address>
                                {{__('pages.username')}}: {{$admin->username}}<br>
                                {{__('pages.password')}}: 123456789 <br>
                                {{__('pages.email')}}: {{$admin->email}}
                            </address>
                        </div>
                    </div>
                    <div class="box-footer">
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