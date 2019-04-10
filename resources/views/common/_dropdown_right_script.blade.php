<script type="text/javascript">
$('.right-language').on('change', function(){
     var taget = $('#tab-panel-'+$('.right-language').val());

                $('.tab-pane').removeClass('active');
                $('.tab-pane').removeClass('in');
                $('.tab-pane').css('opacity',0);
                taget.addClass('active')


                taget.fadeTo( "slow" , 1, function() {
                    taget.addClass('in')
                });                   

});
</script>   