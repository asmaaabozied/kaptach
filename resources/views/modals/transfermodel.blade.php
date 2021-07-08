<div class="modal fade" id="transfersModal" tabindex="-1" role="dialog"
     aria-labelledby="transfersModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body">
                @foreach ($airports as $airport)
                    <a class="hvrbox"
                       href="{{ route('transfers.add',['id' => $airport->id,'type'=>'arrival'])}}">
                        <img src="{{$airport->arrival_image['thumb']}}" width="200" height="200"
                             class="hvrbox-layer_bottom" alt="{{$airport->name}}">
                        <div class="hvrbox-layer_top">
                            <div class="hvrbox-text"> Arrival To {{$airport->name}}</div>
                        </div>
                    </a>
                    <a class="hvrbox"
                       href="{{route('transfers.add',['id' => $airport->id,'type'=>'departure'])}}">
                        <img src="{{$airport->departure_image['thumb']}}" width="200" height="200"
                             class="hvrbox-layer_bottom" alt="{{$airport->name}}">
                        <div class="hvrbox-layer_top">
                            <div class="hvrbox-text"> Departure From {{$airport->name}}</div>
                        </div>
                    </a>
                    <div>
                        <hr size="30">
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{__('buttons.close')}}</button>
            </div>
        </div>
    </div>
</div>