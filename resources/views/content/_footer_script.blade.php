    <script type="text/javascript">

        $( document ).ready(function(){

            // setup tag input data
            @foreach($language as $lang)
                @if(isset($tags[$lang]))
                    @foreach($tags[$lang] as $tag)
                        $("#tagsinput_{{ $lang }}").tagsinput("add","{{ $tag }}");
                    @endforeach
                @endif
            @endforeach


            $('.select-dropdown').select2({
                minimumResultsForSearch: -1
            });
            $('select').on('select2:select', function (evt) {
                // Do something
                var categoryId = $(".select-dropdown option:selected").val();
                $("#selected-category-id").val(categoryId);
            });

            // Set Category Selected
            @if(!empty($contentCategory['category_id']))
                $(".select-dropdown").val("{!! $contentCategory['category_id'] !!}").trigger('change');
            @endif

        });


        @foreach($language as $lang)

            $("#tagsinput_{{ $lang }}").on('itemAdded', function(event) {
                // event.item: contains the item
                var items =  $("#tagsinput_{{ $lang }}").val();
                $("#tags_show_{{ $lang }}").text('Ex : '+items);
            });

            $("#tagsinput_{{$lang}}").on('itemRemoved', function(event) {
                // event.item: contains the item
                var items =  $("#tagsinput_{{ $lang }}").val();
                $("#tags_show_{{$lang}}").text('Ex : '+items);
            });

            CKEDITOR.replace('full_text_{{ $lang }}');

            @if( isset($contentDetail['description'][$lang]) )
                var text = '{!! str_replace("\r\n" , '\r\n' , $contentDetail['description'][$lang] ) !!}';
                var html = text;
                CKEDITOR.instances.full_text_{{ $lang }}.setData(html);
            @endif

        @endforeach

    </script>

    @include('common._validate_form_script')
    @include('common._slug_script',['slug_input_name' => 'name_en'])

    <script type="text/javascript">

        // $(document).ready(function(){
        //     $.ajax({
        //         method: 'POST',
        //         url: 'http://makro-ecommerce-admin.dev:8054/images',
        //         success: function(data) {
        //             console.log(data);
        //             //$('#login-form').html(data);
        //         }
        //     })
        // })

        var contentId = '{{ isset($contentId) ? $contentId : "" }}';
        var createConfig = {
            form: $('#form-submit'),
            url: '/content',
            httpMethod: 'POST',
            successCallback: function() {
                window.location = '/content'
            }
        }
        var updateConfig = {
            form: $('#form-submit'),
            url: '/content/'+contentId,
            httpMethod: 'PUT',
            successCallback: function() {
                window.location = '/content/' + contentId + '/edit'
            }
        }

        var config = (contentId === '') ? createConfig : updateConfig;
        validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback, beforeFormSubmit);

        $(".switch").bootstrapSwitch();

        function beforeFormSubmit()
        {
            @foreach($language as $lang)
                var markdown_text = CKEDITOR.instances.full_text_{{ $lang }}.getData();
                $("#full_text_{{ $lang }}").val(markdown_text);
            @endforeach
        }

    </script>
    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}

