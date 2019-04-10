<?php
$scripts = [
	'angular',
    'inputupload',
	'sweetalert',
	];
?>

@extends('layouts.main')

@section('title', 'Attribute')

@section('breadcrumb')
<li><a href="/attribute">Attribute</a></li>
<li class="active">{{ $menu_action }}</li>
@endsection

@section('header_script')
<style type="text/css">
    .btn-icon { cursor: pointer; }
	.thumbnail > img {
		width : 16px;
		height : 16px;
	}
</style>
@endsection

@section('content')

<div class="row" ng-app="myApp">
	<div class="col-md-8 col-md-offset-2" ng-controller="addnewController">
		<form id="form-submit" enctype="multipart/form-data">
			<div class="panel panel-flat" style="position: static;">
				<div class="panel-heading">
					<h5 class="panel-title">Attribute<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
				</div>
				{{ csrf_field() }}
				<input type="hidden" id="attr_id" name="attr_id" value="{{ $attr_id }}">
				<div id='isHidden'></div>

				<div class="tabbable">
				<ul class="nav nav-tabs bg-teal-400 nav-justified">
                    <?php $i = 1; ?>
                    @foreach($language as $lang)
                        <li class="{{ $i == 1 ? 'active' : '' }}">
                            <a href="#highlighted-tab{{ $i }}" data-toggle="tab">{{ trans('form.title.'.$lang) }}</a>
                        </li>
                        <?php $i++; ?>
                    @endforeach
                </ul>

                <div class="panel-body">
                <div class="tab-content col-lg-6">
                <?php $i = 1; ?>
                    <div ng-repeat="(lang, attr) in attr_name" ng-class="($first) ? 'active' : ''" class="tab-pane" id="highlighted-tab@{{ $index+1 }}">
					<fieldset>
						<legend class="text-semibold" style="text-transform:none;"><i class="icon-home"></i>  Attribute (@{{ lang | uppercase }}) <span class="text-danger">*</span></legend>
						<div class="row">
						<div class="col-md-11">
						<div class="form-group">
							<input class="form-control" name="name_@{{ lang }}"  placeholder="Input Main Attribute Name" type="text" value="@{{ attr.name }}">
						</div>
						</div>
						</div>

					</fieldset>

					<fieldset>
						<legend class="text-semibold" style="text-transform:none;"><i class="icon-menu3"></i>  Attribute Values (@{{ lang | uppercase }}) <span class="text-danger">*</span></legend>
						<div class="row" ng-repeat="(key, subcate) in attr.subattr">
							<div class="col-md-11">
								<div class="form-group">
									<input class="form-control" name="sub_attr_@{{ lang }}[@{{key}}][sub_id]" type="hidden" value="@{{ subcate.sub_attribute_id }}">
									<input class="form-control"  name="sub_attr_@{{ lang }}[@{{key}}][name]" placeholder="Input Sub Attribute Name" type="text" value="@{{ subcate.subname }}">
								</div>
							</div>
							<div class="col-md-1" ng-if="!$first"><i class="icon-bin btn-icon" ng-click="removeInput($index, subcate.sub_attribute_id)"></i></div>
						</div>

					</fieldset>

					<div class="row">
					    <div class="col-md-12">
						    <i class="icon-plus2 btn-icon" ng-click="addInput()"></i>
					    </div>
					</div>
					</div>
                <?php $i++; ?>

				</div>
				<div class="col-lg-6" style="height:195px;">
				</div>
				<div class="col-lg-6" id="imageUpload">
					<div class="col-lg-12" ng-repeat="data in attr_name.th.subattr" style="margin-botton:0px;">
							<div class="col-md-12">
								<div class="fileupload fileupload-new input-group col-md-12" data-provides="fileupload">
									<div class="fileupload-new thumbnail col-md-2">
										<img class="thumb_old" ng-src="@{{ attr_name.th.subattr[$index].image_url || no_img }}">
									</div>
									<div class="fileupload-preview fileupload-exists thumbnail col-md-2"></div>
									<div class="form-inline col-md-8">
										<div class="fileupload fileupload-new input-group" data-provides="fileupload">
											<span class="input-group-btn">
												<span class="btn btn-default btn-file text-primary">
													<span class="fileupload-new"><i class="glyphicon glyphicon-camera"></i>&nbsp;&nbsp;Select file</span>
													<span class="fileupload-exists">Change</span>

													<input type="file" name="file_@{{$index}}" accept="image/*" value="">
													<input type="hidden" name="images[file_@{{$index}}][input_file_name]" value="file_@{{$index}}">
													<input type="hidden" name="images[file_@{{$index}}][old]" value="@{{ attr_name.th.subattr[$index].old_image_url }}">
													<input type="hidden" name="images[file_@{{$index}}][url_if_exist]" value="@{{ attr_name.th.subattr[$index].image_url }}">
												</span>
											</span>
											<span class="input-group-btn">
												<a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
												<a ng-click="removeExist($index)" ng-if="visible($index)" class="btn btn-danger fileupload-new">Remove</a>
												
											</span>
											
										</div>
									</div>
								</div>
							</div>
					</div>
					<div>Size 16x16 pixel, .jpg or .png file format only</div>
				</div>
				</div>
				<div class="row">
				<div class="col-md-11">
				<div class="pull-right">
					<div class="form-group">
						{{ Form::button('<i class="icon-checkmark"></i> Save', [
								'type'  => 'submit',
								'class' => 'btn bg-primary-800 btn-raised btn-submit'
						]) }}
					</div>

				</div>
				</div>
				</div>

				<br />

				</div>

			</div>
		</form>

	</div>

