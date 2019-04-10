$(function(){
    $.datetimepicker.setLocale('th');

    $(".switch").bootstrapSwitch();

    var form = $('#form-submit');
    var startedDate = $('#started_date');
    var endDate = $('#end_date');

    form.click(function (event) {
        //event.preventDefault();
        if (form.valid()) {
            console.log('pass');
            return true;
        } else {
            console.log('check value');
            return false;
        }
        //return !!form.valid();
    });

    function convertDate(date) {
        var splitTime = date.split(' ');
        var splitDate = splitTime[0].split('-');
        return splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0] + ' ' + splitTime[1];
    }

    startedDate.datetimepicker({
        format: 'd-m-Y',
        timepicker: false,
        onShow: function(ct) {
            var checkDate = false;
            if (endDate.val() !== '') {
                checkDate = convertDate(endDate.val());
            }
            this.setOptions({
                maxDate: checkDate
            });
        }
    });

    endDate.datetimepicker({
        format: 'd-m-Y',
        timepicker: false,
        onShow: function(ct) {
            var checkDate = false;
            if (startedDate.val() !== '') {
                checkDate = convertDate(startedDate.val());
            }
            this.setOptions({
                minDate: checkDate
            });
        }
    });
});