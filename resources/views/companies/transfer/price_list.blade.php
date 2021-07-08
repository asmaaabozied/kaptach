@section('title',__('pages.price'))


    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{__('pages.transfer'). __('pages.price')}}</h3>
                    <a id="addToTable" class="btn btn-primary pull-right" href="{{route('transfers-price.create')}}">
                        <i class="fa fa-fw fa-plus" aria-hidden="true"></i> {{__('buttons.add_row')}}
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example2" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.car_models')}}</th>
                            <th>{{__('pages.hotel_tourism_list')}}</th>
                            <th>{{__('pages.airports')}}</th>
                            <th>{{__('pages.departure'). __('pages.price')}}</th>
                            <th>{{__('pages.arrival'). __('pages.price')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>{{__('pages.id')}}</th>
                            <th>{{__('pages.car_models')}}</th>
                            <th>{{__('pages.hotel_tourism_list')}}</th>
                            <th>{{__('pages.airports')}}</th>
                            <th>{{__('pages.departure'). __('pages.price')}}</th>
                            <th>{{__('pages.arrival'). __('pages.price')}}</th>
                            <th>{{__('pages.actions')}}</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->