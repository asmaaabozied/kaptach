@extends('layouts.master')
@section('title',__('pages.price'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection
@section('content')
    @include('companies.shuttle.price_list')
    @include('companies.transfer.price_list')
    @include('companies.tours.price_list')
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(function () {
            $('#example1').DataTable({
                processing: true,
                serverSide: true,
                ServerMethod: "GET",
                ajax: "{{ route('shuttles-price.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'clients.name', name: 'hotel'},
                    {data: 'airports.name', name: 'airport'},
                    {data: 'departure_price', name: 'departure_price'},
                    {data: 'arrival_price', name: 'arrival_price'},
                    {data: 'action', name: 'action'}

                ]
            });
            $('#example2').DataTable({
                processing: true,
                serverSide: true,
                ServerMethod: "GET",
                ajax: "{{ route('transfers-price.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'carModel.model_name', name: 'carModel.model_name'},
                    {data: 'clients.name', name: 'clients.name'},
                    {data: 'airport.name', name: 'airport'},
                    {data: 'departure_price', name: 'departure_price'},
                    {data: 'arrival_price', name: 'arrival_price'},
                    {data: 'action', name: 'action'}

                ]
            });
            $('#example3').DataTable({
                processing: true,
                serverSide: true,
                ServerMethod: "GET",
                ajax: "{{ route('tours-price.index') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'carModel.model_name', name: 'carModel.model_name'},
                    {data: 'tourism_place', name: 'tourism_place'},
                    {data: 'with_food', name: 'with_food'},
                    {data: 'number_hours', name: 'number_hours'},
                    {data: 'tours_start_time', name: 'tours_start_time'},
                    {data: 'tours_end_time', name: 'tours_end_time'},
                    {data: 'action', name: 'action'}

                ]
            });
        });
    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection