<div class="modal fade" id="forSaleModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ __('pages.offered_for_sale')}}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::checkbox('sale_for[]','Drivers Only','1','',false) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! BootForm::checkbox('sale_for[]','Transfer Companies','2','',false) !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="forsale" class="btn btn-primary"> {{__('buttons.for_sale')}}
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
    $('#forsale').click(function () {
        var arr = new Array();
        var sale_for = new Array();
        var p = new Array();
        $("table tbody").find('input[name="record"]').each(function () {
            if ($(this).is(":checked")) {
                arr.push($(this).val());
                p.push($(this));
            }

        });
        $("input[name='sale_for[]']").each(function () {
            if ($(this).is(":checked")) {
                sale_for.push($(this).val());
            }
        });
        if (arr != "" && sale_for != "") {
            $.ajax({
                type: 'POST',
                headers: {
                    "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
                },
                url: '{!! route('transfers.offerForSale') !!}',
                data: {transfer_ids: arr, sale_for: sale_for},
                success: function (data) {
                    $.each(p, function (k, v) {
                        v.parent('.icheckbox_flat-blue').hide();
                        v.parents('td').append("<div style='color: red; text-transform: uppercase;font-weight: bold;'>  {{__('pages.offered_for_sale')}}</div>");

                    });
                },
                error: function (data) {
                },
            });
            $('#forSaleModal').modal('toggle');
        }
        else {
            $('#forSaleModal').modal('toggle');
            alert('No Transfer or sale for has selected');
        }
    });
</script>
