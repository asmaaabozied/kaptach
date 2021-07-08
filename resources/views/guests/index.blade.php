@extends('layouts.master')
@section('title',__('pages.airports'))
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dist/css/hvrbox.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.guests')}}</h3>
                    {{--<a id="addToTable" class="btn btn-primary pull-right" href="{{route('guests.create')}}">--}}
                        {{--<i class="fa fa-fw fa-plus" aria-hidden="true"></i> {{__('buttons.add_row')}}--}}
                    {{--</a>--}}
                </div>

                <div class="row col-md-offset-4" id="date-search" style="margin-bottom: 20px;">
                    <div class="form-group">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control pull-right" name="" value="" id="key_search"
                                       placeholder="Search by name or id">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a  class="btn btn-default" id="search">
                             Search
                            </a>
                        </div>
                    </div>
                </div>

                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.identity_number')}}</th>
                            <th>{{__('pages.first_name')}}</th>
                            <th>{{__('pages.last_name')}}</th>
                            <th>{{__('pages.gender')}}</th>
                            <th>{{__('pages.phone')}}</th>
                            <th>{{__('pages.nationality')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{__('pages.identity_number')}}</th>
                            <th>{{__('pages.first_name')}}</th>
                            <th>{{__('pages.last_name')}}</th>
                            <th>{{__('pages.gender')}}</th>
                            <th>{{__('pages.phone')}}</th>
                            <th>{{__('pages.nationality')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- Transfers Modal -->
            <div class="modal fade" id="transfersModal" tabindex="-1" role="dialog"
                 aria-labelledby="transfersModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header"></div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">{{__('buttons.close')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
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
                ajax: {
                    url: "{{ route('guests.index') }}",
                    type: 'GET',
                    data: function (d) {
                        d.key_search = $('#key_search').val();
                    }
                },
                columns: [
                    {data: 'identity_number', name: 'identity_number'},
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'gender', name: 'gender'},
                    {data: 'phone', name: 'phone'},
                    {data: 'nationality', name: 'nationality'},
                    {data: 'action', name: 'action', searchable: false}

                ],
            });
            $("#search").click(function () {
                $('#example1').DataTable().draw(true);
            });
            $(document).on("click", '.btn_transfer', function (event) {
                // AJAX request
                var guestId = $(this).attr('id');
                $.ajax({
                    url: "guests/" + guestId + "/viewModalWithData",
                    type: 'get',
                    success: function (response) {
                        // Add response in Modal body
                        $('.modal-body').html(response);

                        // Display Modal
                        $('#transfersModal').modal('show');
                    }
                });
            });

        });

    </script>
    <!--bootbox -->
    <script src="{{ url('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ url('assets/dist/js/index.js') }}"></script>
@endsection