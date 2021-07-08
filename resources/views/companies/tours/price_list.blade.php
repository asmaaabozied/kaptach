@section('title','Price List')


    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Tours Price</h3>
                    <a id="addToTable" class="btn btn-primary pull-right" href="{{route('tours-price.create')}}">
                        <i class="fa fa-fw fa-plus" aria-hidden="true"></i> Add row
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example3" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>car model</th>
                            <th>Tourism place</th>
                            <th>Food</th>
                            <th>Number hours</th>
                            <th>Tours start time</th>
                            <th>Tours end time</th>
                            <th>action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>id</th>
                            <th>car model</th>
                            <th>Tourism place</th>
                            <th>Food</th>
                            <th>Number hours</th>
                            <th>Tours start time</th>
                            <th>Tours end time</th>
                            <th>action</th>
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