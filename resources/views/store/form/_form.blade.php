<form id="form-submit" class="form-horizontal" autocomplete="off">
<div class="panel">
	<div class="panel-body">		
		<div class="row">
			<div class="col-lg-12">
				@if (count($errors) > 0)
					<div class="alert alert-danger">
						{{ Html::ul($errors->all()) }}
					</div>
				@endif
					<div style="position: relative; z-index: 99;">
						  @include('../common._dropdown_right', ['language'=> $language] )
					</div>
				   
				<!-- Start: Tab panel -->
				<div class="tabbable">
					<!-- Start: Tab menu -->
					{{--  =================  language   =================  --}}
					<!-- Tab content -->
					<div class="tab-content">
						@foreach($language as $lang)
							<div class="tab-pane fade {{ $lang == $language[0] ? 'in active' : '' }}" id="tab-panel-{{ $lang }}">
								<div class="">

									<!-- Name panel -->
									@include('store.form.name',[
										'language' => $lang,
										'name' => isset($store['name'][$lang])? $store['name'][$lang] : ''
									])
									<!-- End Name panel -->
									<div class="clearfix"></div>
									<!-- Name panel -->
									@include('store.form.address',[
										'language' => $lang,
										'name' => isset($address['store']['address']['address'][$lang])? $address['store']['address']['address'][$lang] : '',
										'address_line_1' => isset($address['store']['address']['address'][$lang])? $address['store']['address']['address'][$lang] : ''
									])
									<!-- End Name panel -->
									<div class="clearfix"></div>
									<!-- Name panel -->
									@include('store.form.address2',[
										'language' => $lang,
										'name' => isset($address['store']['address']['address2'][$lang])? $address['store']['address']['address2'][$lang] : '',
										'address_line_2' => isset($address['store']['address']['address2'][$lang])? $address['store']['address']['address2'][$lang] : ''
									])
									<!-- End Name panel -->
									<div class="clearfix"></div>

								</div>
							</div>
						@endforeach		
						{{--  ================= End language   =================  --}}
						{{ Form::hidden('store_id', array_get($address, 'store.address.id', '')) }}
           				 <input type="hidden" name="mode" value="store_address">
						<div class="col-lg-12">
							<div class="row">
								<div class="form-group col-lg-12">
									<div class="col-lg-2 control-label">
										<label for="name">
											{!! Html::decode(Form::label('store_province', ' Province')) !!}
										</label>
									</div>
									<div class="col-lg-7">
										{{ Form::select('store_province', array_get($address, 'provinces', []), array_get($address, 'store.address.province.id', ''), [
											'class'       => 'form-control select2 col-lg-12 _address',
											'group'       => 'store',
											'type'        => 'districts',
											'placeholder' => 'Select Province ...',
											'data-placeholder' => 'Select Province ...'
										]) }}
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="row">
								<div class="form-group col-lg-12">
									<div class="col-lg-2 control-label">
										<label for="name">
											{!! Html::decode(Form::label('store_districts', ' District')) !!}
										</label>
									</div>
									<div class="col-lg-7">
										{{ Form::select('store_districts', array_get($address, 'store.districts', []), array_get($address, 'store.address.district.id', ''), [
											'class'       => 'form-control select2 col-lg-12 _address',
											'group'       => 'store',
											'type'        => 'sub_district',
											'placeholder' => 'Select District ...',
											'data-placeholder' => 'Select District ...'
										]) }}
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="row">
								<div class="form-group col-lg-12">
									<div class="col-lg-2 control-label">
										<label for="name">
											{!! Html::decode(Form::label('store_sub_district', ' Sub-District')) !!}
										</label>
									</div>
									<div class="col-lg-7">	
										{{ Form::select('store_sub_district', array_get($address, 'store.subdistricts', []), array_get($address, 'store.address.subdistrict.id', ''), [
											'class'            => 'form-control select2 col-lg-12 _address',
											'group'       => 'store',
											'type'        => 'postcode',
											'placeholder' => 'Select Sub-District ...',
											'data-placeholder' => 'Select Sub-District ...'
										]) }}
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="row">
								<div class="form-group col-lg-12">
									<div class="col-lg-2 control-label">
										<label for="name">
											{!! Html::decode(Form::label('postcode', ' Postcode')) !!}
										</label>
									</div>
									<div class="col-lg-7">	
										{{ Form::text('store_postcode', array_get($address, 'store.address.postcode', ''), [
											'id' => 'store_postcode',
											'placeholder' => 'Postcode',
											'maxlength' => 35,
											'class'     => 'form-control',
											'readonly' => ''
										]) }}
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12" id="Region">
							<div class="row">
								<div class="form-group col-lg-12">
									<label class="control-label col-lg-2">
										{!! Html::decode(Form::label('region', ' Zone')) !!}
									</label>
									<div class="col-lg-7">
										@include('common._select', [
											'data' => $region,
											'hasPlaceholder' => true, 
											'name' => 'region',
											'id' => 'select-region', 
											'value' => isset($store["region"]) ? $store["region"] : null
										])
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-12">
	                        <div class="row">
	                            <div class="form-group col-lg-12">
	                                <div class="col-lg-2 control-label">
	                                    <label for="name">
	                                    {!! Html::decode(Form::label('contact_phone', '<span class="text-danger">*</span>  Phone Number')) !!}
	                                    </label>
	                                </div>
	                                <div class="col-lg-7">
	                                    {{ Form::text('contact_phone',isset($store['contact_phone'])? $store['contact_phone'] : '', [
	                                        'placeholder' => '09XXXXXXXX',
	                                        'maxlength' => 10,
	                                        'class'     => 'form-control'
	                                    ]) }}
	                                </div>
	                            </div>
	                        </div>
                    	</div>
						<div class="col-lg-12">
	                        <div class="row">
	                            <div class="form-group col-lg-12">
	                                <div class="col-lg-2 control-label">
	                                    <label for="name">
	                                    {!! Html::decode(Form::label('contact_fax', '<span class="text-danger">*</span>  Fax Number')) !!}
	                                    </label>
	                                </div>
	                                <div class="col-lg-7">
	                                    {{ Form::text('contact_fax',isset($store['contact_fax'])? $store['contact_fax'] : '', [
	                                        'placeholder' => '02XXXXXXXX',
	                                        'maxlength' => 10,
	                                        'class'     => 'form-control'
	                                    ]) }}
	                                </div>
	                            </div>
	                        </div>
                    	</div>

						@include('store.form._switch',[
							'language' => $lang,
							'text_name' => 'Status',
							'name' => 'status',
							'onText' => 'Publish',
							'offText' => 'Unpublish',
							'value' => isset($store['status'])? $store['status'] : ''
						])
						<div class="clearfix"></div>

						@include('store.form._switch',[
							'language' => $lang,
							'text_name' => 'Delivery',
							'name' => 'delivery',
							'onText' => 'Yes',
							'offText' => 'No',
							'value' => isset($store['have_delivery'])? $store['have_delivery'] : ''
						])
						<div class="clearfix"></div>


						@include('common.map_draggable',[
                            'latitude' => isset($address['store']['address']['location']['latitude']) ? $address['store']['address']['location']['latitude'] : "0",
                            'longitude' =>  isset($address['store']['address']['location']['longitude']) ? $address['store']['address']['location']['longitude'] : "0"
                        ]) 

						<div class="clearfix"></div>

					</div>
					<!-- End Tab content -->
				</div>

				<div class="pull-right">
					<div class="form-group">
						{{ Form::button(' save', [
							'type'  => 'submit',
							'class' => 'btn bg-primary-800 btn-raised btn-submit'
						]) }}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>