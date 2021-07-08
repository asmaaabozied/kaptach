<style>
    .delete{
        color: red;
    }
    .tools{
        float: right;
    }

</style>
<div class="modal fade" id="exchangeModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ __('pages.exchange')}}</h4>
            </div>
            <div class="modal-body">
                <div class="box box-primary append" style="background: #f4f4f4;">
                    <div class="row">

                    </div>
                    <div class="row">

                    </div>
                    <div class="row">

                    </div>
                
                </div>
                <div class="appendto"></div>
                <div class="row">
                    <div class="col-md-6" style="margin-top: 10px;">
                        <a href="#" class="btn btn-default" id="add_more_attributes">Add more</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="forsale" class="btn btn-primary"> {{__('buttons.exchange')}}
                </button>
                <a href="{{ route('transfers.index') }}" class="btn btn-default">
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

</script>
