<script>
"use strict";
$.datetimepicker.setLocale('th');
$('#start_date').datetimepicker({
    format: 'Y-m-d',
    timepicker: false, 
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
    format: 'Y-m-d',
    timepicker: false, 
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
</script>