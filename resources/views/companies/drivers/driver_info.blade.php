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
                        <h4 class="modal-title">{{__('pages.transfer_company').' '.__('pages.info')}} </h4>
                    </div>
                    <div class="modal-body">
                        <p>{{__('pages.name')}}: {{$company->name}}</p>
                        <p>{{__('pages.type')}}: Transfer Company</p>
                        <address>
                            @foreach($company->admins as  $admin)
                                {{__('pages.username')}}: {{$admin->username}}<br>
                                {{__('inputs.password')}}: 123456<br>
                                {{__('pages.email')}}: {{$admin->email}}
                            @endforeach
                        </address>
                    </div>
                    <div class="modal-footer">
                        <a href="{{route('companies.index')}}" class="btn btn-primary">Ok</a>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>

@endsection