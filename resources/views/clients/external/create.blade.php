<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$client->name}}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!--->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{asset('/assets/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('/assets/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('/assets/dist/css/skins/_all-skins.min.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
          href="{{asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]-->
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <style>
        .content {
            max-width: 600px;
            min-width: 324px;
            margin: 50px auto 0px;
        }

        .box-header {
            padding: 1.5em 2.5em;
            border-bottom: 1px solid #ccc;
            background: url(https://images2.imgbox.com/a5/2e/m3lRbCCA_o.jpg) left -80px;
            color: #fff
        }

        .box-header .box-title {
            font-family: 'Lato', sans-serif;
            font-weight: 400;
            font-size: 2.25em;
            margin: 1em 0 0.4em 0;
        }
    </style>
</head>
<body class="skin-blue layout-boxed sidebar-mini">
<section class="content">
    <form class="form-horizontal">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Fill out the form below</h3>
            </div>
            <div class="box-body">

                <div class="form-group">
                    <label class="col-xs-2 control-label">{{__('inputs.date')}}:</label>
                    <div class="input-group date form_datetime col-xs-6">
                        <input class="form-control" size="16" type="text" value="" readonly required
                               name="datetimepicker" id="datetimepicker">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">{{__('pages.type')}}:</label>
                    <div class="col-md-6">
                        <select class="form-control" name="type">
                            <option value="">{{__('pages.type')}}</option>
                            <option value="arrival">{{__('inputs.arrival')}}</option>
                            <option value="departure">{{__('inputs.departure')}}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">{{__('inputs.flight_no')}}:</label>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="flight_number"
                               id="flight_number" required/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">{{__('inputs.number_of_seats')}}:</label>
                    <div class="col-md-6">
                        <input type="number" class="form-control" value="1" min="1" name="number_of_booking"
                               id="number_of_booking" required/>
                    </div>
                </div>
                <hr>
                <h3 class="box-title">Guests</h3>
                <a href="#" class="btn btn-success" id="add_one">{{__('buttons.add_row')}}</a>
                <button type="button"
                        class="delete btn btn-sm btn-danger delete-row">{{__('buttons.delete_row')}}</button>
            </div>
            <div class="box-body guests">
                <div class="one_row">
                    <div class="col-md-2">
                        <input type="checkbox" name="record" class="filled-in">
                    </div>
                    <div class="col-md-10">
                        <div class="form-group">
                            <label class="col-md-6 control-label">{{__('pages.passport_number')}}*:</label>
                            <div class="col-md-6">
                                <input type="text" class=" form-control" min="1" name="identity_number[]"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{__('pages.first_name')}}*:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="first_name[]" id="first_name"
                                       required>
                            </div>
                            <label class="col-md-2 control-label">{{__('pages.last_name')}}*:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="last_name[]" id="last_name"
                                       required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{__('pages.nationality')}}*:</label>
                            <div class="col-md-4">
                                <select name="nationality[]" required class="form-control">
                                    <option value="">{{__('pages.select'). __('pages.nationality')}}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->nationality}}">{{$country->nationality}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <label class="col-md-2 control-label">{{__('pages.phone')}}*:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="phone[]" id="phone" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">{{__('pages.gender')}}*:</label>
                            <div class="col-md-6">
                                <select name="gender[]" required class="form-control">
                                    <option value="female">{{__('inputs.female')}}</option>
                                    <option value="male">{{__('inputs.male')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="append"></div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {!! BootForm::submit() !!}
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </form>
</section>
<script src="{{asset('/assets/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('/assets/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')}}"></script>
<script>
    $(document).ready(function () {
        //Date picker
        $(".form_datetime").datetimepicker({
            format: "yyyy-mm-dd hh:ii",
            autoclose: true,
        });
        $('#add_one').click(function () {
            var str = "  <div class=\"one_row\">   <div class=\"col-md-2\">\n" +
                "                    <input type=\"checkbox\" name=\"record\" class=\"filled-in\">\n" +
                "                </div>\n" +
                "                <div class=\"col-md-10\">\n" +
                "                    <div class=\"form-group\">\n" +
                "                        <label class=\"col-md-6 control-label\">{{__('pages.passport_number')}}*:</label>\n" +
                "                        <div class=\"col-md-6\">\n" +
                "                            <input type=\"text\" class=\" form-control\" min=\"1\" name=\"identity_number[]\"\n" +
                "                                   required>\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "                    <div class=\"form-group\">\n" +
                "                        <label class=\"col-md-2 control-label\">{{__('pages.first_name')}}*:</label>\n" +
                "                        <div class=\"col-md-4\">\n" +
                "                            <input type=\"text\" class=\"form-control\" name=\"first_name[]\" id=\"first_name\"\n" +
                "                                   required>\n" +
                "                        </div>\n" +
                "                        <label class=\"col-md-2 control-label\">{{__('pages.last_name')}}*:</label>\n" +
                "                        <div class=\"col-md-4\">\n" +
                "                            <input type=\"text\" class=\"form-control\" name=\"last_name[]\" id=\"last_name\"\n" +
                "                                   required>\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "                    <div class=\"form-group\">\n" +
                "                        <label class=\"col-md-2 control-label\">{{__('pages.nationality')}}*:</label>\n" +
                "                        <div class=\"col-md-4\">\n" +
                "                            <select name=\"nationality[]\" required class=\"form-control\">\n" +
                "                                <option value=\"\">{{__('pages.select'). __('pages.nationality')}}</option>\n" +
                "                                @foreach ($countries as $country)\n" +
                "                                    <option value=\"{{$country->nationality}}\">{{$country->nationality}}</option>\n" +
                "                                @endforeach\n" +
                "                            </select>\n" +
                "                        </div>\n" +
                "                        <label class=\"col-md-2 control-label\">{{__('pages.phone')}}*:</label>\n" +
                "                        <div class=\"col-md-4\">\n" +
                "                            <input type=\"text\" class=\"form-control\" name=\"phone[]\" id=\"phone\" required>\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "                    <div class=\"form-group\">\n" +
                "                        <label class=\"col-md-2 control-label\">{{__('pages.gender')}}*:</label>\n" +
                "                        <div class=\"col-md-6\">\n" +
                "                            <select name=\"gender[]\" required class=\"form-control\">\n" +
                "                                <option value=\"female\">{{__('inputs.female')}}</option>\n" +
                "                                <option value=\"male\">{{__('inputs.male')}}</option>\n" +
                "                            </select>\n" +
                "                        </div>\n" +
                "                    </div>\n" +
                "                </div></div>\n";
            $('#append').append(str);
            var num_booking = $('#number_of_booking').val();
            num_booking++;
            $('#number_of_booking').val(num_booking)
        });
        $(".delete-row").click(function () {
            $(".guests").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    if ($('.one_row').length > 1) {
                        $(this).parents(".one_row").remove();
                        var no_passenger = $('#number_of_booking').val();
                        $('#number_of_booking').val(no_passenger - 1);
                    }

                }
            });
        });
    });
</script>
</body>