@section('title',__('pages.price'))
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{__('pages.shuttle'). __('pages.price')}}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>{{__('pages.id')}}</th>
                        <th>{{__('pages.airports')}}</th>
                        <th>{{__('pages.departure'). __('pages.price')}}</th>
                        <th>{{__('pages.arrival'). __('pages.price')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>{{__('pages.id')}}</th>
                        <th>{{__('pages.airports')}}</th>
                        <th>{{__('pages.departure'). __('pages.price')}}</th>
                        <th>{{__('pages.arrival'). __('pages.price')}}</th>
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
