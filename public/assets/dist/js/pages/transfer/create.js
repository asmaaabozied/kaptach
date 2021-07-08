$('#basic-form').submit(function()
{
     //Check type to set room or flight
     if ("{{$type}}" == 'arrival'){                
        if(document.getElementById('flight_number').value == ""){
            //document.getElementById('flight_number').focus();
            alert("flight number is required");
            return false;
        }              
    } 
    else{
        for (var n = 0; n < document.getElementById('number_of_booking').value; n++) {
            if(document.getElementById('room_number-'+n).value == ""){
                //document.getElementById('room_number-'+n).focus();
                alert("room number is required");
                return false;
            }
        }
    }   

});
$('#number_of_booking').change(function (event) {
    var count = $('#number_of_booking').val();
        $("#customers_table tbody").empty();
        for (var n = 0; n < count; n++) {
            var markup = "<tr><td><input type=\"checkbox\" name=\"record\" class=\"filled-in\"></td>"+
                "<td><input type=\"text\" class=\" form-control\" name=\"identity_number[]\" id=\"identity_number-"+n+"\" onchange=\"identity_number_change(this.value,this.id)\" required></td>"+
                "<td><select name=\"gender[]\" id=\"gender-"+n+"\" required class=\"form-control\">"+
                        "<option value=\"female\">Female</option>"+
                        "<option value=\"male\">Male</option>"+
                    "</select></td>"+
                "<td><select  name=\"nationality[]\" id=\"nationality-"+n+"\" required class=\"form-control\">"+
                        "<option value=\"\">Select Nationality</option>"+
                        "@foreach ($countries as $country)"+
                            "<option value=\"{{$country->id}}\">{{$country->nationality}}</option>"+
                        "@endforeach"+
                    "</select></td>"+                                        
                "<td><input type=\"text\" class=\"form-control\" name=\"first_name[]\" id=\"first_name-"+n+"\" required></td>"+
                "<td><input type=\"text\" class=\"form-control\" name=\"last_name[]\" id=\"last_name-"+n+"\" required></td>"+                                                                        
                "<td><input type=\"text\" class=\"form-control\" name=\"phone[]\" id=\"phone-"+n+"\" required></td>"+
                "<td><input type=\"text\" class=\"form-control\" name=\"room_number[]\" id=\"room_number-"+n+"\"></td>"+"</tr>";
            $("#customers_table tbody").append(markup);            
    }            
});