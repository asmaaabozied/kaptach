<script>
var gmarkers = [];
function initMap() {
    var latitude = parseFloat(document.getElementById('lat').innerText);
    var longitude = parseFloat(document.getElementById('lng').innerText);

    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: latitude, lng: longitude},
        zoom: 12
    });
    var cars = <?php echo json_encode($cars)?>;
    $.each(cars, function(key, car) {
        var latitude = parseFloat(car.lat);
        var longitude = parseFloat(car.lng);

        for (var i = 0; i < 3; i++) {
            color = car_color(car.status);
            AddCar(latitude ,longitude,map,color,car.name);
        }
    });

}
$("#hotel").on('change', function(){
    $('#car').val(0); $('#status').val(0);
    search('hotel',this.value);
});
$("#car").on('change', function(){
    $('#hotel').val(0); $('#status').val(0);
    search('car',this.value);
});
$("#status").on('change', function(){
    $('#car').val(0); $('#hotel').val(0);
    search('status',this.value);
});
//************************** car_color ************************/
function car_color(status){
    switch(status) {
        case 1://online
            color = 'red';
            break;
        case 2://offline
            color = 'green';
            break;
        default://3: on the road
            color = 'blue';
    }
    return color;
}
//************************** change_location type: Airport or hotel ************************/
function search(type,id){
    $.ajax({
        type:'GET',
        url:'{{url("corporate/map_search/")}}'+'/'+type+'/'+id,
        success:function(data){
            removeMarkers();
            var cars = data.cars;
            $.each(cars, function(key, car) {
                var latitude = parseFloat(car.lat);
                var longitude = parseFloat(car.lng);
                for (var i = 0; i < 3; i++) {
                    color = car_color(car.status);
                    AddCar(latitude ,longitude,map,color,car.name);
                }
            });
            $("#status1").text( data.cars_count_by_status[1]);
            $("#status2").text( data.cars_count_by_status[2]);
            $("#status3").text( data.cars_count_by_status[3]);
        },
        error: function(data) {
        },

    });

}
/***************** AddMarker *****************/
function addMarker(latitude,longitude,title){
    var LatLng = {lat: latitude, lng: longitude};
    var marker = new google.maps.Marker({
        position: LatLng,
        map: map,
        title: title
    });
    return marker;
}
function removeMarkers(){
    for(i=0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
    }
}
/****************** Add car*****************/
function AddCar(latitude,longitude,map,color,title) {
    var icon = { // car icon
        path: 'M29.395,0H17.636c-3.117,0-5.643,3.467-5.643,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759   c3.116,0,5.644-2.527,5.644-5.644V6.584C35.037,3.467,32.511,0,29.395,0z M34.05,14.188v11.665l-2.729,0.351v-4.806L34.05,14.188z    M32.618,10.773c-1.016,3.9-2.219,8.51-2.219,8.51H16.631l-2.222-8.51C14.41,10.773,23.293,7.755,32.618,10.773z M15.741,21.713   v4.492l-2.73-0.349V14.502L15.741,21.713z M13.011,37.938V27.579l2.73,0.343v8.196L13.011,37.938z M14.568,40.882l2.218-3.336   h13.771l2.219,3.336H14.568z M31.321,35.805v-7.872l2.729-0.355v10.048L31.321,35.805',
        scale: 0.6,
        fillColor: color, //<-- Car Color, you can change it
        fillOpacity: 1,
        strokeWeight: 1,
        anchor: new google.maps.Point(0, 5),
        rotation: 160//data.val().angle //<-- Car angle
    };
    var LatLng = {lat: latitude, lng: longitude};
    var marker = new google.maps.Marker({
        position: LatLng,
        icon: icon,
        map: map,
        title: title
    });
    gmarkers.push(marker);
}

</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDFkSLH-JOeEK-00L0sE8-usUl7yH_ZK4Y&callback=initMap">
    </script>