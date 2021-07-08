<div class="row">
    <div class="col-md-3 table-responsive">&nbsp;</div>
    <div class="col-md-6 table-responsive" id="main">
        <table class="table table-bordered table-striped table-hover">
            <thead style="background-color: darkgray">
            <tr>
                <th>{{__('pages.hotel_tourism_list')}}</th>
                <th>{{__('pages.transfers')}}</th>
                <th>{{__('pages.tax')}}</th>
                <th>{{__('pages.payments')}}</th>
                <th>{{__('pages.total')}}</th>
            </tr>
            </thead>
            <tbody>
            @php $total=0;
            $total_transfers=0;
            $total_taxs=0;
            $total_payments=0;
            @endphp
            @foreach($data as $hotel)
                @php $total=$total + $hotel['total'];
                $total_transfers=$total_transfers+$hotel['transfers'];
                $total_taxs=$total_taxs+$hotel['total_tax'];
                $total_payments=$total_payments+$hotel['total_payments'];
                @endphp
                <tr>
                    <td data-toggle="collapse" data-target="#collapseOne">{{$hotel['name']}}</td>

                    <td style="color: blue">{{$hotel['transfers']}}</td>
                    <td style="color: blue">{{$hotel['total_tax']}}</td>
                    <td style="color: blue">{{$hotel['total_payments']}}</td>
                    <td style="color: blue" class="IndebtednessCell">{{$hotel['total']}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <th>{{__('pages.total')}} :</th>
            <th>{{$total_transfers}}</th>
            <th>{{$total_taxs}}</th>
            <th>{{$total_payments}}</th>
            <th style="background-color: lightskyblue"> {{$total}}</th>
            </tfoot>
        </table>
    </div>
</div>