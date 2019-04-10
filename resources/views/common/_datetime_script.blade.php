<script type="text/javascript">

    $.datetimepicker.setLocale('en');

    $("{{ $refer }}").datetimepicker({
        format: "{{ isset($format)? $format : 'd-m-Y' }}",
        scrollInput: false,
        timepicker: {{ isset($timepicker)? 'true' : 'false' }}
    });
    
</script>