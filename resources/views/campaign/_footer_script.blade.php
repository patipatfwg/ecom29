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


        });


        @foreach($language as $lang)

            // $("#tagsinput_{{ $lang }}").on('itemAdded', function(event) {
            //     // event.item: contains the item
            //     var items =  $("#tagsinput_{{ $lang }}").val();
            //     $("#tags_show_{{ $lang }}").text('Ex : '+items);
            // });

            // $("#tagsinput_{{$lang}}").on('itemRemoved', function(event) {
            //     // event.item: contains the item
            //     var items =  $("#tagsinput_{{ $lang }}").val();
            //     $("#tags_show_{{$lang}}").text('Ex : '+items);
            // });

            CKEDITOR.replace( "full_text_{{ $lang }}");

            @if( isset($campaignData['description'][$lang]) )
                var text = '{!! str_replace("\r\n" , '\r\n' , $campaignData['description'][$lang] ) !!}';
                var html = text;
                CKEDITOR.instances.full_text_{{ $lang }}.setData(html);
            @endif

        @endforeach
    </script>

    <script type="text/javascript">

    var campaignId = '{{ isset($campaignId) ? $campaignId : "" }}';
    var createConfig = {
        form: $('#form-submit'),
        url: '/campaign',
        httpMethod: 'POST',
        successCallback: function() {
            window.location = '/campaign'
        }
    }
    var updateConfig = {
        form: $('#form-submit'),
        url: '/campaign/'+campaignId,
        httpMethod: 'POST',
        successCallback: function() {
            window.location = '/campaign/' + campaignId + '/edit'
        }
    }

    var config = (campaignId === '') ? createConfig : updateConfig;
    validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);

        function validateAndSubmit(form, url, httpMethod, successCallback) {
        var checkClick = false;

        $(form).on('click', '.btn-submit', function (event) {
            event.preventDefault();

            if ($(form).valid()) {
                if (checkClick) {
                    return false;
                }

                checkClick = true;
                var formData = new FormData(document.getElementById("form-submit"));
                @foreach($language as $lang)
                    var markdown_text =CKEDITOR.instances.full_text_{{ $lang }}.getData();
                    formData.append ("description_{{ $lang }}",CKEDITOR.instances.full_text_{{ $lang }}.getData());
                @endforeach
                
                formData.append('', 'PUT');
                @if(isset($campaignData))
                    formData.append('_method', 'PUT');
                @endif
                
                callAjax(httpMethod, url, formData, null, successCallback, function(){
                    checkClick = false;
                });
            }
            
        });
     
        @if(isset($campaignId))
        var mappingProduct = function(){
            window.location = '/campaign/{{ $campaignId }}'
        }

        $(form).on('click', '#mapping-btn', function (event) {
            event.preventDefault();

            if ($(form).valid()) {
                if (checkClick) {
                    return false;
                }

                checkClick = true;
                var formData = new FormData(document.getElementById("form-submit"));

                @foreach($language as $lang)
                    var markdown_text = toMarkdown(CKEDITOR.instances.full_text_{{ $lang }}.getData());
                    formData.append ("description_{{ $lang }}",toMarkdown(CKEDITOR.instances.full_text_{{ $lang }}.getData()));
                @endforeach

                @if(isset($campaignData))
                    formData.append('_method', 'PUT');
                @endif
                callAjax(httpMethod, url, formData, null, mappingProduct, function(){
                    checkClick = false;
                });
            }
        });
        @endif

        }
    </script>

    <script type="text/javascript">
    
        function callAjax(type, url, data, successCallback = null, postSuccessCallback = null, completeCallback = null, postFailCallback = null) {
        $.ajax({
            type: type,
            url: url,
            data: data,
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.status || data.success) {
                if(successCallback) {
                    successCallback();
                }
                onAjaxSuccess(data, postSuccessCallback);
                } else {
                onAjaxFail(data, postFailCallback);
                }
            },
            error: function(data) {
                        console.log(data);
                        var dataValidation = '';
                        $.each(data.responseJSON,function(key,value){
                            dataValidation += value + "\n";
                        });
                        swal('{{ trans('validation.create.fail') }}', dataValidation, 'warning');
                       // onAjaxError
            },
            complete: function() {
                if(completeCallback) {
                completeCallback();
                }
            }
            }
        );
        }

        function onAjaxSuccess(data, callback = null) {
        swal({
            title: "{{ trans('validation.create.title') }}",
            text: data.messages,
            type: "success",
            confirmButtonText: "{{ trans('validation.btn_ok') }}"
            },
            callback
        );
        }

        function onAjaxFail(data, callback = null) {
        swal({
            title: "{{ trans('validation.create.fail') }}",
            text: data.error ? data.error : data.messages,
            type: "warning",
            confirmButtonText: "{{ trans('validation.btn_ok') }}"
            },
            callback
        );
        }

        function onAjaxError() {
        swal("{{ trans('validation.create.title') }}", "{{ trans('validation.error_connection') }}", 'error');
            }

            function getCheckedId() {
            var ids = $('.ids:checked').serializeArray();
            return ids.map(function(elem) {
                return elem.value;
            }).join();
        }
    </script>
    <script type="text/javascript">
   
    </script>
    <script type="text/javascript">
        $(".switch").bootstrapSwitch();
    </script>

    <script type="text/javascript">

        var promotion = {};
        
        @foreach($promotionData as $promotion)
            promotion["{{ $promotion['id'] }}"]= {
                    "start_date" : "{{ $promotion['start_date'] }}",
                    "end_date" : "{{ $promotion['end_date'] }}"
            };
        @endforeach

        $(document).ready(function() {

            $('.btn.fileupload-exists').on('click',function(){
                $("input[name='thumb_old']").val('');
                $('.thumb_old').attr('src', "{{ URL::asset('/assets/images/no-img.png') }}");
            });

            $('.btn.fileupload-exists').on('click',function(){
                $("input[name='thumb_old2']").val('');
                $('.thumb_old2').attr('src', "{{ URL::asset('/assets/images/no-img.png') }}");
            });

           
            // $("input[name=campaign_type]").on( "change", function() {
            //     showMappingTab();
            // });
            // showMappingTab();

            // $("#searchPromotion").on( "change", function() {
            //     changePromotionDate();
            // });
            // changePromotionDate();
        });

        function changePromotionDate()
        {
            if($("#searchPromotion").val()=="-1")
            {
                $("#promo_start_date").val("");
                $("#promo_end_date").val("");
            }
            else
            {
                $("#promo_start_date").val(promotion[$("#searchPromotion").val()]['start_date']);
                $("#promo_end_date").val(promotion[$("#searchPromotion").val()]['end_date']);
            }  
        }

        function showMappingTab()
        {   
            var tabList = ['offline','online'];
            tabList.forEach(function(entry){
                id = "#"+entry+"Tab";
                if($("input[name=campaign_type]:checked").val()==entry)
                {
                    $( id ).show(100);
                }
                else
                {
                   $( id ).hide(100);
                }
            });
        }

    </script>

    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\CampaignRequest', '#form-submit-create') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\CampaignRequest', '#form-submit-update') !!}
