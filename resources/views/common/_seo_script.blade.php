<script type="text/javascript">
    $( document ).ready(function(){

        // seo subject
        $("#subjectInputName{{ isset($id)? '-'.$id : '' }}").keyup(function () {
            $("#seo_subject_show{{ isset($id)? '-'.$id : '' }}").text($("#subjectInputName{{ isset($id)? '-'.$id : '' }}").val());
            if ($("#subjectInputName{{ isset($id)? '-'.$id : '' }}").val() == '') {
                  $("#seo_subject_show{{ isset($id)? '-'.$id : '' }}").text("This is an Example of a Title Tag that is Eighty Characters in Length");
            }
        });

        // seo explanation
        $("#explanationInput{{ isset($id)? '-'.$id : '' }}").keyup(function(){
            var text = $("#explanationInput{{ isset($id)? '-'.$id : '' }}").val();
            $("#seo_explanation_show{{ isset($id)? '-'.$id : '' }}").text(text);
            if ($("#explanationInput{{ isset($id)? '-'.$id : '' }}").val() == '') {
                $("#seo_explanation_show{{ isset($id)? '-'.$id : '' }}").text("Here is an example of what a snippet looks like in Google's SERPs The content that appears here is usually taken from the Meta Description tag if relevant.");
            }
        })
    });


    $("#subjectInputName{{ isset($id)? '-'.$id : '' }}").on('keyup', function(){
       var strReal = "";
       strReal = $("#subjectInputName{{ isset($id)? '-'.$id : '' }}").val();
       $("#subjectInputName{{ isset($id)? '-'.$id : '' }}").val(clearHTMLtag(strReal));  
    });
    
    $("#explanationInput{{ isset($id)? '-'.$id : '' }}").on('keyup', function(){
       var strReal = "";
       strReal = $("#explanationInput{{ isset($id)? '-'.$id : '' }}").val();
       $("#explanationInput{{ isset($id)? '-'.$id : '' }}").val(clearHTMLtag(strReal));  
    });

    function clearHTMLtag(text){
            var regex = /<[^>]*>/g;
            var result = text.replace(regex, "");
            return result;
    }

</script>
