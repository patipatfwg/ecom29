<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">{{ $formName }}</h5>
		<div class="heading-elements">Last Modified : {{ convertDateTime($updated_at, 'Y-m-d H:i:s', 'd/m/Y H:i:s') }}</div>
	</div>
	<div class="panel-body">
			<div class="col-lg-12">
				<div class="form-group"
					@if($product=='productIntermediate')
						ng-class="{'has-warning': compare('sub_makro_unit')}"
					@endif
					 >
					<label class="control-label col-lg-4 text-left">Unit Type : <span class="control-label text-danger"> *</span> </label>
					<div class="col-lg-4">
						<input type="text" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="sub_makro_unit"' !!} class="col-lg-12 form-control" ng-model="{{$product}}.sub_makro_unit">
					</div>
					<div class="col-lg-4">
						<select class="form-control" ng-disabled="{{$readonly}}" {!! ($readonly)? '' : 'name="unit_type"' !!}>
							@foreach($unitType as $unit)
							<option value={!! json_encode($unit) !!} {!! $unit_type_id == $unit['id']? 'selected' : '' !!}>{{ sprintf('%s (%s)', $unit['name']['en'], $unit['name']['th']) }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group"
					@if($product=='productIntermediate')
						ng-class="{'has-warning': compare('makro_unit')}"
					@endif
					>
					<label class="control-label col-lg-4 text-left">Pieces per Unit : <span class="text-danger"> *</span> </label>
					<div class="col-lg-8">
						<input type="text" class="form-control" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="makro_unit"' !!} ng-model="{{$product}}.makro_unit">
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group"
					@if($product=='productIntermediate')
						ng-class="{'has-warning': compare('suggest_price')}"
					@endif>
					<label class="control-label col-lg-4 text-left">Suggested Price: </label>
					<div class="col-lg-8">
						<input type="text" class="form-control" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="suggest_price"' !!}  ng-model="{{$product}}.suggest_price">
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group"
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('profit_per_unit')}"
				@endif>
					<label class="control-label col-lg-4 text-left">Profit Per Unit: </label>
					<div class="col-lg-8">
						<input type="text" class="form-control" ng-readonly="{{$readonly}}" {!! ($readonly)? '' :  'name="profit_per_unit"' !!}  ng-model="{{$product}}.profit_per_unit">
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group"
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('total_profit')}"
				@endif>
					<label class="control-label col-lg-4 text-left">Total Profit: </label>
					<div class="col-lg-8">
						<input type="text" class="form-control" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="total_profit"' !!}  ng-model="{{$product}}.total_profit">
					</div>
				</div>
			</div>
			
			<div class="col-lg-12">
				<div class="form-group"
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('minimum_order_limit')}"
				@endif>
					<label class="control-label col-lg-4 text-left">Minimum Order Limit: </label>
					<div class="col-lg-8">
						<input type="number" class="form-control" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="minimum_order_limit"' !!} ng-model="{{$product}}.minimum_order_limit" min="1">
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="form-group"
					@if($product=='productIntermediate')
						ng-class="{'has-warning': compare('maximum_order_limit')}"
					@endif
					>
					<label class="control-label col-lg-4 text-left">Maximum Order Limit:</label>
					<div class="col-lg-8">
						<input type="number" class="form-control" ng-readonly="{{$readonly}}" {!! ($readonly)? '' : 'name="maximum_order_limit"' !!}  ng-model="{{$product}}.maximum_order_limit" min="1">
					</div>
				</div>
			</div>

	</div>
</div>