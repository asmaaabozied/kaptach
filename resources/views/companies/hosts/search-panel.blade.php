<div class="modal fade" id="searchModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{__('pages.search_in'). __('pages.hosts')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::input('text', 'phone', null, __('inputs.phone'), $errors, ['class'=>'form-control','id'=>'phone']) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="search" class="btn btn-primary"><i class="fa fa-search"
                                                               aria-hidden="true"></i> {{__('buttons.search')}}
                </button>
                <a href="{{ route('hosts.index') }}" class="btn btn-default">
                    <i class="fa fa-eraser" aria-hidden="true"></i>
                    {{__('buttons.clear')}}
                </a>
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