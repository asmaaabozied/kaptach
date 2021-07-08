@extends('layouts.master')
@section('title',__('pages.info'))
@section('styles')
    <style>
        .example-modal .modal {
            position: relative;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            display: block;
            z-index: 1;
        }

        .example-modal .modal {
            background: transparent !important;
        }
    </style>
@endsection
@section('content')
    <div class="example-modal">
        <div class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">{{__('pages.client').' '.__('pages.info')}} </h4>
                    </div>
                    <div class="modal-body">
                        <p>{{__('pages.name')}}: {{$client->name}}</p>
                        <p>{{__('pages.type')}}: {{($client->type == 'hotel' ? 'Hotel' : 'Tourism Company')}}</p>
                        <address>
                            @foreach($client->admins as  $admin)
                                {{__('pages.username')}}: {{$admin->username}}<br>
                                {{__('pages.password')}}: 123456<br>
                                {{__('pages.email')}}: {{$admin->email}}
                            @endforeach
                        </address>
                    </div>
                    <div class="modal-footer">
                        <a href="{{route('my-clients.index')}}" class="btn btn-primary">Ok</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>

@endsection