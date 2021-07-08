@extends('layouts.master')
@section('title',__('pages.invoices'))
@section('content')
    <div class="box col-lg-10">
        <div class="box-header with-border">
            <div class="col-lg-9"><h3>{{__('pages.invoices')}}</h3></div>
            <div class="col-lg-3"><a href="{{route('annual_report')}}" class="btn btn-primary pull-right"
                                     style="margin-right: 5px;"><i
                            class="glyphicon glyphicon-folder-open"></i>{{__('pages.reports')}}</a></div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-3 form-group">
                    <label>{{__('pages.month')}}/{{__('pages.year')}}:</label>
                    <input type="text" class="form-control" style="width: 100px;" value="{{$month.'/'.$year}}"
                           readonly>
                </div>
                <div class="col-lg-6">
                    <label>{{__('inputs.hotel_tourism')}}:</label>
                    <input type="text" class="form-control" style="width: 100px;" value="{{$client->name}}"
                           readonly>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead style="background-color: darkgray">
                        <tr>
                            <th>{{__('pages.created_at')}}</th>
                            <th>{{__('pages.tax')}}</th>
                            <th>{{__('pages.amount')}}</th>
                            <th>{{__('pages.invoices')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $total=0; @endphp
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{date('d/m/Y',strtotime($invoice->created_at))}}</td>
                                @php $tax=$invoice->tax / 100 ;
                            $invoice_tax=$tax * $invoice->price;
                                $invoices=$invoice_tax +$invoice->price;
                                @endphp
                                <td>({{$invoice->tax}} % ){{$invoice_tax}}</td>
                                <td>{{$invoice->price}}</td>
                                <td>{{$invoice->price + $invoice_tax}}</td>
                            </tr>
                            @php $total=$total+$invoices;@endphp
                        @endforeach
                        </tbody>
                        <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="background-color: lightskyblue">{{__('pages.total')}}:{{$total}}</th>
                        <th></th>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 col-lg-6"></div>
            </div>
            <div class="row no-print">
                <div class="col-xs-12">
                    <button onclick="window.print()" target="_blank" class="btn btn-default"><i
                                class="fa fa-print"></i> {{__('buttons.print')}}</button>
                    <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                        <i class="fa fa-download"></i>{{__('buttons.generate_dpf')}}
                    </button>
                </div>
            </div>
        </div>
@endsection