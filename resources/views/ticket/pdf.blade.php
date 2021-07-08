<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <!-- Bootstrap 3.3.6 -->
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">--}}
<!-- Ionicons -->
{{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">--}}
<!-- Theme style -->
    <link rel="stylesheet" href="/assets/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/assets/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">
    <style type="text/css" media="all">
        {{--html, body {--}}
    {{--height: 100%;--}}
    {{--}--}}

    body {
            font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-weight: 400;
            overflow-x: hidden;
            overflow-y: auto;
            width: 210mm;
            height: 297mm;
        }

        /*.content{*/
        /*width: 210mm;*/
        /*height: 297mm;*/
        /*}*/
        @page {
            size: a4 landscape;
            margin: 0.9px;
            padding: 0.9px;
        }

        /*body {*/
        /*font-family: Times New Roman;*/
        /*font-size: 33px;*/
        /*text-align: center;*/
        /*border: thin solid black;*/
        /*}*/
        /*.box-header p{*/
        /*color: #33c151;*/
        /*}*/

        .top_p h1 {
            width: 100%;
            float: left;
            padding: 10px 0 5px;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 15px;
            font-weight: bold;
            margin: 0;
            color: #000;
        }

        .top_l {
            float: right;
            margin-right: 16px;
        }

        /*.top_l p {*/
        /*width: 100%;*/
        /*float: left;*/
        /*font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;*/
        /*font-size: 13px;*/
        /*font-weight: normal;*/
        /*color: #777;*/
        /*}*/

        /*.top_l h1 {*/
        /*width: 100%;*/
        /*float: left;*/
        /*font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;*/
        /*font-size: 15px;*/
        /*font-weight: bold;*/
        /*margin: 0;*/
        /*color: #000;*/
        /*}*/

        .box-bord {
            width: 100%;
            margin: 30px 0 0;
            padding: 15px 15px 0;
            border: 1px solid #000;
            float: left;
        }

        .box-bord h1 {
            width: auto;
            float: left;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 19px;
            font-weight: bold;
            margin: -27px 0 0 25px;
            color: #000;
            background-color: #fff;
            padding: 0px 15px;
        }

        .invoice-data {
            width: 100%;
            float: left;
            padding: 20px;
            border: none;
        }

        /*.invoice-data p {*/
        /*float: left;*/
        /*font-size: 13px;*/
        /*font-weight: normal;*/
        /*color: #777;*/
        /*width: 30%;*/
        /*margin-top: 3px;*/
        /*}*/

        /*.invoice-data h2 {*/
        /*width: 100%;*/
        /*float: left;*/
        /*font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;*/
        /*font-size: 16px;*/
        /*font-weight: normal;*/
        /*color: #2785dd;*/
        /*margin: 0 0 10px;*/
        /*}*/

        /*.invoice-data h3 {*/
        /*width: 100%;*/
        /*float: left;*/
        /*font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;*/
        /*font-size: 13px;*/
        /*font-weight: normal;*/
        /*color: #000;*/
        /*margin: 0 0 10px;*/
        /*line-height: 22px;*/
        /*}*/

        {{--.table-responsive {--}}
        {{--min-height: .01%;--}}
        {{--overflow-x: auto;--}}
        {{--}--}}

        {{--.table {--}}
        {{--width: 100%;--}}
        {{--max-width: 100%;--}}
        {{--margin-bottom: 20px;--}}
        {{--background-color: transparent;--}}
        {{--border-spacing: 0;--}}
        {{--border-collapse: collapse;--}}
        {{--}--}}

        {{--.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {--}}
        {{--border-top: 1px solid #f4f4f4;--}}
        {{--}--}}

        {{--.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {--}}
        {{--padding: 8px;--}}
        {{--line-height: 1.42857143;--}}
        {{--vertical-align: top;--}}
        {{--border-top: 1px solid #ddd;--}}
        {{--}--}}

        {{--.content {--}}
        {{--min-height: 945px;--}}
        {{--padding: 15px;--}}
        {{--margin-right: auto;--}}
        {{--margin-left: auto;--}}
        {{--padding-left: 15px;--}}
        {{--padding-right: 15px;--}}
        {{--width: 750px;--}}
        {{--}--}}

        {{--.box {--}}
        {{--width: 100%;--}}
        {{--}--}}

    </style>

</head>
<body>
<section class="content">
    <div class="box col-lg-12">
        <div class="col-lg-12">
            <div class="box-header with-border ">
                <div class="row">
                    <div class="col-lg-5">
                        <b> Transfer Ticket </b>
                        <p> Please be ready 15 minutes before! </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="box-body with-border">
            <div class="col-lg-12" style="padding:0;">

                @if($transfer->cancelled == 1)
                    <div class="col-lg-5">
                        <div class="pull-right" style="    margin-top: -45px;
    transform: rotate(30deg);
    font-size: 2em;
    color: red;
    margin-right: -227px;">
                            {{__('pages.cancelled')}}
                        </div>

                    </div>
                @endif
            </div>

            <div class="box-bord">
                <div class="row">
                    <h1>General Details</h1>
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>Transfer Code:</td>
                                <td>{{$transfer->id}}</td>
                            </tr>
                            <tr>
                                <td>FROM:</td>
                                <td>{{$transfer->airport->name}}</td>
                            </tr>
                            <tr>
                                <td>To:</td>
                                <td>{{$transfer->transferable->name}}</td>
                            </tr>
                            <tr>
                                <td>Transfer Type:</td>
                                <td>{{$transfer->type}}</td>
                            </tr>
                            <tr>
                                <td>Start Time:</td>
                                <td>{{$transfer->transfer_start_time}}</td>
                            </tr>
                            <tr>
                                <td>Number of Adults:</td>
                                <td>{{count($transfer->guests)}}</td>
                            </tr>
                            @if($transfer->shift)
                                <tr>
                                    <td>Driver Name:</td>
                                    <td>{{$transfer->shift->driver->first_name}} {{$transfer->shift->driver->last_name}}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>


                    <div class="box-bord">
                        <div class="row">
                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Passport Number / TC Identity Number *</th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Gender</th>
                                            <th>Nationality</th>
                                            <th>Phone</th>
                                            <th>Room Number</th>
                                        </tr>
                                        </thead>
                                        @foreach($transfer->guests as $guest)
                                            <tr>
                                                <td>{{$guest->identity_number}}</td>
                                                <td>{{$guest->first_name}}</td>
                                                <td>{{$guest->last_name}}</td>
                                                <td>{{$guest->gender}}</td>
                                                <td>{{$guest->nationality}}</td>
                                                <td>{{$guest->phone}}</td>
                                                <td>{{$guest->pivot->room_number}}</td>
                                            </tr>
                                        @endforeach
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                        </div>
                    </div>

                    @if($transfer->notes)
                        <div class="box-bord">
                            <div class="row">
                                <h1>{{__('pages.notes')}} </h1>
                                <div class="col-sm-12 invoice-data">
                                    <h3>{!! $tranfer->notes !!}</h3>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if($transfer->cancelled == 1)
                        <div class="box-bord">
                            <div class="row">
                                <h1>{{__('pages.cancelled')}} </h1>
                                <div class="col-sm-12 invoice-data">
                                    <h3>  {{__('inputs.cancel_reason')}}:
                                        {!! $transfer->cancel_reason !!}<br>
                                        by : {{$transfer->cancellable->name }}
                                        {{$transfer->cancellable->first_name.' ' .$transfer->cancellable->last_name}}
                                        <br>
                                        {{$transfer->cancellable->type}}</h3>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
</section>
</body>
</html>