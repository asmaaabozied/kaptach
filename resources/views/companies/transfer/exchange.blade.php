@extends('layouts.master')
@section('title',__('pages.create') .  __('pages.transfers'))
@section('styles')
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
          href="{{asset('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/sweetalert/sweetalert.css')}}">
    {{--<link rel="stylesheet" href="{{asset('assets/plugins/intlTelInput/css/intlTelInput.css'))}}">--}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets/plugins/ccpicker/css/jquery.ccpicker.css')}}">
@endsection
@section('content')
    <section class="content">
        <div class="row">
            {!! BootForm::open('create', ['url'=>route('exchanges.store',$transfer->id),'id'=>'basic-form','class'=>'form-horizontal']) !!}
            {{--<form id="exchangeSubmit">--}}
            <div class="box">

                <div class="box-header" id="exchange_blade">
                    <div class="col-md-6">
                        <label for="airport">{{__('pages.airports')}}</label>
                        <select class="form-control" name="airport[0]">
                            <option value="">Select airport</option>
                            @foreach($airports as $airport)
                                <option value="{{$airport->id}}">{{$airport->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>{{__('pages.type')}}</label>
                        {!! BootForm::checkbox('type[0][]', 'Arrival', 'arrival',null,false) !!}
                        {!! BootForm::checkbox('type[0][]', 'Departure', 'departure',null,false) !!}
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-4">
                            <label for="from">{{__('pages.from')}}</label>
                            <div class="input-group date form_datetime">
                                <input class="form-control" size="16" type="text" value="" readonly
                                       name="from[0]">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="to">{{__('pages.to')}}</label>
                            <div class="input-group date form_datetime">
                                <input class="form-control" size="16" type="text" value="" readonly
                                       name="to[0]">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="box-header">
                    <div class="appendto"></div>
                </div>
                <div class="box-body">

                    <p><tt id="results"></tt></p>
                </div>


                <div class="col-md-12" style="margin-top: 10px;margin-bottom: 10px;">
                    <a href="#" class="btn btn-default" id="add_more_attributes">Add more</a>
                </div>

                <div class="box-footer">
                    {!! BootForm::submit() !!}
                </div>
            </div>

        {!! BootForm::close() !!}
        {{--</form>--}}
        <!-- /.box-body -->
        </div>


        <!-- /.box -->
    </section>
@endsection
@section('scripts')

    <!-- bootstrap datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js')}}"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script>

        $(document).ready(function () {
            //Date picker
            $(".form_datetime").datetimepicker({
                format: "yyyy-mm-dd hh:ii",
                autoclose: true,
            });
            var i = 0;
            $('#add_more_attributes').click(function () {
                i++;
                $(".appendto").append("    <div class=\"box-header append\">\n" +
                    "                     <span>Or</span><div class=\"tools\">\n" +
                    "                        <a class='delete' href=\"#\"><i class=\"fa fa-trash-o\"></i></a>\n" +
                    "\n" +
                    "                    </div>\n" +
                    "                        <div class=\"col-md-6\">\n" +
                    "                            <label for=\"airport\">{{__('pages.airports')}}</label>\n" +
                    "                            <select class=\"form-control\" name=\"airport[" + i + "]\">\n" +
                    "                                <option value=\"\">Select airport</option>\n" +
                    "                                @foreach($airports as $airport)\n" +
                    "                                    <option value=\"{{$airport->id}}\">{{$airport->name}}</option>\n" +
                    "                                @endforeach\n" +
                    "                            </select>\n" +
                    "                        </div>\n" +
                    "                        <div class=\"col-md-6\">\n" +
                    "                            <label>{{__('pages.type')}}</label>\n" +
                    "                             <div class=\"checkbox\"><label><input name=\"type[" + i + "][]\" type=\"checkbox\" class=\"chkBlock\" value=\"arrival\" >Arrival</label></div>\n" +
                    "                            <div class=\"checkbox\"><label><input name=\"type[" + i + "][]\" type=\"checkbox\" class=\"chkBlock\" value=\"departure\" >Departure</label></div>\n" +
                    "                        </div>\n" +
                    "                        <div class=\"col-md-6\">\n" +
                    "                                <div class=\"col-md-4\">\n" +
                    "                            <label for=\"from\">{{__('pages.from')}}</label>\n" +
                    "                            <div class=\"input-group date form_datetime\">\n" +
                    "                                <input class=\"form-control\" size=\"16\" type=\"text\" value=\"\" readonly\n" +
                    "                                       name=\"from[" + i + "]\">\n" +
                    "                                <span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>\n" +
                    "                            </div>\n" +
                    "                        </div>\n" +
                    "                        <div class=\"col-md-4\">\n" +
                    "                            <label for=\"to\">{{__('pages.to')}}</label>\n" +
                    "                            <div class=\"input-group date form_datetime\">\n" +
                    "                                <input class=\"form-control\" size=\"16\" type=\"text\" value=\"\" readonly\n" +
                    "                                       name=\"to[" + i + "]\">\n" +
                    "                                <span class=\"input-group-addon\"><i class=\"fa fa-calendar\"></i></span>\n" +
                    "                            </div>\n" +
                    "                        </div>" +
                    "                    </div>\n" +
                    "                \n" +
                    "                </div>"
                );
                //Date picker
                $(".form_datetime").datetimepicker({
                    format: "yyyy-mm-dd hh:ii",
                    autoclose: true,
                });
                $('.delete').click(function () {
                    $(this).parents('.append').remove();
                    return false;
                });
            });


        });


    </script>

@endsection