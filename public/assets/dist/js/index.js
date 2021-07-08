$(function() {
//confirm dialog
    $("#main-content").on("click", ".confirmed", function (e, flag) {
        if (flag) {
            return true;
        }
        e.preventDefault();
        var btn = $(this);
        confirmDialog(btn.data('confirm-message'), function () {
            btn.trigger('click', [true]);
        })
    });
    // $('#example1').DataTable().language.url("https://cdn.datatables.net/plug-ins/1.10.20/i18n/Turkish.json");
});
function confirmDialog(message, callback) {
    bootbox.confirm({
        title: "Confirm",
        message: message,
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if (result) {
                //categories/{id}/delete
                callback();

            }
        }
    });
}

(function ($, DataTable) {

    // Datatable global configuration
    $.extend(true, DataTable.defaults, {
        language: {
            "sDecimal":        ",",
            "sEmptyTable":     "Tabloda herhangi bir veri mevcut değil",
            "sInfo":           "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
            "sInfoEmpty":      "Kayıt yok",
            "sInfoFiltered":   "(_MAX_ kayıt içerisinden bulunan)",
            "sInfoPostFix":    "",
            "sInfoThousands":  ".",
            "sLengthMenu":     "Sayfada _MENU_ kayıt göster",
            "sLoadingRecords": "Yükleniyor...",
            "sProcessing":     "İşleniyor...",
            "sSearch":         "Ara:",
            "sZeroRecords":    "Eşleşen kayıt bulunamadı",
            "oPaginate": {
                "sFirst":    "İlk",
                "sLast":     "Son",
                "sNext":     "Sonraki",
                "sPrevious": "Önceki"
            },
            "oAria": {
                "sSortAscending":  ": artan sütun sıralamasını aktifleştir",
                "sSortDescending": ": azalan sütun sıralamasını aktifleştir"
            },
            "select": {
                "rows": {
                    "_": "%d kayıt seçildi",
                    "0": "",
                    "1": "1 kayıt seçildi"
                }
            }
        }
    });

})(jQuery, jQuery.fn.dataTable);