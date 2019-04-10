<script>
$(document).ready(function(){
  $.ajax({
    method: 'GET',
    url: '/login-form',
    dataType: 'html',
    cache: false,
    success: function(data) {
      $('#login-form').html(data);
    }
  })
})
</script>