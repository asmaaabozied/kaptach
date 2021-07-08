@extends('layouts.master')
@section('title',__('pages.settings'))
@section('styles')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-fw fa-cog"></i>{{__('pages.settings')}}</h3>
                </div>
                <!-- /.box-header -->
                {!! BootForm::model($update, 'edit', ['method'=>'put', 'route'=>['settings_update'],'files'=>true],['id'=>'basic-form']) !!}
                <div class="box-body">
                    <div class="col-lg-6">
                        {!! BootForm::input('text', 'name', null, __('inputs.name'), $errors, ['required','class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'contact_phone', null, __('inputs.contact_phone'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'contact_email', null, __('inputs.contact_email'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::input('text', 'website', null, __('inputs.website'), $errors, ['class'=>'form-control']) !!}
                        {!! BootForm::file('logo',  __('inputs.logo'), $errors, ['accept'=>'png,jpg,jpeg']) !!}
                            @if (empty($update->logo))
                                {{__('messages.there_no_image')}}
                            @else
                                <img src="{{url('uploads/companies/'.$update->logo)}}" width="100" height="100">
                            @endif
                            @if($update->id !=1 && $update->type == 'commercial')
                                {!! BootForm::checkbox('receive_request_from_drivers', __('messages.receive_request_from_drivers'),1, $update->receive_request_from_drivers,false) !!}
                            @endif


                    </div>

                        <div class="col-lg-6">
                            <p>Transfer Settings</p>
                            <div class="form-group">
                                <p>Offer for sale :</p>
                                {!! BootForm::radio('sale_for','1','checked').' None' !!}<br>
                                {!! BootForm::radio('sale_for','2').' Drivers Only' !!}<br>
                                {!! BootForm::radio('sale_for','3').' Transfer Companies Only' !!}<br>
                                {!! BootForm::radio('sale_for','4').' Drivers and Transfer Companies' !!}
                            </div>
                            <div class="form-group">
                                @php $array=[1=>'Per Day',2=>'Per Week','3'=>'Per month']@endphp
                                {!! BootForm::select('duration_sold','Sold Duration',$array,null,$errors) !!}
                            </div>

                        </div>
                </div>
                <div class="box-footer">
                    {!! BootForm::submit() !!}
                </div>
            {!! BootForm::close() !!}
            <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('scripts')

@endsection