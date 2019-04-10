<script type="text/javascript">
function callAjax(type, url, data, successCallback = null, postSuccessCallback = null, completeCallback = null, postFailCallback = null) {
	$.ajax({
		type: type,
		url: url,
		data: data,
		dataType: 'json',
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
			var dataValidate = "";
			$.each(data.responseJSON,function(key,value){
				dataValidate += value + "\n";
			});
			swal('{{ trans('validation.create.fail') }}', dataValidate, 'warning');
		},
		complete: function() {
			if(completeCallback) {
				completeCallback();
			}
		}
	});
}

function callAjaxCustom(type, url, data, contentType='formData', successCallback = null, completeCallback = null, postFailCallback = null) {

	var custom = {};
	custom.type = type;
	custom.url = url;
	custom.data = data;

	if(contentType == 'json'){
		custom.contentType = "application/json; charset=utf-8"
	}

  	custom.dataType = 'json';

  	custom.success = function(data){
    	if(data.status || data.success){
      		if(successCallback){
        		successCallback(data);
      		}	
    	} else{
      		onAjaxFail(data, postFailCallback);
    	}
  	};

  	custom.error = onAjaxError;
  	custom.complete = function(){
    	if(completeCallback) {
      		completeCallback();
    	}
  	};

  	$.ajax(custom);
}

function onAjaxSuccess(data, callback = null) {
	swal({
      	title: "{{ trans('validation.create.title') }}",
      	text: data.messages,
      	type: "success",
      	allowEscapeKey: false,
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

function onAjaxMultipleItem(dataSuccess = null, dataErrors = null, callback = null, successIdKey = 'id', errorIdKey = 'id'){

	var htmlString = '';

	if(dataSuccess == null && dataErrors == null){
		onAjaxSuccess(null);
		return;
	}

	// Success
	if(dataSuccess != null){
		for(var item in dataSuccess){
			htmlString += '<li class="list-group-item"><i class="glyphicon glyphicon-ok text-success"></i>('+dataSuccess[item][successIdKey]+')</li>';
		}
	}

	// Errors
	if(dataErrors != null){
      	for (var item in dataErrors) {
        	htmlString += '<li class="list-group-item"><i class="glyphicon glyphicon-remove text-danger"></i>('+dataErrors[item][errorIdKey]+') '+dataErrors[item].message+'</li>';
      	}
    }

	// Display
	swal(
		{
			title: "{{ trans('validation.create.title') }}",
			text:  '<ul class="list-group no-border">'+htmlString+'</ul>',
			html: true
      	}, 
      	callback
    );
}

function getCheckedId() {
	
	var table = $('.table').map(function() {
	    return this.id;
	}).get();

	table = jQuery.grep(table, function(n, i){
	  return (n !== "" && n != null);
	});
	

			if(table.length == 0){
				// old function
				var ids = $('.ids:checked').serializeArray();
				// console.log(ids);
				var data = ids.map(function(elem) {
					return elem.value;
				});
				return jQuery.unique(data);
			}else{
				var ids = $('#'+table+' .ids:checked').serializeArray();				
				// console.log(ids);
				var data = ids.map(function(elem) {
					return elem.value;
				});
				return jQuery.unique(data);
			}
}

$('.table').on('change','.ids', function(){
	var ch = $(this).prop('checked');
	var val = $(this).val();
	$(":checkbox[value="+val+"]").prop("checked", ch);
	getCheckedId();
});	


function isChecked(){
	var ids = $('.ids:checked').serializeArray();
	return (ids.length > 0)? true : false;
}
</script>