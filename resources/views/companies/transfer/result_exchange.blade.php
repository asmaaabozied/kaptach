@extends('layouts.master')
@section('title',__('pages.create') .  __('pages.transfers'))
@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="box">
                <div class="box-body">
                    <!-- /.info-box -->
                    <div class="col-md-8">
                        <div class="info-box bg-aqua">
                            <span class="info-box-icon"><i class="fa fa-bus"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{__('pages.transfers')}}</span>
                                <span class="info-box-number">{{count($transfers)}}</span>
                                <?php $percentage = (count($transfers) / count($total)) * 100; ?>
                                <div class="progress">
                                    <div class="progress-bar"
                                         style="width: {{round($percentage,1)}}%"></div>
                                </div>
                                <span class="progress-description">
                                        {{round($percentage,1)}}%
                                       </span>
                            </div>

                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <!-- /.info-box -->
                </div>
                <a href="{{route('transfers.index')}}">Go back to {{__('pages.transfers')}}</a>
            </div>

        </div>
    </div>
@endsection