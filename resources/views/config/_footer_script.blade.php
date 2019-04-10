<script type="text/javascript" src="/assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switch.min.js"></script>
<script type="text/javascript" src="/assets/js/core/libraries/jquery_ui/full.min.js"></script>

@include('common._seo_script')
@include('common._call_ajax')

<script type="text/javascript">
    var appUrl = '{{ $appUrl }}';
    var checkClick = false;

    var validateData = {
        init: function () {
            var _self = this;
            $('#form-submit').on('click', '#save-status', function (e) {
                e.preventDefault();
                _self.validate();
            });
        },
        validate: function () {
            var dataOld = "{{ base64_encode(json_encode($configs)) }}";
            var dataNew = JSON.stringify($('#form-submit').serializeArray());
                $.ajax({
                    type: 'PUT',
                    url: appUrl,
                    data: {dataOld:dataOld,dataNew:dataNew},
                    dataType: 'json',
                    success: function(data) {
                        if (data.status || data.success) {
                            onAjaxSuccess(data, function(){
                                window.location = "/config/payment_method";
                            });
                        } 
                        else {
                            onAjaxFail(data);
                        }
                    },
                    error: onAjaxError      
                });
        },
    }

    $(".switch").bootstrapSwitch();
    validateData.init();
</script>