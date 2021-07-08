<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- Bootstrap 3.3.6 -->
    {{--<title></title>--}}
    <style type="text/css" media="all">
        html, body {
            height: 100%;
        }

        body {
            font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-weight: 400;
            overflow-x: hidden;
            overflow-y: auto;
        }

        div {
            display: block;
        }

        section {
            display: block;
        }

        col-xs-12 {
            width: 100%;
        }

        .col-sm-4 {
            position: relative;
            min-height: 1px;
            padding-right: 15px;
            padding-left: 15px;
            width: 33.33333333%;
            float: left;
        }* {
             -webkit-box-sizing: border-box;
             -moz-box-sizing: border-box;
             box-sizing: border-box;
         }
        /*.col-xs-6 {*/
            /*width: 50%;*/
            /*!*float: left;*!*/
            /*!*position: relative;*!*/
            /*!*min-height: 1px;*!*/
            /*!*padding-right: 15px;*!*/
            /*!*padding-left: 15px;*!*/
        /*}*/
        p {
             margin: 0 0 10px;
         }
        .table-responsive {
              min-height: .01%;
              overflow-x: auto;
          }.table {
               width: 100%;
               max-width: 100%;
               margin-bottom: 20px;background-color: transparent;    border-spacing: 0;
                                          border-collapse: collapse;
           }.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
                border-top: 1px solid #f4f4f4;
            }.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                 padding: 8px;
                 line-height: 1.42857143;
                 vertical-align: top;
                 border-top: 1px solid #ddd;
             }
        .lead {
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 300;
            line-height: 1.4;}
        address {
            margin-bottom: 20px;
            font-style: normal;
            line-height: 1.42857143;
            display: block;
        }

        page-header {
            margin: 10px 0 20px 0;
            font-size: 22px;
            padding-bottom: 9px;
            margin: 40px 0 20px;
            border-bottom: 1px solid #eee;
        }

        .page-header > small {
            color: #666;
            display: block;
            margin-top: 5px;
        }

        .row {
            margin-right: -15px;
            margin-left: -15px;
        }

        .invoice {
            margin: 10px 25px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            background-color: #fff;
        }

    </style>
</head>
<body>
<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> AdminLTE, Inc.
                <small class="pull-right">Date: {{date('d/m/Y',strtotime($invoice->created_at))}}</small>
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            From
            <address>
                <strong>{{$invoice->corporate->name}}.</strong><br>
                {{$invoice->address}}<br>
                Phone: {{$invoice->contact_phone}}<br>
                Email: {{$invoice->contact_email}}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            To
            <address>
                <strong>{{$to_corporate->name}}.</strong><br>
                {{$to_corporate->address}}<br>
                Phone: {{$to_corporate->contact_phone}}<br>
                Email: {{$to_corporate->contact_email}}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice #{{$invoice->code}}</b><br>
            <br>
            <b>Payment Due:</b> {{$invoice->deducted_month}}/{{$invoice->deducted_year}}<br>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->


    <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6" style="width: 50%;">
            <p class="lead" style="padding-top: 143px;">Payment Methods:  {{$invoice->paymentType->type_name}}</p>

            {{--<img src="../../dist/img/credit/visa.png" alt="Visa">--}}

            @if($invoice->notes)
                <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                   Notes:  {{$invoice->notes}}
                </p>
            @endif
        </div>
        <!-- /.col -->
        <div class="col-xs-6" >

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>{{$invoice->price}}</td>
                    </tr>
                    <tr>
                        <th>Tax ({{$invoice->tax}}%)</th>
                        @php $tax=$invoice->tax / 100 ;
                            $invoice_tax=$tax * $invoice->price;
                        @endphp
                        <td>{{$invoice_tax}}</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td>{{$invoice_tax + $invoice->price}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
</body>
</html>