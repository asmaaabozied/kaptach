<div class="modal fade" id="searchModal" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{__('pages.search_in'). __('pages.transfers')}}</h4>
            </div>
            {!! Form::open(['method'=>'GET', 'route'=>'store.index']) !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::select('hotel_id',__('inputs.hotel_tourism'),$hotels->prepend(__('inputs.select_hotel_tourism'),''),null,$errors,['class'=>'form-control','id'=>'hotel_id']) !!}
                    </div>
                    <div class="col-md-6">
                        <label></label>
                        {!! BootForm::checkbox('shift', 'With Shift', 1, Request::input('shift')) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::input('text', 'from', Request::input('from'),  __('inputs.date_from'), $errors, ['class'=>'datepicker','id'=>'from','data-date-format'=>"yyyy-mm-dd"]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! BootForm::input('text', 'to', Request::input('to'), __('inputs.date_to'), $errors, ['class'=>'datepicker','id'=>'to','data-date-format'=>"yyyy-mm-dd"]) !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" value=" {{__('buttons.search')}}">
                <a href="{{ route('transfers.index') }}" class="btn btn-default">
                    <i class="fa fa-eraser" aria-hidden="true"></i>
                    {{__('buttons.clear')}}
                </a>
                <button type="button" name="export" value="xls" class="btn btn-primary">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> {{__('buttons.export')}}
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i> {{__('buttons.close')}}
                </button>
            </div>
            {!! BootForm::close() !!}
        </div>

    </div>
</div>