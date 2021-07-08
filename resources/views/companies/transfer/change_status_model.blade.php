<div class="modal fade" id="changeStatusModal" role="dialog">
    {!! BootForm::model($transfer, 'change', ['method'=>'put', 'route'=>['transfers.end',$transfer->id],'id'=>'basic-form','class'=>'form-horizontal']) !!}

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{__('pages.transfers')}}</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="lat" name="lat" value="" required>
                <input type="hidden" id="lang" name="lang" value="" required>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="use_default_end_point"> Use default end-point
                    </label>
                </div>
                <div class="row">

                    <div id="map" style="width: 100%; height: 500px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                {!! BootForm::submit() !!}
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i> {{__('buttons.close')}}
                </button>
            </div>
        </div>
    </div>
    {!! BootForm::close() !!}
</div>
<script>
    var map, marker;

    function initMap() {
        var lat = 3.1412;
        var long = 101.68653;
        var myCenter = new google.maps.LatLng(lat, long);
        var mapCanvas = document.getElementById("map");

        var mapOptions = {
            center: myCenter,
            zoom: 15,
            treetViewControl: false,
            mapTypeControl: false
        };

        map = new google.maps.Map(mapCanvas, mapOptions);
        marker = new google.maps.Marker(
            {
                position: myCenter,
                draggable: true
            }
        );
        marker.setMap(map);

        // Zoom to 9 when clicking on marker
        google.maps.event.addListener(marker, 'click', function () {
            map.setZoom(9);
            map.setCenter(marker.getPosition());
            console.log(marker.getPosition());
        });
        // click on map and set you marker to that position
        google.maps.event.addListener(map, 'click', function (event) {
            marker.setPosition(event.latLng);
            $('#lat').val(marker.position.lat())
            $('#lang').val(marker.position.lng())
        });
        // when dragend, show new lat and lng in console
        google.maps.event.addListener(marker, 'dragend', function () {
            $('#lat').val(marker.position.lat())
            $('#lang').val(marker.position.lng())
        })

        //sets variable for lat and long
        $('#lat').val(lat);
        $('#lang').val(long);
    }

    function newLocation(newLat, newLng) {
        map.setCenter({
            lat: newLat,
            lng: newLng
        });
    }


</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFkSLH-JOeEK-00L0sE8-usUl7yH_ZK4Y&callback=initMap"
        async defer>
</script>