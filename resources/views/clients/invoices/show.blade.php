@extends('layouts.master')
@section('title',__('pages.invoices'))

@section('content')
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> {{__('pages.invoices')}}
                    <small class="pull-right">{{__('pages.date')}}: {{date('d/m/Y',strtotime($invoice->created_at))}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                {{__('pages.from')}}
                <address>
                    <strong>{{$invoice->client->name}}.</strong><br>
                    {{$invoice->address}}
                    <br>   {{__('pages.phone')}}: {{$invoice->contact_phone}}<br>
                    {{__('pages.email')}}: {{$invoice->contact_email}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                {{__('pages.to')}}
                <address>
                    <strong>{{$to_company->name}}.</strong><br>
                    {{$to_company->address}}
                    <br>     {{__('pages.phone')}}: {{$to_company->contact_phone}}<br>
                    {{__('pages.email')}}: {{$to_company->contact_email}}
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>{{__('pages.invoice')}} #{{$invoice->code}}</b><br>
                <br>
                <b>{{__('pages.payment_due')}}:</b> {{$invoice->deducted_month}}/{{$invoice->deducted_year}}<br>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->


        <div class="row">
            <!-- accepted payments column -->
            <div class="col-xs-6">
                <p class="lead">{{__('pages.payment_methods')}}:  {{$invoice->paymentType->type_name}}</p>

                {{--<img src="../../dist/img/credit/visa.png" alt="Visa">--}}
                @if($invoice->notes)
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        {{$invoice->notes}}
                    </p>
                @endif
            </div>
            <!-- /.col -->
            <div class="col-xs-6">

                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">{{__('pages.subtotal')}}:</th>
                            <td>{{$invoice->price}}</td>
                        </tr>
                        <tr>
                            <th>{{__('pages.tax')}} ({{$invoice->tax}}%)</th>
                            @php $tax=$invoice->tax / 100 ;
                            $invoice_tax=$tax * $invoice->price;
                            @endphp
                            <td>{{$invoice_tax}}</td>
                        </tr>
                        <tr>
                            <th>{{__('pages.total')}}:</th>
                            <td>{{$invoice_tax + $invoice->price}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <a href="#" target="_blank" class="btn btn-default" id="print"><i class="fa fa-print"></i>
                    {{__('buttons.print')}}</a>
                <a type="button" href="{{route('clients.invoices.downloadPDF', $invoice->id)}}"
                   class="btn btn-primary pull-right" style="margin-right: 5px;">
                    <i class="fa fa-download"></i>{{__('buttons.generate_dpf')}}
                </a>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    <script>
        $('#print').click(function () {
            window.print();
        })
    </script>
@endsection