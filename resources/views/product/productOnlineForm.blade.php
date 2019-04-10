<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">{{ $formName }}</h5>
		<div class="heading-elements">Last Modified : {{ convertDateTime($updated_at, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</div>
	</div>
	<div class="panel-body">
		<div class="rows">
			<div class="col-lg-12">
				<div class="form-group"
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('name','{{ $lang }}')}"
				@endif>
					<label class="control-label col-lg-5 text-left">{{ trans_append_language('Product Name',$lang) }} : <span class="text-danger">*</span> </label>
					<div class="col-lg-7">
						<input type="text" class="form-control" name="name[{{$lang}}]" value="{{ $productIntermediateData['name'][$lang] }}" ng-readonly="{{ $readonly }}">
					</div>
				</div>
			</div>
 		</div>
		<div class="col-lg-12">
			<div class="panel">
				<div class="panel-heading bg-teal-400">
					<h6 class="panel-title">{{ trans_append_language('Detail',$lang) }}<i ng-show=""></i></h6>
				</div>
				<textarea id="full_{{ $product }}_text_{{ $lang }}" name="description[{{$lang}}]"
				@if($readonly=="true")
				disabled
				@endif
				 ng-model="{{ $product }}.description.{{ $lang }}" ></textarea>
				<div class="clearfix"></div>
				
				<!-- Old Description -->
				<div ng-show="false">
					<textarea id="description_old_textarea_{{ $lang }}" ng-show="">
					</textarea>
				</div>
				<!-- End Old Description -->
			</div>
		</div>
	</div>
</div>


