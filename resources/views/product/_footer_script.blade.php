<script>

    $('#brand_id').select2({
        placeholder: 'Select Brand...',
        allowClear: true
    });
	/// form script
    var productId = "{{ $product_id }}";
    var approveStatus = "{{ ($editAble)? '' : 'readyToApprove' }}";
    var productRelatedList = null;
	var createConfig = {
		form: $('#form-submit'),
		url: '/product',
		httpMethod: 'POST',
		successCallback: function() {
			window.location = '/product'
		}
	}
	var updateConfig = {
		form: $('#form-submit'),
		url: '/product/' + productId,
		httpMethod: 'POST',
		successCallback: function() {
			window.location = '/product/' + productId + '/edit'
		}    
	}
    var approveConfig = {
		form: $('#form-submit'),
		url: '/product/' + productId + '/approve',
		httpMethod: 'POST',
		successCallback: function() {
			window.location = '/product/' + productId + '/edit'
		}    
	}
	var config = (approveStatus === '') ? updateConfig : approveConfig;
	validateAndSubmit(config.form, config.url, config.httpMethod, config.successCallback);

	function validateAndSubmit(form, url, httpMethod, successCallback) {
        var checkClick = false;

        $(form).on('click', '.btn-save', function (event) {
            $("#setStatus").val("save");
            event.preventDefault();
			
            if ($(form).valid()) {
                if (checkClick) {
                    return false;
                }

                @foreach($language as $lang)
                    // Current Description
                    var data = CKEDITOR.instances.full_productIntermediate_text_{{ $lang }}.getData();
                    $("#full_productIntermediate_text_{{ $lang }}").val(data);

                    // Old Description
                    var dataOld = CKEDITOR.instances.description_old_textarea_{{ $lang }}.getData();
                    $("#description_old_{{ $lang }}").val(dataOld);
                @endforeach

                checkClick = true;
                var formData = new FormData(document.getElementById("form-submit"));

                @if(isset($product_id))
                    formData.append('_method', 'PUT');
                @endif
                
                callAjax(httpMethod, url, formData, null, successCallback, function(){
                    checkClick = false;
                });
            }

        });

        $(form).on('click', '.btn-ready', function (event) {
            event.preventDefault();
			$("#setStatus").val("ready");
            if ($(form).valid()) {
                if (checkClick) {
                    return false;
                }

                @foreach($language as $lang)
                    // Current Description
                    var data = CKEDITOR.instances.full_productIntermediate_text_{{ $lang }}.getData();
                    $("#full_productIntermediate_text_{{ $lang }}").val(data);

                    // Old Description
                    var dataOld = CKEDITOR.instances.description_old_textarea_{{ $lang }}.getData();
                    $("#description_old_{{ $lang }}").val(dataOld);
                @endforeach

                checkClick = true;
                var formData = new FormData(document.getElementById("form-submit"));

                @if(isset($product_id))
                    formData.append('_method', 'PUT');
                @endif
                
                callAjax(httpMethod, url, formData, null, successCallback, function(){
                    checkClick = false;
                });
            }

        });

        $(form).on('click', '.btn-approve', function (event) {
            event.preventDefault();
			$("#setStatus").val("approved");
            if ($(form).valid()) {
                if (checkClick) {
                    return false;
                }

                @foreach($language as $lang)
                    // Current Description
                    var data = CKEDITOR.instances.full_productIntermediate_text_{{ $lang }}.getData();
                    $("#full_productIntermediate_text_{{ $lang }}").val(data);

                    // Old Description
                    var dataOld = CKEDITOR.instances.description_old_textarea_{{ $lang }}.getData();
                    $("#description_old_{{ $lang }}").val(dataOld);
                @endforeach

                checkClick = true;
                var formData = new FormData(document.getElementById("form-submit"));

                @if(isset($product_id))
                    formData.append('_method', 'PUT');
                @endif
                
                callAjax(httpMethod, url, formData, null, successCallback, function(){
                    checkClick = false;
                });
            }

        });

        $(form).on('click', '.btn-reject', function (event) {
            event.preventDefault();
			$("#setStatus").val("reject");
            if ($(form).valid()) {
                if (checkClick) {
                    return false;
                }

                @foreach($language as $lang)
                    // Current Description
                    var data = CKEDITOR.instances.full_productIntermediate_text_{{ $lang }}.getData();
                    $("#full_productIntermediate_text_{{ $lang }}").val(data);

                    // Old Description
                    var dataOld = CKEDITOR.instances.description_old_textarea_{{ $lang }}.getData();
                    $("#description_old_{{ $lang }}").val(dataOld);
                @endforeach

                checkClick = true;
                var formData = new FormData(document.getElementById("form-submit"));

                @if(isset($product_id))
                    formData.append('_method', 'PUT');
                @endif
                
                callAjax(httpMethod, url, formData, null, successCallback, function(){
                    checkClick = false;
                });
            }

        });

	}
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
                dataImage = '';
                $.each(data.responseJSON,function(key,value){
                    dataImage += value + "\n";
                });
                swal('{{ trans('validation.create.fail') }}', dataImage, 'warning');
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

	$('body').on('click', '.dropdown-item', function(event) {
	    var id = $(this).attr('value');
	    var group = $(this).attr('group-name');
	    var str = $(this).text();
	
	    $('.dropdown-input[group-name="'+group+'"]').val(id);
	    $('.dropdown-selected[group-name="'+group+'"]').text(str);
	});

	(function(angular) {
	    var myApp = angular.module('productApp', []);
        
	    myApp.controller('productController', function($scope, $filter, $http) {

            $scope.storePriceData = {!! json_encode($storePriceData) !!};
            $scope.productOnline = {!! json_encode($productOnlineData) !!};
            $scope.productIntermediate = {!! json_encode($productIntermediateData) !!};
            
			$scope.businessCategoryData = {!! json_encode($businessCategoryData) !!};
			$scope.productCategoryData = {!! json_encode($productCategoryData) !!};
            $scope.attributeData = {!! json_encode($attributeData) !!} 
            
			$scope.businessCategoryList = {!! json_encode($businessCategoryFlattenList) !!};
			$scope.productCategoryList = {!! json_encode($productCategoryFlattenList) !!};
            $scope.attributeList = {!! json_encode($attributeList) !!}
            $scope.productRelatedData = [];
            
            $scope.pageList =  [];

            for(var i=0;i<$scope.storePriceData.pagination.total_records;i+=10) {
                $scope.pageList.push(i);
            }

            if($scope.productIntermediate.published.started_date != null){
                var intermediate_started_date = new Date($scope.productIntermediate.published.started_date);
                $scope.productIntermediate.published.started_date = $filter('date')(intermediate_started_date, 'dd/MM/yyyy HH:mm:ss');
            }

            if($scope.productIntermediate.published.end_date != null){
                var intermediate_end_date = new Date($scope.productIntermediate.published.end_date);
                $scope.productIntermediate.published.end_date = $filter('date')(intermediate_end_date, 'dd/MM/yyyy HH:mm:ss');
            }
            
            if($scope.productOnline.published.started_date != null){
                var online_started_date = new Date($scope.productOnline.published.started_date);
                $scope.productOnline.published.started_date = $filter('date')(online_started_date, 'dd/MM/yyyy HH:mm:ss');
            }

            if($scope.productOnline.published.end_date != null){
                var online_end_date = new Date($scope.productOnline.published.end_date);
                $scope.productOnline.published.end_date = $filter('date')(online_end_date, 'dd/MM/yyyy HH:mm:ss');
            }

            $scope.compare = function(field, subfield=false){
                if(subfield){
                    if($scope.productOnline[field][subfield] == null)
                        $scope.productOnline[field][subfield] = ''
                    if($scope.productIntermediate[field][subfield] == null)
                        $scope.productIntermediate[field][subfield] = ''
                    return $scope.productOnline[field][subfield]!=$scope.productIntermediate[field][subfield]
                }
                else
                {
                    if($scope.productOnline[field] == null)
                        $scope.productOnline[field] = ''
                    if($scope.productIntermediate[field] == null)
                        $scope.productIntermediate[field] = ''
                    return $scope.productOnline[field]!=$scope.productIntermediate[field]
                }
                    
            }
			
			$scope.deleteBusinessCategory = function(index){
				$scope.businessCategoryData.splice(index, 1);
			};
						
			$scope.addBusinessCategory = function(){
				var id = $('.dropdown-input[group-name="business"]').val();
				var checkDupResult = $filter('filter')($scope.businessCategoryData, {id:id});
				if(checkDupResult.length > 0){
					return false;
				}
				var result = $filter('filter')($scope.businessCategoryList, {id:id});
				$scope.businessCategoryData.push(result[0]);
			};

			$scope.deleteProductCategory = function(index){
				$scope.productCategoryData.splice(index, 1);
			};

			$scope.addProductCategory = function(){
				var id = $('.dropdown-input[group-name="product"]').val();
				var checkDupResult = $filter('filter')($scope.productCategoryData, {id:id});
				if(checkDupResult.length > 0){
					return false;
				}
				var result = $filter('filter')($scope.productCategoryList, {id:id});
                index = result.findIndex(x => x.id==id);
                $scope.productCategoryData.push(result[index]);
                
				
			};

            $scope.deleteAttribute = function(index){
                var input_id = "#attribute_id_"+$scope.attributeData[index].attribute_id;
                $(input_id).prop('checked', false);
                var collapse_id = "#collapse-"+$scope.attributeData[index].attribute_id;
                $(collapse_id).collapse("hide");
				$scope.attributeData.splice(index, 1);
                
			};

			$scope.addAttribute = function(){
                var serializeArrayData = ($('#attributeForm :input').serializeArray());
                var attributeDataObj = [];
                for(var i=0 ;i<serializeArrayData.length ;i++)
                {
                    if(serializeArrayData[i].name == 'attribute_id[]')
                    {
                        var attribute = new Object;
                        attribute.id = serializeArrayData[i].value;
                        name = 'attribute_value['+serializeArrayData[i].value+']';
                        for(var j=i+1 ;j<serializeArrayData.length ;j++)
                        {
                            if(serializeArrayData[j].name == name)
                            {
                                attribute.value_id = serializeArrayData[j].value;
                            }
                        }
                        attributeDataObj.push(attribute);
                    }
                }
                $scope.attributeData = [];
                for(var i=0 ;i<attributeDataObj.length ;i++)
                {
                    for(var j=0;j<$scope.attributeList.length;j++) {
                        if(attributeDataObj[i].id==$scope.attributeList[j].id) {
                            var result = $scope.attributeList[j];
                        }
                    }
                    result.subAttribute.forEach(function(entry){   
                        if(entry.sub_attribute_id == attributeDataObj[i].value_id)
                        {
                            
                            result.value_result = entry;
                        }
                           
                    });
                    var attributeResult = new Object;
                    attributeResult.attribute_id = result.id;
                    
                    attributeResult.name = result.name.th;
                    
                    attributeResult.attribute_value_id = result.value_result.sub_attribute_id;
                    
                    attributeResult.attribute_value_name = result.value_result.name.th;
                    
                    $scope.attributeData.push(attributeResult);
                    
                }

			};

            $scope.selectPage = function(offset){
                $http({
                    method : 'GET',
                    url : '/product/store_price',
                    params : {
                        'item_ids' : '{{ $productIntermediateData["item_id"] }}',
                        'limit' : 10,
                        'offset' : offset*10,
                        'order' : 'name|ASC'
                    }
                }).then(function(response) {
                    $scope.storePriceData = response.data;
                });
            };

		});

        myApp.directive('numbersOnly', function () {
      
            return {
                require: 'ngModel',
                link: function (scope, element, attr, ngModelCtrl) {
                    function fromUser(text) {
                        if (text) {
                            var transformedInput = text.replace(/[^0-9]/g, '');

                            if (transformedInput !== text) {
                                ngModelCtrl.$setViewValue(transformedInput);
                                ngModelCtrl.$render();
                            }
                            return transformedInput;
                        }
                        return undefined;
                    }            
                    ngModelCtrl.$parsers.push(fromUser);
                }
            };
        });
        
        myApp.controller('imageController', function($scope,$filter){
            $scope.tmpImage = {!! json_encode($images) !!};
            @if($productIntermediateData['approve_status']!='ready to approve')
                var sortable_table = document.getElementById('images-table-body');
                var sortable = Sortable.create(sortable_table, {
                    onSort: function (evt) {
                        /*
                    if (evt.newIndex < evt.item.childElementCount && evt.newIndex >=0 && evt.oldIndex < evt.item.childElementCount) {       
                            var tmpImage = $scope.tmpImage[evt.newIndex];
                            $scope.tmpImage[evt.newIndex] = $scope.tmpImage[evt.oldIndex];
                            $scope.tmpImage[evt.oldIndex] = tmpImage;
                        }
                        else if(evt.newIndex >= evt.item.childElementCount) {

                        } else {

                        }
                        */
                    }
                });

                $scope.deleteImage = function(index){
                    $scope.tmpImage.splice(index, 1);
                }
            @endif
        });
        
	}(window.angular));

    $('.collapse').collapse();

    @foreach($attributeData as $productAttribute)
		$("#collapse-{{ $productAttribute['attribute_id'] }}").collapse("show");
	@endforeach

