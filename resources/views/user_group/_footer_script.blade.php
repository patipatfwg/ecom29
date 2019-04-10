{!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
{!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
{!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}

@include('common._validate_form_script')

<script type="text/javascript">
var url = '/user_group';
var userGroupId = '{{ isset($userGroupId) ? $userGroupId : "" }}';

var createConfig = {
    form: $('#form-submit'),
    url: url,
    httpMethod: 'POST',
    successCallback: function() {
        window.location = url
    }
}
var updateConfig = {
    form: $('#form-submit'),
    url: url + '/' + userGroupId,
    httpMethod: 'PUT',
    successCallback: function() {
        window.location = url + '/' + userGroupId + '/edit'
    }    
}

var config = (userGroupId === '') ? createConfig : updateConfig; 
validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);
</script>

{!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}

<script>
$(document).ready(function() {
  $(".checkAll").attr("data-type", "check");
  $(".checkAll").click(function() {
    var data = $(this).attr('data-group');
    if ($(".checkAll").attr("data-type") === "check") {
      $('[id^="permission['+data+']"]').prop("checked", true);
    }
  })
});

function autoCheck(menu_key,sub_menu_key)
{
  if( $('[id^="permission['+menu_key+']['+sub_menu_key+'][write]"]').is(':checked') == true)
    $('[id^="permission['+menu_key+']['+sub_menu_key+'][read]"]').prop('checked', true);
}

function autoUnCheck(menu_key,sub_menu_key)
{
  if( $('[id^="permission['+menu_key+']['+sub_menu_key+'][read]"]').is(':checked') == false)
    $('[id^="permission['+menu_key+']['+sub_menu_key+'][write]"]').prop('checked', false);
}

$(document).ready(function() {
  $(".uncheckAll").attr("data-type", "uncheck");
  $(".uncheckAll").click(function() {
    var data = $(this).attr('data-group');
    if ($(".uncheckAll").attr("data-type") != "check") {
      $('[id^="permission['+data+']"]').prop("checked", false);
    }
  })
});

$(".switch").bootstrapSwitch();
</script>