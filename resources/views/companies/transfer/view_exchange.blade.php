@extends('layouts.master')
@section('title',__('pages.transfers'))
@section('content')
    <div class="row">

        <!-- /.col -->
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <ul class="nav nav-tabs pull-right" id="myTab" role="tablist">
                        <li class="active">
                            <a class="nav-link active" id="attributes-tab" data-toggle="tab" href="#attributes"
                               role="tab" aria-controls="attributes" aria-selected="true">Exchange Offer</a>
                        </li>
                        <li>
                            <a class="nav-link" id="transfer-tab" data-toggle="tab" href="#transfer" role="tab"
                               aria-controls="transfer" aria-selected="false">Transfer</a>
                        </li>
                    </ul>
                    <!-- /.box-tools -->
                </div>
                <div class="box-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane active" id="attributes" role="tabpanel" aria-labelledby="attributes-tab">
                            <?php  $count = 1;?>
                            @foreach($exchange->attributes as $attribute)
                                <span class="label label-primary pull-left">{{$count}}</span>
                                <dl class="dl-horizontal">
                                    @if($attribute->airport)
                                        <dt>Airport</dt>
                                        <dd>{{$attribute->airport->name}}</dd>
                                    @endif
                                    @if($attribute->datetime)
                                        <dt>Date</dt>
                                        <dd>{{$attribute->datetime}}</dd>
                                    @endif
                                    @if($attribute->type)
                                        <dt>Type</dt>
                                        <dd>{{$attribute->type}}</dd>
                                    @endif
                                    <?php $count++;?>
                                </dl>
                            @endforeach
                        </div>
                        <div class="tab-pane" id="transfer" role="tabpanel" aria-labelledby="transfer-tab">

                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td>Transfer</td>
                                    <td>#{{$exchange->transfer->id}}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{$exchange->transfer->type}}</td>
                                </tr>
                                <tr>
                                    <td>Airport</td>
                                    <td>{{$exchange->transfer->airport->name}}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>{{$exchange->transfer->transfer_start_time}}</td>
                                </tr>
                                <tr>
                                    <td>Car Model</td>
                                    <td>{{$exchange->transfer->car_model['ModelWithSeats']}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <!-- /.box-body -->

            </div>
            <!-- /. box -->
        </div>
        <div class="col-md-4">
            <div class="box box-body">
                <p>
                    @if(!$exchange->offers->isEmpty())
                        <?php $str = ''; $i = 1;?>
                        @foreach($exchange->offers as $offer)
                            <?php $str .= 'multiCollapse' . $offer->id;
                            $str .= ' ';
                            ?>
                            <button type="button" class="btn btn-primary" data-toggle="collapse"
                                    href="#multiCollapse{{$offer->id}}" role="button"
                                    aria-expanded="false" aria-controls="multiCollapse{{$offer->id}}"> Offer {{$i}}
                            </button>
                            <?php $i++;?>
                        @endforeach
                        @if($str!='')
                            <button class="btn btn-primary" type="button" data-toggle="collapse"
                                    data-target=".multi-collapse" aria-expanded="false"
                                    aria-controls="{{$str}}">All Offers
                            </button>
                        @endif
                    @else
                        No Offers found
                    @endif

                </p>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-8">
            <?php $i = 1 ?>
            @foreach($exchange->offers as $offer)
                <div class="col">
                    <div class="collapse multi-collapse" id="multiCollapse{{$offer->id}}">

                        <div class="box box-body"> Offer {{$i}}
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td>Transfer</td>
                                    <td>#{{$offer->transfer->id}}</td>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <td>{{$offer->transfer->type}}</td>
                                </tr>
                                <tr>
                                    <td>Airport</td>
                                    <td>{{$offer->transfer->airport->name}}</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>{{$offer->transfer->transfer_start_time}}</td>
                                </tr>
                                <tr>
                                    <td>Car Model</td>
                                    <td>{{$offer->transfer->car_model['ModelWithSeats']}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="box-footer">
                                <a href="{{route('offer.rejected',$offer->id)}}" class="btn btn-danger pull-left confirmed" data-dismiss="modal" data-confirm-message="Are you sure to reject this offer">Reject</a>
                                <a href="{{route('offer.accepted',$offer->id)}}" class="btn btn-primary confirmed" style="margin-left: 10px;" data-confirm-message="Are you sure to accept this offer">Accept</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++;?>
            @endforeach
        </div>
    </div>
    <!-- /.row -->
@endsection

@section('scripts')
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
 <script>
     $(document).ready(function(){
         $('.confirmed').on('click', function(event){
             event.preventDefault();
             href = $(this).attr('href');
             var btn = $(this);
             bootbox.confirm({
                 title: "Confirm",
                 message: btn.data('confirm-message'),
                 buttons: {
                     cancel: {
                         label: '<i class="fa fa-times"></i> Cancel'
                     },
                     confirm: {
                         label: '<i class="fa fa-check"></i> Confirm'
                     }
                 },
                 callback: function (result) {
                     if (result) {
                         //categories/{id}/delete
                         window.location = href;

                     }
                 }
             });
         });
     });
 </script>
@endsection