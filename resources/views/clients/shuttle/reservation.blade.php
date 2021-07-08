@extends('layouts.master')
@section('title','Reservation')
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            {!! Form::open(['method'=>'put','url'=>route('clients',$shuttle),'id'=>'basic-form','class'=>'form-horizontal']) !!}
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Reservation</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Date:</label>
                            <div class="col-md-6">
                                <div class="input-group date">

                                    <input type="text" class="form-control" id="datepicker" name="datepicker" readonly
                                           value="{{$shuttle->shuttle_start_time}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Station:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" readonly value="{{$shuttle->station->name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Airport:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" readonly value="{{$shuttle->airport->name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Number of Seats:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" readonly
                                       value="{{$shuttle->number_seats}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Number of Empty Seats:</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" readonly
                                       value="{{$shuttle->number_seats - $shuttle->number_of_booking}}">
                            </div>
                        </div>
                    </div>

                    <!-- /.box-body -->
                </div>
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Guests</h3>
                    </div>
                    <div class="box-body" id="div_append">
                    </div>

                </div>
                <!-- /.box -->
            </div>
            {!! BootForm::submit() !!}
        <!-- /.col -->
            {!! BootForm::close() !!}
        </div>
        <!-- /.row -->
    </section>
@endsection
@section('scripts')
    <!-- bootstrap datepicker -->
    <script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('assets/plugins/sweetalert/sweetalert.min.js')}}"></script>
    <script>
        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        });


        //These codes takes from http://t4t5.github.io/sweetalert/
        function showBasicMessage() {
            swal("There are not enough seats!");
        }

        $(document).ready(function () {
//            $('#add_hotel').click(function () {
            var str = "<div class=\"div-parent\">  " +
                "<div class=\"form-group\">\n" +
                "                            <label class=\"col-md-2 control-label\">Price:</label>\n" +
                "                            <div class=\"col-md-4\">\n" +
                "                                <input type=\"text\" class=\"form-control price\" name=\"price[]\" readonly id=\"price\" value=\"{{$price}}\">\n" +
                "                            </div>\n" +
                "                        </div>\n" +
                "                        <div class=\"form-group\">\n" +
                "                            <label class=\"col-md-2 control-label\">#Guests:</label>\n" +
                "                            <div class=\"col-md-4\">\n" +
                "                                <input type=\"number\" class=\"form-control booking\" value=\"1\" min=\"1\"\n" +
                "                                      id=\"number_of_booking\" name=\"number_of_booking\"\n" +
                "                                      required/>\n" +
                "                            </div>\n" +
                "                        </div>\n" +
                "                        <div class=\"table-responsive\">\n" +
                "                            <table class=\"table table-bordered table-striped table-hover dataTable\"\n" +
                "                                   id=\"customers_table\">\n" +
                "                                <thead>\n" +
                "                                <tr>\n" +
                "                                    <th>Select</th>\n" +
                "                                    <th>Passport Number / TC Identity Number *</th>\n" +
                "                                    <th>First Name*</th>\n" +
                "                                    <th>Last Name*</th>\n" +
                "                                    <th>Gender</th>\n" +
                "                                    <th>Nationality</th>\n" +
                "                                    <th>Phone</th>\n" +
                "                                    <th>Room Number</th>\n" +
                "                                </tr>\n" +
                "                                </thead>\n" +
                "                                <tbody>\n" +
                "                                <tr>\n" +
                "                                    <td><input type=\"checkbox\" name=\"record\" class=\"filled-in\"></td>\n" +
                "                                    <td><input type=\"number\" class=\" form-control\" min=\"1\" name=\"identity_number[]\"\n" +
                "                                               required>\n" +
                "                                    </td>\n" +
                "                                    <td><input type=\"text\" class=\"form-control\" name=\"first_name[]\" id=\"first_name\"\n" +
                "                                               required>\n" +
                "                                    </td>\n" +
                "                                    <td><input type=\"text\" class=\"form-control\" name=\"last_name[]\" id=\"last_name\"\n" +
                "                                               required>\n" +
                "                                    </td>\n" +
                "                                    <td><select name=\"gender[]\" required class=\"form-control\">\n" +
                "                                            <option value=\"female\">Female</option>\n" +
                "                                            <option value=\"male\">Male</option>\n" +
                "                                        </select></td>\n" +
                "                                    <td>\n" +
                "                                        <select name=\"nationality[]\" required class=\"form-control\">\n" +
                "                                            <option value=\"\">Select Nationality</option>\n" +
                "                                            @foreach ($countries as $country)\n" +
                "                                                <option value=\"{{$country->nationality}}\">{{$country->nationality}}</option>\n" +
                "                                            @endforeach\n" +
                "                                        </select>\n" +
                "                                    </td>\n" +
                "                                    <td><input type=\"text\" class=\"form-control\" name=\"phone[]\" id=\"phone\" required></td>\n" +
                "                                    <td><input type=\"number\" class=\"form-control\" min=\"1\" name=\"room_number[]\"\n" +
                "                                               id=\"room_number\"\n" +
                "                                               required>\n" +
                "                                    </td>\n" +
                "                                </tr>\n" +
                "                                </tbody>\n" +
                "                            </table>\n" +
                "                            <button type=\"button\" class=\"delete btn btn-sm btn-danger delete-row\">Delete Row</button>\n" +
                "                        </div></div>";
            $("#div_append").append(str);
            $('.booking').change(function (event) {
                var count = $(this).val();
                var no_of_seats ={{$shuttle->number_seats - $shuttle->number_of_booking}};
                if (count > no_of_seats) {
                    $(this).val(no_of_seats);
                    showBasicMessage();
                } else {
                    $("#customers_table tbody").empty();
                    // $(this).parents('.div-parent').find(".table tbody").empty();
                    for (var n = 0; n < count; n++) {
                        var markup = "<tr><td><input type=\"checkbox\" name=\"record\" class=\"filled-in\"></td>" +
                            "<td><input type=\"number\" class=\" form-control\" min=\"1\" name=\"identity_number[]\"   required></td>" +
                            "<td><input type=\"text\" class=\"form-control\" name=\"first_name[]\"  required></td>" +
                            "<td><input type=\"text\" class=\"form-control\" name=\"last_name[]\"  required></td>" +
                            "<td><select name=\"gender[]\"  required class=\"form-control\">" +
                            "<option value=\"female\">Female</option>" +
                            "<option value=\"male\">Male</option>" +
                            "</select></td>" +
                            "<td><select  name=\"nationality[]\"  required class=\"form-control\">" +
                            "<option value=\"\">Select Nationality</option>" +
                            "@foreach ($countries as $country)" +
                            "<option value=\"{{$country->nationality}}\">{{$country->nationality}}</option>" +
                            "@endforeach" +
                            "</select></td>" +
                            "<td><input type=\"text\" class=\"form-control\" name=\"phone[]\"  required></td>" +
                            "<td><input type=\"number\" class=\"form-control\" min=\"1\" name=\"room_number[]\" required></td>" + "</tr>";
                        $(this).parents('.div-parent').find(".table tbody").append(markup);
                    }
                }
            });

            $(".delete-row").click(function () {
                $("table tbody").find('input[name="record"]').each(function () {
                    if ($(this).is(":checked")) {
                        if ($("#customers_table tr").length > 2) {
                            $(this).parents("tr").remove();
                            var no_passenger = $('#number_of_booking').val();
                            $('#number_of_booking').val(no_passenger - 1);
                        }

                    }
                });
            })
        });
    </script>
@endsection