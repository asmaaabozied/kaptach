
function initMap() {

    var latitude = parseFloat(document.getElementById('lat').value);
    var longitude = parseFloat(document.getElementById('lang').value);

    var LatLng = {lat: latitude, lng: longitude};
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: latitude, lng: longitude},
        zoom: 13
    });

    document.getElementById('map_input').value= document.getElementById('address').value;

    var marker = new google.maps.Marker({
        draggable: true,
        position: LatLng,
        map: map,
        title: 'Location'
    });

    marker.addListener('drag', handleEvent);
    marker.addListener('dragend', handleEvent);

    function handleEvent(event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        document.getElementById('lat').value = lat;
        document.getElementById('lang').value = lng;
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            "latLng":event.latLng
        }, function (results, status) {
            console.log(results, status);
            if (status == google.maps.GeocoderStatus.OK) {
                console.log(results);
                var lat = results[0].geometry.location.lat(),
                    lng = results[0].geometry.location.lng(),
                    placeName = results[0].address_components[0].long_name,
                    latlng = new google.maps.LatLng(lat, lng);

                document.getElementById('address').value = results[0].formatted_address;
                document.getElementById('map_input').value = results[0].formatted_address;
            }
        });


    }

    var input = document.getElementById('map_input');

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    // Specify just the place data fields that you need.
    autocomplete.setFields(['place_id', 'geometry', 'name']);

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var infowindow = new google.maps.InfoWindow();
    var infowindowContent = document.getElementById('infowindow-content');
    infowindow.setContent(infowindowContent);

    var marker = new google.maps.Marker({map: map,  draggable: true });

    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();

        var place = autocomplete.getPlace();

        if (!place.geometry) {
            return;
        }

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        $('#lat').val(place.geometry['location'].lat());
        $('#lng').val(place.geometry['location'].lng());
        $('#address').val(document.getElementById('map_input').value);

        infowindowContent.children['place-name'].textContent = place.name;
        infowindowContent.children['place-id'].textContent = place.place_id;
        infowindowContent.children['place-address'].textContent =
            place.formatted_address;
        infowindow.open(map, marker);

        // Add dragging event listeners.
        google.maps.event.addListener(marker, 'dragstart', function () {
            updateMarkerAddress('Dragging...');
        });

        google.maps.event.addListener(marker, 'drag', function () {
            updateMarkerStatus('Dragging...');
            updateMarkerPosition(marker.getPosition());
        });

        google.maps.event.addListener(marker, 'dragend', function () {
            updateMarkerStatus('Drag ended');
            geocodePosition(marker.getPosition());
        });
    });

}


