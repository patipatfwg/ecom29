<div class="panel panel-flat">
   <div class="panel-heading">
      <h5 class="panel-title">Product Brand<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
   </div>
   <div class="panel-body">
      <div class="row">
         <div class="col-lg-12">
			<div class="form-group">
				<input type="hidden" name="old_brand_id" value="{{ $productIntermediateData['brand_id'] }}">
				<label class="col-lg-3 text-left text-both">Brand : </label>
				<div class="col-lg-9">
					<select class="form-control select2" name="brand_id" id="brand_id" {{ ($productIntermediateData['approve_status']=='ready to approve')? 'disabled' : '' }}>
						<option value="null"> ===== Please Select Brand ===== </option>
						@foreach($brandList as $brand)
						    <option value="{{ $brand['id'] }}" {{ ($brand['id']==$productIntermediateData['brand_id'])? 'selected' : '' }}>{{ $brand['name']['en'] . " ( " . $brand['name']['th'] . " ) " }}</option>
						@endforeach
					</select>
				</div>
			</div>
         </div>
      </div>
   </div>
</div>