<script type="text/javascript">
"use strict";
$.datetimepicker.setLocale('en');

var date  = moment();
var month = ('0' + (date.month() + 1)).slice(-2);
var year  = date.year();

$("{{ $refer_start }}").datetimepicker({
    yearStart: year - 3,
    yearEnd: parseInt(year) + 1,
    defaultDate: new Date(),
    format: "{{ isset($format_start)? $format_start : 'd M Y' }}",
    formatDate: "{{ isset($format_start)? $format_start : 'd M Y' }}",
    scrollInput: false,
    timepicker: "{{ isset($timepicker)? $timepicker : false }}",
    @if(isset($default_start))
    defaultTime: "{{ isset($default_start)?$default_start:'' }}",
    @endif
    onShow: function(ct) {
        var checkDate = false;
        var checkminDate = false;

        if ($("{{ $refer_end }}").val() != '') {
            checkDate = $("{{ $refer_end }}").val();
        }

        @if(isset($start_minDate))
            checkminDate = "{{$start_minDate}}";
        @endif

        this.setOptions({
            maxDate: checkDate,
            minDate: checkminDate
        })
    },
    onClose: function(current_time, $input) {
        var timefixed = "{{ isset($timefixed)? $timefixed : true }}";
        @if(!empty($timepicker))
        var start  = checkDateTime('{{ $refer_start }}');
        var end    = checkDateTime('{{ $refer_end }}');
        var result = ('0' + (date.date())).slice(-2) + '/' + month + '/' + year + ' 00:00';
        @else
        var start  = checkDateTime('{{ $refer_start }}');
        var end    = checkDateTime('{{ $refer_end }}');
        var result = ('0' + (date.date())).slice(-2) + '/' + month + '/' + year;
        @endif

        //<input required>
        if (((isNaN(start) && $input.val() != '')
            || (isNaN(start) && $input.attr('required'))
            || (start < 0 && $input.val() != '')
            || (start > end)
        ) && timefixed) {
            $('#' + $input.attr('id')).val(result);
        }
    }
}).keydown(function(e) {
    return "{{ (isset($editable) && $editable)? 'true': 'false'}}"
});

$("{{ $refer_end }}").datetimepicker({
    yearStart: year - 3,
    yearEnd: parseInt(year) + 1,
    format: "{{ isset($format_end)? $format_end : 'd M Y' }}",
    formatDate: "{{ isset($format_start)? $format_start : 'd M Y' }}",
    scrollInput: false,
    timepicker: "{{ isset($timepicker)? $timepicker : false }}",
    @if(isset($default_end))
    defaultTime: '{{ $default_end }}',
    @endif
    onShow: function(ct) {
        var checkDate = false;
        if ($("{{ $refer_start }}").val() != '') {
            checkDate = $("{{ $refer_start }}").val();
        }
        this.setOptions({
            minDate: checkDate
        })
    },
    onClose: function(current_time, $input) {
        var timefixed = "{{ isset($timefixed)? $timefixed : true }}";
        @if(!empty($timepicker))
        var start  = checkDateTime('{{ $refer_start }}');
        var end    = checkDateTime('{{ $refer_end }}');
        var result = ('0' + (date.date())).slice(-2) + '/' + month + '/' + year + ' 23:59';
        @else
        var start  = checkDate('{{ $refer_start }}');
        var end    = checkDate('{{ $refer_end }}');
        var result = ('0' + (date.date())).slice(-2) + '/' + month + '/' + year;
        @endif

        //<input required>
        if (((isNaN(start) && $input.val() != '')
            || (isNaN(start) && $input.attr('required'))
            || (start < 0 && $input.val() != '')
            || (start > end)
        ) && timefixed) {
            $('#' + $input.attr('id')).val(result);
        }
    }
}).keydown(function(e) {
    return {{ (isset($editable) && $editable)? 'true': 'false'}}
});

function checkDateTime(date) {
    var time  = $(date).val().split(' ');
    var split = time[0].split('/');
    return moment(split[2] + '-' + split[1] + '-' + split[0] + ' ' + time[1]).valueOf();
}
function checkDate(date) {
    var $date = $(date).val().split('/');
    return moment($date[2] + '-' + $date[1] + '-' + $date[0]).valueOf();
}
</script>