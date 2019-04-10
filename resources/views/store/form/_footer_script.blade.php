<script type="text/javascript">
$(".switch").bootstrapSwitch();
$('.select2').select2({
    allowClear: true,
    placeholder: 'Select ...'
});
$('._address').on('select2:select', function (event) {
    $type   = $(this).attr('type');
    $group  = $(this).attr('group');
    $select = $('#' + $group + '_' + $type);
    $placeholder = $('#' + $group + '_' + $type).data('placeholder');

    clearSelect($group, $type);
    $.ajax({
        type: 'POST',
        url: '/store/address',
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
            if($type == 'postcode'){
                $select.val(data.postcode);
            }
            else{
                $select.select2({
                    placeholder: $placeholder,
                    allowClear: true,
                    data: data
                }).val(null).trigger('change');
            }
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
        $('#' + group + '_postcode').val("");
    } else if ($type === 'sub_district') {
        $('#' + group + '_sub_district').val(null).empty().trigger('change');
        $('#' + group + '_postcode').val("");
    } else if ($type === 'postcode') {
        $('#' + group + '_postcode').val("");
    }
}

$("#select-region").select2({
    minimumResultsForSearch: -1,
    placeholder: 'Select Zone...'
});

$('#select-region').on('select2:select', function (evt) {
    var region = $("#select-region option:selected").val();
    $("#region").val(region);
});

var url = '/store';
var store_id = '{{ isset($id) ? $id : "" }}';

var createConfig = {
    form: $('#form-submit'),
    url: url,
    httpMethod: 'POST',
    successCallback: function() {
        window.location = url
    }
}
var updateConfig = {
    form: $('#form-submit'),
    url: url + '/' + store_id,
    httpMethod: 'PUT',
    successCallback: function() {
        location.reload();
    }
}

var config = (store_id === '') ? createConfig : updateConfig; 
validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);
</script>
{!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}