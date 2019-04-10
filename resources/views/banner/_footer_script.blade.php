<script type="text/javascript">
var bannerId = '{{ isset($bannerId) ? $bannerId : "" }}';
var createConfig = {
    form: $('#form-submit'),
    url: '/banner',
    httpMethod: 'POST',
    successCallback: function() {
        window.location = '/banner'
    }
}
var updateConfig = {
    form: $('#form-submit'),
    url: '/banner/'+bannerId,
    httpMethod: 'POST',
    successCallback: function() {
         location.reload();
    }
}

var config = (bannerId === '') ? createConfig : updateConfig;
validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);

function validateAndSubmit(form, url, httpMethod, successCallback)
{
    var checkClick = false;

    $(form).on('click', '.btn-submit', function (event) {
        event.preventDefault();
        if (checkClick) {
            return false;
        }

        checkClick = true;
        var formData = new FormData(document.getElementById('form-submit'));

        formData.append('', 'PUT');
         @if(isset($bannerData))
            formData.append('_method', 'PUT');
        @endif
        callAjax(httpMethod, url, formData, null, successCallback, function(){
            checkClick = false;
        });
        
    });
}
</script>

<script type="text/javascript">
function callAjax(type, url, data, successCallback = null, postSuccessCallback = null, completeCallback = null, postFailCallback = null) {
    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType: 'json',
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.status || data.success) {
                if(successCallback) {
                    successCallback();
                }
                onAjaxSuccess(data, postSuccessCallback);
            } else {
                onAjaxFail(data, postFailCallback);
            }
        },
        error: function(data) {
            if(data.responseJSON.expired) {
                swal({
                    title: "Error!",
                    text: 'Session Expired',
                    type: "error",
                    confirmButtonText: "OK"
                    },
                    function(){
                        window.location.href = window.location.href;
                    }
                );
            } else {
                var dataValidation = '';
                $.each(data.responseJSON,function(key,value){
                    dataValidation += value + "\n";
                });
                swal('{{ trans('validation.create.fail') }}', dataValidation, 'warning');
            }
        },
        complete: function() {
            if(completeCallback) {
            completeCallback();
            }
        }
    });
}

function onAjaxSuccess(data, callback = null) {
    swal({
        title: "{{ trans('validation.create.title') }}",
        text: data.messages,
        type: "success",
        confirmButtonText: "{{ trans('validation.btn_ok') }}"
        },
        callback
    );
}

function onAjaxFail(data, callback = null) {
    swal({
        title: "{{ trans('validation.create.fail') }}",
        text: data.error ? data.error : data.messages,
        type: "warning",
        confirmButtonText: "{{ trans('validation.btn_ok') }}"
        },
        callback
    );
}

function getCheckedId() {
    var ids = $('.ids:checked').serializeArray();
    return ids.map(function(elem) {
        return elem.value;
    }).join();
}

$(document).ready(function() {
    $('.btn.fileupload-exists').on('click',function(){
        if($('#thumb_tmp').val() != ''){
            $("input[name='thumb_old']").val($('#thumb_tmp').val());
            $('.thumb_old').attr('src',$('#thumb_tmp').val());
            //$('#thumb-error').attr('class', '');    
        } else {
            $("input[name='thumb_old']").val('');
            $('.thumb_old').attr('src',"{{ URL::asset('/assets/images/no-img.png') }}");
        }
    });
    suggestion = {
        'A1': 'Size 285x380 pixel, .jpg or .png file format only',
        'A2': 'Size 380x190 pixel, .jpg or .png file format only',
        'A3': 'Size 190x190 pixel, .jpg or .png file format only',
        'A4': 'Size 190x190 pixel, .jpg or .png file format only',
        'A5': 'Size 190x190 pixel, .jpg or .png file format only',
        'A6': 'Size 190x190 pixel, .jpg or .png file format only',
    };
    if ($("select[name='position']").val() != ''){
        $("#image-format").text(suggestion[$("select[name='position']").val()]);
    }
    else {
        $("#image-format").text('.jpg and .png file format only');
    }       
    $("select[name='position']").on('change', function(){
        if ($("select[name='position']").val() != ''){
            $("#image-format").text(suggestion[$("select[name='position']").val()]);
        }
        else {
            $("#image-format").text('.jpg and .png file format only');
        }
    })
    $(".select-dropdown").select2({
        minimumResultsForSearch: -1
    });

    if (!!window.performance && window.performance.navigation.type === 2) {
        window.location.reload();
    }
});
</script>
