<div class="modal fade" id="airportsModal" tabindex="-1" role="dialog"
     aria-labelledby="airportsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body">
                @foreach ($airports as $airport)
                    <div class="hvrbox" onclick="carmodel_selected({{$airport->id}})">
                        <img src="{{$airport->image['thumb']}}" width="200" height="200"
                             class="hvrbox-layer_bottom" alt="{{$airport->name}}">
                        <div class="hvrbox-layer_top">
                            <div class="hvrbox-text">{{$airport->name}}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>