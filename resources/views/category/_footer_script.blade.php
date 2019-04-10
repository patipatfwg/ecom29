<script type="text/javascript">
    $('#attribute').multi();
    $(".switch").bootstrapSwitch();
</script>
        @include('common._validate_form_script')

<script type="text/javascript">

    var category_id = '{{ isset($category_id) ? $category_id : "" }}'; 
    var parent_id = '{{ isset($parent_id) ? $parent_id : "" }}'; 
    var url = '/category';
    var urls = '/category/' + parent_id;
    var createConfig = {
        form: $('#form-submit'),
        url: url,           
        httpMethod: 'POST',
        successCallback: function() {
            window.location = urls
        }
    }
        
    var updateConfig = {
        form: $('#form-submit'),
        url: url + '/' + category_id,
        httpMethod: 'PUT',
        successCallback: function() {
            window.location = url + '/' + category_id + '/edit'
        }    
    }
    console.log(url);
    var config = (category_id === '') ? createConfig : updateConfig; 
    validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);
</script>
    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\CategoryCreateRequest', '#form-submit') !!}
