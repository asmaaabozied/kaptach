<div class="modal fade" id="searchModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{__('pages.search_in'). __('pages.invoices')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::select('client_id',__('inputs.hotel_tourism'),$clients->prepend(__('inputs.select_hotel_tourism'),''),null,$errors,['required','class'=>'form-control','id'=>'client_id']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! BootForm::input('text', 'Code', Request::input('code'), __('inputs.code'), $errors, ['placeholder'=>'code...','id'=>'code']) !!}
                    </div>
                    {{--<div class="col-md-6">--}}
                    {{--<label></label>--}}
                    {{--{!! BootForm::checkbox('trashed', 'Search In Trash', 1, Request::input('trashed')) !!}--}}
                    {{--</div>--}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::input('month', 'from', Request::input('from'), __('inputs.date_from'), $errors, ['id'=>'from']) !!}
                    </div>
                    <div class="col-md-6">
                        {!! BootForm::input('month', 'to', Request::input('to'), __('inputs.date_to'), $errors, ['id'=>'to']) !!}
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="search" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> {{__('buttons.search')}}
                </button>
                <a href="{{ route('invoices.index') }}" class="btn btn-default">
                    <i class="fa fa-eraser" aria-hidden="true"></i>
                    {{__('buttons.clear')}}
                </a>
                <button type="submit" name="export" value="xls" class="btn btn-primary">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> {{__('buttons.export')}}
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i> {{__('buttons.close')}}
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $("#search").click(function () {

        $('#searchModal').modal('toggle');
        search();
    });
</script>