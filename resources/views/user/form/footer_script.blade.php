<script>

    $('.select2').select2({
        allowClear: false,
        placeholder: 'Select Store'
    });

    var groupList = [];
    @if(isset($id))
        @foreach($userData['authorize'] as $role)
            groupList.push("{{ $role['id'] }}");
        @endforeach
    @endif

    function addGroup(name, id) {
        if (id == '') {
            return false;
        }
        if (jQuery.inArray(id, groupList) != -1) {
            return false;
        }
        groupList.push(id);
        groupList.sort();
        if ($("#userGroupTextBox").val() == '') {
            $("#userGroupTextBox").val(id);
        } else {
            $("#userGroupTextBox").val(groupList.toString());
        }
        $("#groupRow").prepend("<span class=\"tag label label-info\">" + name + "<span data-role=\"remove\" onclick=\"remove(this,'" + id + "')\"></span></span>");
    }

    function remove(obj, id) {
        groupList = jQuery.grep(groupList, function(value) {
            return value != id;
        });
        $(obj).parents("span").remove();
        $("#userGroupTextBox").val(groupList.toString());
    }

    $('#saveNewGroup').click(function() {
        addGroup($('#userGroupSelect option:selected').text(), $('#userGroupSelect option:selected').val());
    });
</script>

<script type="text/javascript">
var userId = '{{ isset($id) ? $id : "" }}';
var createConfig = {
    form: $('#form-submit'),
    url: '/user',
    httpMethod: 'POST',
    successCallback: function() {
        window.location = '/user'
    }
}
var updateConfig = {
    form: $('#form-submit'),
    url: '/user/' + userId,
    httpMethod: 'POST',
    successCallback: function() {
        window.location = '/user/' + userId + '/edit'
    }    
}
var config = (userId === '') ? createConfig : updateConfig;

validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);

function validateAndSubmit(form, url, httpMethod, successCallback) {
        var checkClick = false;

        $(form).on('click', '.btn-submit', function (event) {
            event.preventDefault();


                checkClick = true;
                var formData = new FormData(document.getElementById("form-submit"));
     
                formData.append('', 'PUT');
                @if(isset($userData))
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
            }
        );
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

        function onAjaxError() {
        swal("{{ trans('validation.create.title') }}", "{{ trans('validation.error_connection') }}", 'error');
            }

            function getCheckedId() {
            var ids = $('.ids:checked').serializeArray();
            return ids.map(function(elem) {
                return elem.value;
            }).join();
        }

            $('#myModalP').on('click', function (e) {
                    $("#userGroupSelect").val('');
                    $("#userGroupSelect").selectpicker("refresh");
            });
    </script>