</div>

@endsection

@section('footer_script')

{!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}

@include('common._call_ajax')

<script type="text/javascript">
    var appUrl = '{{ $form_action }}';
    var checkClick = false;
    var validateData = {
        init: function () {
            var _self = this;
            $('#form-submit').on('click', '.btn-submit', function (e) {
                e.preventDefault();
                _self.validate();
            });
        },
		
        validate: function () {
            var formData = new FormData(document.getElementById("form-submit"));
			var dataImage = '';
                $.ajax({
                    type: 'POST',
                    url: appUrl,
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.status || data.success) {
                            onAjaxSuccess(data, function(){
								if(appUrl=='/attribute/update')
									location.reload();
								else
                                	window.location = "/attribute";
                            });
                        } 
                        else {
                            onAjaxFail(data);
                        }
                    },
                    error: function(data) {
						if(data.responseJSON.expired) {
							swal({
								title: "Error!",
								text: 'Session Expired',
								type: "error",
								confirmButtonText: "OK"
								},
								function(){
									window.location.href = window.location.href;
								}
							);
						} else {
							var dataValidation = '';
							$.each(data.responseJSON,function(key,value){
								dataValidation += value + "\n";
							});
							swal('{{ trans('validation.create.fail') }}', dataValidation, 'warning');
						}
                    }            
                });
        },
    }

    validateData.init();

</script>


<script type="text/javascript">

	var myApp = angular.module('myApp', []);

    myApp.controller('addnewController', function ($scope) {


    	$scope.attr_name = {!! $attr_name !!};
    	$scope.attr_sub  = {!! $attr_sub !!};
		$scope.no_img = '{!! $attr_default_img !!}';

		console.log($scope.attr_name.en.subattr);
		
    	$scope.addInput = function () {
    		var item  = { subname: '', image_url: '' };
    		$scope.attr_name.th.subattr.push(item);
    		$scope.attr_name.en.subattr.push(item);
    	}

		$scope.removeExist = function ($index) {
			var input_name = 'input[name=file_'+$index+']';
			$(input_name).val('');
			$scope.attr_name.th.subattr[$index].image_url = '';
		}

		$scope.visible = function ($index) {
			var input_name = 'input[name=file_'+$index+']';
			if ($scope.attr_name.th.subattr[$index].image_url !='' && $scope.attr_name.th.subattr[$index].image_url != null)
				return true;
			return false;
		}

    	$scope.removeInput = function (index, id) {
            $scope.attr_name.th.subattr.splice(index, 1);
            $scope.attr_name.en.subattr.splice(index, 1);

            if (typeof(id) != 'undefined') {
            	// var item  = { del_id: '' };
            	// $scope.attr_name.th.subattr.push(item);
            	var input = document.createElement("input");
				input.setAttribute("type", "hidden");
				input.setAttribute("name", "del_id[]");
				input.setAttribute("value", id);
				document.getElementById("isHidden").appendChild(input);
            }
    	}
    });

     
</script>
@endsection
