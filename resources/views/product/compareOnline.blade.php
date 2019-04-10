<div class="panel ">
	<div class="panel-heading">
		<div class="panel-title">
				<h5>Online Information</h5>
		</div>
		<div class="heading-elements">
			<select id="language-select" class="form-control">
				@foreach($language as $lang)
					<option value="{{ $lang }}">{{ trans('form.title.'.$lang) }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="panel-body">
		@foreach($language as $lang)
		<div class="col-lg-12" id="panel-{{ $lang }}">
			<div class="col-lg-6">
				@include('product.productOnlineForm', [
					'formName'           => 'Online',
					'product'            => 'productOnline',
					'readonly'           => true,
					'updated_at' => $productOnlineData['updated_at']
				])
			</div>
			<div class="col-lg-6">
				@include('product.productOnlineForm', [
					'formName'           => 'Staging',
					'product'            => 'productIntermediate',
					'readonly'           => ($editAble)? false : true,
					'updated_at' => $productIntermediateData['updated_at']
				])
			</div>
			<div class="col-lg-12">
				@include('product.tag',[
					'readonly' => ($editAble)? false : true
				])
			</div>
		</div>
		@endforeach

        <div class="col-lg-6">
			@include('common._seo', [
				'subject'     => isset($productOnlineData['seo']['title']) ? $productOnlineData['seo']['title'] : '',
				'explanation' => isset($productOnlineData['seo']['description']) ? $productOnlineData['seo']['description'] : '',
				'readOnly'    => true ,
				'id'          => 'productOnline',
				'slug'        => $productOnlineData['item_id'] . '-' . $productOnlineData['name']['th']
			])
		</div>
		</div>
        <div class="col-lg-6">
			@include('common._seo', [
				'subject'     => isset($productIntermediateData['seo']['title']) ? $productIntermediateData['seo']['title'] : '',
				'explanation' => isset($productIntermediateData['seo']['description']) ? $productIntermediateData['seo']['description'] : '',
				'readOnly'    => ($editAble)? false : true , 'id' => 'productIntermediate',
				'slug'        => $productOnlineData['item_id'] . '-' . $productIntermediateData['name']['th']
			])
		</div>
		</div>
	</div>

</div>