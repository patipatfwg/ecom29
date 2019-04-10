<script type="text/javascript">
function validateAndSubmit(form, url, httpMethod, successCallback, beforeCallback = null) {
  var checkClick = false;

  $(form).on('click', '.btn-submit', function (event) {
      event.preventDefault();

      if(beforeCallback != null){
          beforeCallback();
      }

      if ($(form).valid()) {
          if (checkClick) {
              return false;
          }

          checkClick = true;

          callAjax(httpMethod, url, $(form).serialize(), null, successCallback, function(){
              checkClick = false;
          });
      }
  });
}
</script>
@include('common._call_ajax')