</script>

<script>
var appurl="";
var myDropzone;
var totalFiles = 0;

Dropzone.autoDiscover = false;
$("#dropzone").dropzone({
    url: appurl+"uploadimage",
    autoProcessQueue: true,
    addRemoveLinks: true,
    maxFilesize: 20, //limit file size
    uploadMultiple: false,
    maxFiles: 5, //max file
    acceptedFiles: '.png,.jpg,.jpeg',
    accept: function(file, done) {

        file.acceptDimensions = done;

        file.rejectDimensions = function() { 
            done("Invalid dimension."); 
        };

    },
    init: function() {
        myDropzone = this;
        this.on("thumbnail", function(file) {
            console.log(file);
            // Do the dimension checks you want to do
            if (file.width != 800 || file.height != 800) {
                file.rejectDimensions()
            }
            else {
                file.acceptDimensions();
            }
        });
    },
    success: function(file, response, action) {
        if (response.success) {
            addImageToTmp(file, response.image);
            myDropzone.removeFile(file);
        } 

    },
    error: function(file, response) {
        swal('File!', 'Allow only .jpg or .png file and 800 x 800 resolution', 'warning');
        myDropzone.removeFile(file);
    },
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var previousIndex =0;
var currentIndex =0;

function addImageToTmp(file, image){
    var tmpImage = {
        name : file.name,
        url  : image
    }

    var scope = $('#imageController').scope();
    scope.tmpImage.push(tmpImage);
    scope.$apply();
}

function editNoImages(){
	$.each( $('.image-no'), function( key, value ) {
		$(this).text(key+1);
	});
}

@foreach($language as $lang)

    CKEDITOR.replace( "full_productOnline_text_{{ $lang }}");
    CKEDITOR.replace( "full_productIntermediate_text_{{ $lang }}");

    CKEDITOR.replace("description_old_textarea_{{ $lang }}");

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
    
@endforeach
$( document ).ready(function(){
    @foreach($language as $lang)
        @if(isset($tags[$lang]))
            @foreach($tags[$lang] as $tag)
                $("#tagsinput_{{ $lang }}").tagsinput("add","{{ $tag }}");
            @endforeach
        @endif
    @endforeach
    if (!!window.performance && window.performance.navigation.type === 2) {
        window.location.reload();
    }
});
@foreach($language as $lang)
    @if( isset($productOnlineData['description'][$lang]) && !empty($productOnlineData['description'][$lang]))
        var html = "{!! $productOnlineData["description"][$lang] !!}";
        CKEDITOR.instances.full_productOnline_text_{{ $lang }}.setData(html);
    @endif

    @if( isset($productIntermediateData['description'][$lang]) && !empty($productIntermediateData['description'][$lang])) 
        var html = "{!! $productIntermediateData["description"][$lang] !!}";
        CKEDITOR.instances.full_productIntermediate_text_{{ $lang }}.setData(html);
        CKEDITOR.instances.description_old_textarea_{{ $lang }}.setData(html);
    @endif

@endforeach

var language = [];
@foreach($language as $lang)
    language.push("{{ $lang }}");
    @if($lang !== $language[0])
        $("#panel-{{ $lang }}").hide();
    @else
        $("#panel-{{ $lang }}").show();
    @endif
@endforeach

$('#language-select').change(function() {

    var $this = $(this);
    var $itemId = $('label[ng-bind="productOnline.item_id"]').text();

    for(var i=0; i<language.length; i++) {
        if($this.val() == language[i]) {
            $('#panel-' + language[i]).show(500);
        } else {
            $('#panel-' + language[i]).hide(500);
        }
    }

    //get input by panel language
    $("#panel-" + $this.val() + " input[name^='name']").each(function(kData, vData) {
        var $slug = $(this).val().length > 0 ? $itemId + '-' + $(this).val() : $itemId;
        $('.seo-url').eq(kData).find('span').text($slug);
    });
});

$("input[name^='name']").keyup(function() {

    var $itemId = $('label[ng-bind="productOnline.item_id"]').text();
    var $slug = $(this).val().length > 0 ? $itemId + '-' + $(this).val() : $itemId;

    $('.seo-url').eq(1).find('span').text($slug);
});


</script>

{!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}