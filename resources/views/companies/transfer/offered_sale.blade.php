@extends('layouts.master')
@section('title','Offered for sale')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Offered for sale</h3>
                    </div>
                    <!-- /.box-header -->
                    {!! BootForm::model($transfer, 'sale', ['method'=>'put', 'route'=>['transfers.postOfferForSale',$transfer->id]],['id'=>'basic-form']) !!}
                    <div class="box-body">
                        <p>Sale For :</p>
                        {!! BootForm::checkbox('sale_for[]','Drivers Only','1','',false) !!}
                        {!! BootForm::checkbox('sale_for[]','Transfer Companies','2','',false) !!}

                    </div>
                    <div class="box-footer">
                        {!! BootForm::submit() !!}
                    </div>
                {!! BootForm::close() !!}
                <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection