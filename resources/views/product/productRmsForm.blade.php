<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">{{ $formName }}</h5>
		<div class="heading-elements">Last synchronized : {{ isset($last_update_datetimestamp)? convertDateTime($last_update_datetimestamp,'Y-m-d', 'd/m/Y') : null }}</div>
	</div>
	<div class="panel-body">
 
			<div class="col-lg-12">
				<div class="" 
					@if($product=='productIntermediate')
						ng-class="{'has-warning': compare('item_id')}"
					@endif>
					<label class="control-label col-lg-5 text-left text-both">Makro Item ID: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.item_id"></label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('original_name','th')}"
				@endif>
					<label class="control-label col-lg-5 text-left">Original Product Name: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.original_name.th"></label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('barcode')}"
				@endif>
					<label class="control-label col-lg-5 text-left">Barcode: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.barcode"></label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('buyer_name')}"
				@endif>
					<label class="control-label col-lg-5 text-left">Buyer Name: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.buyer_name"></label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('supplier_name')}"
				@endif>
					<label class="control-label col-lg-5 text-left">Supplier Name: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.supplier_name"></label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('original_brand')}"
				@endif>
					<label class="control-label col-lg-5 text-left">Brand : </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.original_brand"></label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('standard_uom')}"
				@endif>
					<label class="control-label col-lg-5 text-left">UOM: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.selling_uom"></label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('product_type')}"
				@endif>
					<label class="control-label col-lg-5 text-left">Assortment Type: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.product_type"></label>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('is_installation_available')}"
				@endif>
					<label class="control-label col-lg-5 text-left">Installation: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.is_installation_available"></label>
					</div>
				</div>
			</div>

			<div class="col-lg-12">
				<div class="" 
				@if($product=='productIntermediate')
					ng-class="{'has-warning': compare('is_hazmat')}"
				@endif>
					<label class="control-label col-lg-5 text-left">Fragile: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-12 text-left" ng-bind="{{$product}}.is_hazmat"></label>
					</div>
				</div>
			</div>
			
			<div class="col-lg-12">
				<div class="" 
					@if($product=='productIntermediate')
						ng-class="{'has-warning': compare('normal_price') || compare('normal_price_currency')}"
					@endif>
					<label class="control-label col-lg-5 text-left" >Normal Price: </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-5 text-left" ng-bind="{{$product}}.normal_price"></label>
						<label class="control-label col-lg-5 text-left" ng-bind="{{$product}}.normal_price_currency"></label>
					</div>				
				</div>
			</div>
			<div class="col-lg-12">
				<div class="" 
					@if($product=='productIntermediate')
						ng-class="{'has-warning': compare('vat_rate') || compare('normal_price_currency')}"
					@endif>
					<label class="control-label col-lg-5 text-left" >Normal Price (VAT incl.): </label>
					<div class="col-lg-7">
						<label class="control-label col-lg-5 text-left" ng-bind="{{$product}}.vat_rate"></label>
						<label class="control-label col-lg-5 text-left" ng-bind="{{$product}}.normal_price_currency"></label>
					</div>			
				</div>
			</div>
 
	</div>
</div>