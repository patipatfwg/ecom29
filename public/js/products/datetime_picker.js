"use strict";
$.datetimepicker.setLocale('th');
$('#start_date').datetimepicker({
    format: 'Y-m-d H:i',
    scrollInput: false,
    onShow: function(ct) {
        var checkDate = false;
        if ($('#end_date').val() != '') {
            checkDate = $('#end_date').val();
        }
        this.setOptions({
            maxDate: checkDate
        })
    }
});

$('#end_date').datetimepicker({
    format: 'Y-m-d H:i',
    scrollInput: false,
    onShow: function(ct) {
        var checkDate = false;
        if ($('#start_date').val() != '') {
            checkDate = $('#start_date').val();
        }
        this.setOptions({
            minDate: checkDate
        })
    }
});

$('#start_date-intermediate').datetimepicker({
    format: 'Y-m-d 00:00:00',
    scrollInput: false,
    timepicker: false,
    onShow: function(ct) {
        var checkDate = false;
        if ($('#end_date-intermediate').val() != '') {
            checkDate = $('#end_date-intermediate').val();
        }
        this.setOptions({
            maxDate: checkDate
        })
    }
});

$('#end_date-intermediate').datetimepicker({
    format: 'Y-m-d 23:59:59',
    scrollInput: false,
    timepicker: false,
    onShow: function(ct) {
        var checkDate = false;
        if ($('#start_date-intermediate').val() != '') {
            checkDate = $('#start_date-intermediate').val();
        }
        this.setOptions({
            minDate: checkDate
        })
    }
});