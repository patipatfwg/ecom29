<script>
function generateSlugString(text) {
    return text.toString().toLowerCase()
        // .replace(/\s+/g, '-')           // Replace spaces with -
        // .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        // .replace(/\_/g, '-')
        .replace(/[^a-zA-Z0-9]/g,'-')
        .replace(/\-\-+/g, '-')        // Replace multiple - with single -
        .replace(/^-+/, '');         // Trim - from start of text
        // .replace(/-+$/, '');            // Trim - from end of text
}

function focusOutSlugString(text) {
    return text.toString().toLowerCase()
        // .replace(/\s+/g, '-')           // Replace spaces with -
        // .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        // .replace(/\_/g, '-')
        // .replace(/[^a-zA-Z0-9]/g,'-')
        // .replace(/\-\-+/g, '-')        // Replace multiple - with single -
        // .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
}
</script>

<script>
$(document).ready(function(){

    var $slugUrl = $('.seo-url span');
        @if ( strpos(url()->current(), 'create') !== false)
            $("input[name='{{ $slug_input_name }}']").keyup(function(){
                var $slug = generateSlugString($(this).val());
                $("input[name='slug']").val($slug);
                $slug.length === 0 ? $slugUrl.html('slug') : $slugUrl.html($slug);
            });
        @endif
            $("input[name='slug']").keyup(function(){
                var $slug = generateSlugString($(this).val());
                $slug.length === 0 ? $slugUrl.html('slug') : $slugUrl.html($slug);
                $(this).val( $slug );
            }).focusout(function(){
                var $slug = focusOutSlugString($(this).val());
                $slug.length === 0 ? $slugUrl.html('slug') : $slugUrl.html($slug);
                $(this).val( $slug );
            });
        
});
</script>