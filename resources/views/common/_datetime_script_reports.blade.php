<script type="text/javascript">

    $.datetimepicker.setLocale('en');

    $('{{ $start }}').datetimepicker({
        format: 'Y-m-d H:i',
        defaultTime: '17:00',
        onShow: function(ct) {
            var checkDate = false;
            if ($('{{ $end }}').val() != '') {
                checkDate = convertDate($('{{ $end }}').val());
            }

            @if (isset($isStart))
                this.setOptions({ minDate: false, maxDate: checkDate });
            @else
                this.setOptions({ minDate: checkDate, maxDate: false });
            @endif
        }
    }).keydown(function(e) {
        if (e.keyCode == 8) {
            return true;
        } else {
            return false;
        }
    });

    function convertDate(date) {
        var splitTime = date.split(' ');
        var splitDate = splitTime[0].split('-');
        return splitDate[2] + '/' + splitDate[1] + '/' + splitDate[0] + ' ' + splitTime[1];
    }
</script>