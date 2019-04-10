<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">{{ $formName }}</h5>
		<div class="heading-elements">Last Modified : {{ convertDateTime($updated_at, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</div>
	</div>
	<div class="panel-body">
		 
			<div class="col-lg-12">
				<div class="form-group" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('height')}"
				@endif>
					<label class="control-label col-lg-4 text-left">Height : </label>
					<div class="col-lg-8">
						<input type="text" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="height"' !!} class="col-lg-12 form-control" ng-model="{{$product}}.height">
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('width')}"
				@endif>
					<label class="control-label col-lg-4 text-left">Width : </label>
					<div class="col-lg-8">
						<input type="text" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="width"' !!} class="col-lg-12 form-control" ng-model="{{$product}}.width">
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('length')}"
				@endif>
					<label class="control-label col-lg-4 text-left">Length : </label>
					<div class="col-lg-8">
						<input type="text" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="length"' !!} class="col-lg-12 form-control" ng-model="{{$product}}.length">
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group">
					<label class="control-label col-lg-4 text-left">UOM : </label>
					<div class="col-lg-8">
						<select class="form-control" ng-disabled="{{$readonly}}" {!! ($readonly)? '' : 'name="lwh_uom"' !!}>
							@foreach($lwhUom as $unit)
							<option value={!! json_encode($unit) !!} {!! $lwh_uom_id == $unit['id']? 'selected' : '' !!}>{{ sprintf('%s (%s)', $unit['name'][$language], $unit['short_name'][$language]) }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('weight')}"
				@endif>
					<label class="control-label col-lg-4 text-left">Weight : </label>
					<div class="col-lg-4">
						<input type="text" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="weight"' !!} class="col-lg-12 form-control" ng-model="{{$product}}.weight">
					</div>
					<div class="col-lg-4">
						<select class="form-control" ng-disabled="{{$readonly}}" {!! ($readonly)? '' : 'name="weight_uom"' !!}>
							@foreach($weightUom as $unit)
							<option value={!! json_encode($unit) !!} {!! $weight_uom_id == $unit['id']? 'selected' : '' !!}>{{ sprintf('%s (%s)', $unit['name'][$language], $unit['short_name'][$language]) }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
 
	</div>
</div>