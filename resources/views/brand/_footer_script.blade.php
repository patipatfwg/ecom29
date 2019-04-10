<script type="text/javascript" src="/assets/js/plugins/forms/tags/tagsinput.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switch.min.js"></script>
<script type="text/javascript" src="/assets/js/core/libraries/jquery_ui/full.min.js"></script>

@include('brand.form._image_script')
@include('common._seo_script')
@include('common._call_ajax')

<script type="text/javascript">
    var appUrl = '{{ $appUrl }}';
    var redirectUrl = '{{ $redirectUrl }}';
    var checkClick = false;

    var validateData = {
        init: function () {
            var _self = this;
            $('#form-submit').on('click', '.btn-submit', function (e) {
                e.preventDefault();
                _self.validate();
            });
        },
        validate: function () {
            var formData = new FormData(document.getElementById("form-submit"));
            var dataImage = '';
            // if ($('#form-submit').valid()) {
                $.ajax({
                    type: 'POST',
                    url: appUrl,
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.status || data.success) {
                            onAjaxSuccess(data, function(){
                                window.location = redirectUrl;
                            });
                        } 
                        else {
                            onAjaxFail(data);
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        $.each(data.responseJSON,function(key,value){
                            dataImage += value + "\n";
                        });
                        swal('{{ trans('validation.create.fail') }}', dataImage, 'warning');
                    }            
                });
        },
    }

    $(".switch").bootstrapSwitch();
    validateData.init();
</script>