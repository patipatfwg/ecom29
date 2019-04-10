<script type="text/javascript">

$.datetimepicker.setLocale('en')
var element_start   = '{{ isset($refer_start)   ? $refer_start   : ""  }}'
var element_end     = '{{ isset($refer_end)     ? $refer_end     : ""  }}'
var format          = '{{ isset($format)        ? $format        : "d/m/Y H:i:s" }}'
var formatDate      = '{{ isset($formatDate)    ? $formatDate    : "d/m/Y" }}'
var timepicker      = {{ !empty($timepicker)    ? 'true'         : 'false' }}
var defaultTime     = '{{ isset($default_start) ? $default_start : "" }}'
var minDate_start   = {{ isset($minDate_start)  ? $minDate_start : 'false' }}
var editable        = {{ !empty($editable)      ? 'true'         : 'false' }}

$( element_start ).datetimepicker({
    format       :  format,
    formatDate   :  formatDate,
    timepicker   :  timepicker,
    defaultTime: defaultTime,
    datepicker:true,
    step         :5 ,
    onShow : function( ct ) {

        this.setOptions({
            minDate: minDate_start,
            maxDate: ($( element_end ).val() != '') ? $( element_end ).val() : false
        })

    }
}).keydown(function(e) {
    return editable
})

$(element_end).datetimepicker({
    format       :  format,
    formatDate   :  formatDate,
    timepicker   :  timepicker,
    defaultTime: defaultTime,
    step         :5 ,
    onShow : function( ct ) {

        this.setOptions({
            minDate : ($( element_start ).val() != '') ? $( element_start ).val() : false
        })

    }
}).keydown(function(e) {
    return editable
})

</script>