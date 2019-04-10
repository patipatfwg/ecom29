<script type="text/javascript">
    $('body').on('click', '.dropdown-item', function(event) {
        event.preventDefault();
        var id = $(this).attr('value');
        var group = $(this).attr('group-name');

        if($(this).attr('display-text') != null){
            var str = $(this).attr('display-text');
            $('.dropdown-selected[group-name="'+group+'"]').text(str);
            $('.dropdown-input[group-name="'+group+'"]').val(id); 
        }
        else{
            var str = $(this).text();
            $('.dropdown-selected[group-name="'+group+'"]').text(str);
            $('.dropdown-input[group-name="'+group+'"]').val(id); 
        }

        // console.log(group);
        
        //     if(group == 'product'){
        //         $('.dropdown-selected[group-name="business"]').find('ul').hide();
        //     }else{
        //         $('.dropdown-selected[group-name="product"]').find('ul').hide();
        //     }
    });

    $('body').on('click', '.dropdown-child a.dropdown-parent', function(event) {
        $(this).next('ul').toggle();
        event.stopPropagation();
        event.preventDefault();
    });

    $(function(){
        $('input.dropdown-input').each(function(){
            var group = $(this).attr('group-name');

            var str = $(this).siblings('.dropdown-menu').find('a[selected]').text();
            var id = $(this).siblings('.dropdown-menu').find('a[selected]').attr('value');

            if(str && id){
                $('.dropdown-selected[group-name="'+group+'"]').text(str);
                $('.dropdown-input[group-name="'+group+'"]').val(id);

            }
        })
    });

    

</script>