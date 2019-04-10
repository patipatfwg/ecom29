<script type="text/javascript">
$(function() {

    var currentRequest = null;

    $('input#product-relate-input').on('keyup', function() {

        var self = $(this);
        self.parent().removeClass('input-group').find('div.input-group-addon').remove();

        if (currentRequest !== null) {
            currentRequest.abort();
        }

        $('#product-relate-list').addClass('hide').find('div.product-relate-list-detail div').remove();

        if (self.val().trim()) {
            currentRequest = $.ajax({
                method: 'POST',
                url: $("meta[name='root-url']").attr('content') + '/product/relate/search',
                data: {
                    text: self.val()
                },
                beforeSend: function() {
                    self.parent().addClass('input-group').append('<div class="input-group-addon"><i class="icon-spinner2 spinner"></i></div>');
                },
                success: function(data) {
                    self.parent().find('.icon-spinner2').removeClass('icon-spinner2 spinner').addClass('icon-cancel-circle2');
                    productRelate(data);

                    $('#product-relate-list').parent().find('.input-group-addon').on('click', function (e) {
                        e.preventDefault();
                        self.val('');
                        $(this).parent().removeClass('input-group').find('div.input-group-addon').remove();
                        $('#product-relate-list').addClass('hide').find('div.product-relate-list-detail div').remove();
                    });
                },
                error: function(data) {

                }
            });
        }
    });

    $('body').on('click', 'input.control-info', function () {
        if ($(this).is(':checked')) {
            $('#product-relate-table tbody tr[data-id="no-data"]').remove();
            $('#product-relate-table tbody').append('<tr data-id="' + $(this).data('id') + '"><td>#</td><td><input name="product_relate[]" value="' + $(this).data('mongo') + '" type="hidden">' + $(this).data('id') + '</td><td>' + $(this).data('th') + '</td><td>' + $(this).data('en') + '</td><td class="text-center"><a class="product-relate-delete"><i class="icon-trash text-danger"></a></td></tr>');
        } else {
            $('#product-relate-table tbody tr[data-id="' + $(this).data('id') + '"]').remove();
            if ($('#product-relate-table tbody tr').length === 0) {
                $('#product-relate-table tbody').append('<tr data-id="no-data"><td class="text-center" colspan="5">No data.</td></tr>');
            }
        }

        productRelateNumber();
    });

    $('body').on('click', 'a.product-relate-delete', function (event) {
        event.preventDefault();
        $(this).parents('tr').remove();
        $('input[data-id="' + $(this).parents('tr').data('id') + '"]').prop('checked', false).uniform('refresh');
        if ($('#product-relate-table tbody tr').length === 0) {
            $('#product-relate-table tbody').append('<tr data-id="no-data"><td class="text-center" colspan="5">No data.</td></tr>');
        }

        productRelateNumber();
    });

    /* $('#product-relate-list, #product-relate-input').click(function(event) {
        event.stopPropagation();
    });

    $('body').click(function() {
        $('#product-relate-list').addClass('hide').find('div.product-relate-list-detail div').remove();
    }); */
});

function productRelateNumber() {
    $('#product-relate-table tbody tr').each(function(i, items) {
        $(this).find('td').eq(0).text(i+1);
    });
}

function productRelate(data) {
    var appendRow = $('#product-relate-list').removeClass('hide').find('div.product-relate-list-detail');
    if (data.length > 0) {
        appendRow.append(data.map(function(sObj) {
            var isChecked = $('#product-relate-table tbody tr').is('[data-id="' + sObj.item_id + '"]') === true ? 'checked=checked' : '';
            return '<div class="product-relate-list-row"><input data-mongo="' + sObj.id + '" data-id="' + sObj.item_id + '" data-th="' + sObj.name_th + '" data-en="' + sObj.name_en + '" ' + isChecked + ' type="checkbox" class="control-info"><span>[' + sObj.item_id + '] ' + sObj.name_th + ' | ' + sObj.name_en + '</span></div>'
        }));
    } else {
        appendRow.append('<div class="product-relate-list-row text-danger">No data.</div>');
    }

    $('input.control-info').uniform({
        radioClass: 'choice',
        wrapperClass: 'border-info-600 text-info-800'
    });
}
</script>