<div class="row">
    <div class="col-xs-12 table-responsive">
        <table class="table table-bordered table-striped table-hover" id="searchTable">
            <thead style="background-color: darkgray">
            <tr>
                <th>{{__('pages.month')}}</th>
                <th>{{__('pages.sales')}}</th>
                <th>{{__('pages.payments')}}</th>
                <th>{{__('pages.tax')}}</th>
                <th>{{__('pages.invoices')}}</th>
                <th>{{__('pages.dept')}}</th>
                <th>{{__('pages.total')}}</th>
                <th>{{__('pages.actions')}}</th>
            </tr>
                    <tr style="background-color: bisque">
                        <th>{{__('pages.last_balance')}}</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$last_balance}}</td>
                        <td></td>
                        <td></td>
                    </tr>
            </thead>
            <tbody>
            @foreach($data['month'] as $value)
                <tr>
                    <td>{{$value['month']}}</td>
                    <td>{{$value['sales']}}</td>
                    <td style="color: red">-{{$value['payments']}}</td>
                    <td>{{$value['tax']}}</td>
                    <td>{{$value['invoices']}}</td>
                    <td style="color: blue">{{$value['dept']}}</td>
                    <td style="color: mediumvioletred">{{$value['total']}}</td>
                    <td>
                        <a href="{{route('payments_report',[$data['item'],$data['year'],$value['month_num']])}}"
                           class="btn btn-primary btn-sm"><i
                                    class="glyphicon glyphicon-usd"></i> {{__('pages.payments')}}</a>
                        <a href="{{route('transportation_report',[$data['item'],$data['year'],$value['month_num']])}}" class="btn btn-primary btn-sm"><i
                                    class="glyphicon glyphicon-list-alt"></i> {{__('pages.transportation')}}</a>
                        <a href="{{route('invoices-report',[$data['item'],$data['year'],$value['month_num']])}}"
                           class="btn btn-primary btn-sm"
                        ><i class="glyphicon glyphicon-file"></i> {{__('pages.invoices')}}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="background-color: lightskyblue">{{__('pages.balance')}}: {{$data['total']}}</th>
            <th></th>
            </tfoot>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-xs-4 col-lg-6"></div>
    <div class="col-xs-8 col-lg-6">
        <p class="lead">{{__('pages.amount_due')}} {{$data['year']}}</p>

        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th style="width:50%">{{__('pages.last_balance')}}:</th>
                    <td>{{$last_balance}}</td>
                </tr>
                <tr>
                    <th>{{__('pages.sales')}}:</th>
                    <td>{{$data['total_sales']}}</td>
                </tr>
                <tr>
                    <th>{{__('pages.payments')}}</th>
                    <td>-{{$data['total_payments']}}</td>
                </tr>
                <tr>
                    <th>{{__('pages.tax')}}:</th>
                    <td>{{$data['total_tax']}}</td>
                </tr>
                <tr>
                    <th>{{__('pages.total')}}:</th>
                    <td>@php
                            $total_all=$last_balance+$data['total_sales']+$data['total_tax']-$data['total_payments'];
                                   echo $total_all;
                        @endphp</td>
                </tr>
            </table>
        </div>
    </div>
</div>
</div>