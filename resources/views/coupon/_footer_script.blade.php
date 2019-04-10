<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switch.min.js"></script>
<script type="text/javascript" src="/assets/js/core/libraries/jquery_ui/full.min.js"></script>
@include('common._validate_form_script')


<script type="text/javascript">
var couponId = '{{ isset($couponId) ? $couponId : "" }}';
var createConfig = {
    form: $('#form-submit'),
    url: '/coupon',
    httpMethod: 'POST',
    successCallback: function() {
        window.location = '/coupon'
    }
}

var updateConfig = {
    form: $('#form-submit'),
    url: '/coupon/' + couponId,
    httpMethod: 'PUT',
    successCallback: function() {
        location.reload()
    }
}

var config = (couponId === '') ? createConfig : updateConfig; 
validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);

</script>

<script type="text/javascript">
$(document).ready(function() {
    var path = "{{ url('coupon/product') }}";
    $('input.typeahead').typeahead({
        source:  function (query, process) {
        return $.get(path, { query: query }, function (data) {
                return process(data);
            });
        },
        afterSelect: function (item) {
            $('#product_id').val(item.id);
        }
    });

    $( "#discount_type" ).change(function() {
        switch ($('#discount_type').val()) {
            case 'cart discount' :
                $(".row-product").css('display', 'none');
                $(".row-cart_threshold").fadeIn('slow');
                break;
            case 'product discount' : 
                $(".row-cart_threshold").css('display', 'none');
                $(".row-product").fadeIn('slow');
                break;
        }
        
    });
    $( "#discount_type" ).ready(function() {
        switch ($('#discount_type').val()) {
            case 'cart discount' :
                $(".row-product").css('display', 'none');
                $(".row-cart_threshold").fadeIn('slow');
                break;
            case 'product discount' : 
                $(".row-cart_threshold").css('display', 'none');
                $(".row-product").fadeIn('slow');
                break;
        }
        
    });
    $(".row-product").css('display', 'none');
    
    $(".select-dropdown").select2({
        minimumResultsForSearch: -1
    });
    $(".switch").bootstrapSwitch();
});
</script>
