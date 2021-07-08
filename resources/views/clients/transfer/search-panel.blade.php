<div class="modal fade" id="searchModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{__('pages.search_in'). __('pages.transfers')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{--<div class="col-md-6">--}}
                        {{--{!! BootForm::input('text', 'key', Request::input('key'), 'Key', $errors, ['placeholder'=>'key...']) !!}--}}
                    {{--</div>--}}
                    {{--<div class="col-md-6">--}}
                        {{--<label></label>--}}
                        {{--{!! BootForm::checkbox('trashed', 'Search In Trash', 1, Request::input('trashed')) !!}--}}
                    {{--</div>--}}
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::input('text', 'from', Request::input('from'),  __('inputs.date_from'), $errors, ['class'=>'datepicker','id'=>'from','data-date-format'=>"yyyy-mm-dd"]) !!}
                    </div>
                    <div class="col-md-6">
                        {!! BootForm::input('text', 'to', Request::input('to'), __('inputs.date_to'), $errors, ['class'=>'datepicker','id'=>'to','data-date-format'=>"yyyy-mm-dd"]) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {!! BootForm::select('request_status', __('inputs.requested_status'), [''=>__('pages.select'). __('pages.status'),'0'=>__('pages.pending'),'1'=>__('pages.approved')], Request::input('request_status'),$errors,['id'=>'request_status']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="search" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i>  {{__('buttons.search')}}
                </button>
                <a href="{{ route('clients.transfers.index') }}" class="btn btn-default">
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
        if ($('#from').val() == '' && $('#to').val() == '') {
            $('#date-search').show();
        } else {
            $('#date-search').hide();
            $('#datepicker').val('');
        }     search()
    });
</script>