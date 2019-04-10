    @include('common._validate_form_script')

    <script type="text/javascript">
        var url = '/group_menu';
        var group_id = '{{ isset($group_id) ? $group_id : "" }}';

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
            url: url + '/' + group_id,
            httpMethod: 'PUT',
            successCallback: function() {
                location.reload();
            }
        }

        var config = (group_id === '') ? createConfig : updateConfig; 
        validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);
         $(".switch").bootstrapSwitch();
    </script>
    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
