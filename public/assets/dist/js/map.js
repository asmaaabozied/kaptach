function initMap() {
    var lat = parseFloat(document.getElementById('lat').innerText);
    var lang = parseFloat(document.getElementById('lang').innerText);
    const loc = {lat: lat, lng: lang};
    // load the map
    const map = new google.maps.Map(document.getElementById('map'), {
        center: loc,
        zoom: 8,

    });
    var marker = new google.maps.Marker({
        map: map,
        position: loc,
        title: 'mine'
    });
    marker.addListener('click', function () {
        infoWindow.setContent(infowincontent);
        infoWindow.open(map, marker);
    });
    var infoWindow = new google.maps.InfoWindow;
    //get all points for client and airports
    $.ajax({
        type: 'GET',
        url: "/gmap/locations",
        dataType: 'JSON',
        success: function (response) {
            var len = Object.keys(response).length;
            for (var i = 0; i < len; i++) {
                var id = response[i].id;
                var name = response[i].name;
                var address = response[i].address;
                var point = new google.maps.LatLng(response[i].lat, response[i].lang);
                var infowincontent = document.createElement('div');
                var strong = document.createElement('strong');
                strong.textContent = name
                infowincontent.appendChild(strong);
                infowincontent.appendChild(document.createElement('br'));
                var text = document.createElement('text');
                text.textContent = address
                infowincontent.appendChild(text);
                // var icon = customLabel[type] || {};
                var marker = new google.maps.Marker({
                    map: map,
                    position: point,
                    title: name
                    // label: icon.label
                });
                marker.addListener('click', function () {
                    infoWindow.setContent(infowincontent);
                    infoWindow.open(map, marker);
                });
            }
        },
        error: function (data) {
        },
    });
}


function AddCar(latitude, longitude, map) {
    var icon = { // car icon
        path: 'M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759   c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336   h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805',
        scale: 0.4,
        fillColor: "#427af4", //<-- Car Color, you can change it
        fillOpacity: 1,
        strokeWeight: 1,
        anchor: new google.maps.Point(0, 5),
        rotation: 180//data.val().angle //<-- Car angle
    };
    var LatLng = {lat: latitude, lng: longitude};
    var marker = new google.maps.Marker({
        position: LatLng,
        icon: icon,
        map: map,
        title: 'car model'
    });
}

function focusOnPosition(lat, lng) {
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,

    });
    var position = new google.maps.LatLng(lat, lng);
    map.setCenter(position);
    var marker = new google.maps.Marker({
        map: map,
        position: position,
        title: name
        // label: icon.label
    });
}

function addLabel() {

}

function calculate(lat_from, lang_from, lat_to, lang_to) {
    var lat_lang_form = {lat: parseFloat(lat_from), lng: parseFloat(lang_from)};
    var lat_lang_to = {lat: parseFloat(lat_to), lng: parseFloat(lang_to)};
    drawLine(lat_lang_form, lat_lang_to);

}

function drawLine(from, to) {
    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer();
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: from,
    });
    directionsRenderer.setMap(map);
    directionsService.route(
        {
            origin:  from,
            destination: to,
            travelMode: google.maps.TravelMode.DRIVING,
        },
        (response, status) => {
            if (status === "OK") {
                directionsRenderer.setDirections(response);
                console.log(response)
            } else {
                window.alert("Directions request failed due to " + status);
            }
        }
    );
}