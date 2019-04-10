<script>
jQuery(document).ready(function() {
    var error_message = '';
    $("<?php echo $validator['selector']; ?>").validate({
        errorElement: "span",
        errorClass: "help-block error-help-block",
        errorPlacement: function(r, e) {
        	if (e.parent('.input-group').length || e.prop('type') === 'checkbox' || e.prop('type') === 'radio') {
                $('#' + $(r).attr('id')).remove();
                r.insertAfter(e.closest('div[class*="col-md-"]').children().last());
        	} else if(e.prop('type') === 'file'){
                $('#file-error').text(r.text());
            } else if (e.hasClass('select2')) {
                r.insertAfter(e.parent().children().last());
        	} else {
                $('#' + $(r).attr('id')).remove();
        		r.insertAfter(e);
        	}
        },
        highlight: function(r, e) {
            $(r).closest(".form-group").addClass("has-error");
        },
        onkeyup: function(element, event){
            $(element).valid();
        },
        success: function(r) {
            $(r).closest(".form-group").removeClass("has-error").addClass("has-success")
            error_message = '';
        },
        focusInvalid: false,
        submitHandler: function(r) {
            $("<?php echo $validator['selector']; ?> button.loading").button("loading"), r.submit()
        },
        rules: <?php echo json_encode($validator['rules']); ?>,
        ignore: []
    })
});
</script>