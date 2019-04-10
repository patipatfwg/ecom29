var date = new Date();
var year = date.getFullYear();

$.datetimepicker.setLocale('th');
$('#birth_day').datetimepicker({
    timepicker: false,
    format: 'd-m-Y',
    maxDate: 0,
    yearEnd: year
});

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

$("form").on("click", ".btn" ,function(event){

    event.preventDefault();

    $(this).button("loading");
    var form = $(this).closest("form");

    var myButton = $(this);

    var id = form.attr('id');
    var url = form.attr('action');
    var method = form.attr('method');
    var data = form.serialize();

    form.valid();

    callAjax(method, url, data, null, function(){
        // Reload
        location.reload();
    },
    null,
    function(){
        myButton.button('reset');
    });
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

$("document").ready(function(){
    $("form#form-profile #phone, form#form-profile #email").focusout(function(){
        var thisId = $(this).attr('id');
        var thisVal = $(this).val().trim();
        var optId = (thisId == 'phone') ? 'email' : 'phone';
        var optVal = $("#" + optId).val().trim();
        if(thisVal == '' && optVal != ''){
            $(this).parent().removeClass('has-error has-success');
        } else if(thisVal != '' && optVal == ''){
            $("#" + optId).parent().removeClass('has-error has-success');
        }
    });
});

