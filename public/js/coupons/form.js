$.datetimepicker.setLocale('th');
$('#birth_day').datetimepicker({
    timepicker: false,
    format: 'd-m-Y'
});

$('.select2').select2({
    placeholder: 'Select ...',
    allowClear: true
});

$('._address').on('select2:select', function (event) {

    $type   = $(this).attr('type');
    $group  = $(this).attr('group');
    $select = $('#' + $group + '_' + $type);
    clearSelect($group, $type);

    $.ajax({
        type: 'POST',
        url: $("meta[name='root-url']").attr('content') + '/member/address',
        data: {
            type: $type,
            id: $(this).val()
        },
        dataType: 'json',
        beforeSend: function(xhr) {
            $('#select2-' + $group + '_' + $type + '-container')
                .next('span.select2-selection__arrow')
                .append('<i class="icon-spinner2 spinner select2-icon-spinner"></i>');
        },
        success: function(data) {
            $select.select2({
                placeholder: 'Select ...',
                allowClear: true,
                data: data
            }).val(null).trigger('change');
        }
    });
});

$('._address').on('select2:unselect', function (event) {
    $type  = $(this).attr('type');
    $group = $(this).attr('group');
    clearSelect($group, $type);
});

function clearSelect(group, type) {
    if (type === 'districts') {
        $('#' + group + '_districts').val(null).empty().trigger('change');
        $('#' + group + '_sub_district').val(null).empty().trigger('change');
    } else if ($type === 'sub_district') {
        $('#' + group + '_sub_district').val(null).empty().trigger('change');
    }
}