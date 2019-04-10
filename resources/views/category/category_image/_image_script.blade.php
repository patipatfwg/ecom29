<script type="text/javascript">
    $("#form-submit").on('click', '.remove-exists', function (event) {
        
        // console.log('Remove Exists!!');
        var reference = $(this).attr("reference");
        // console.log('Before : '+ $('input:hidden[name='+reference+']').val());
        
        var input_hidden = $('input:hidden[name='+reference+']');
        input_hidden.val('');

        // console.log('After : ' + input_hidden.val());
        
        var img_display = reference + '_display';
        $( '#' + img_display).attr('src', "{{ URL::asset('/assets/images/no-img.png') }}");
        $(this).hide();
    });
</script>