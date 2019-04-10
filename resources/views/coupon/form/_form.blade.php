{{ Form::open([
    'autocomplete' => 'off',
    'id'           => 'form-submit',
    'url'          => '/coupon/' . $coupon_id
]) }}
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    @if(isset($coupon_id))
        <input type="hidden" id="coupon_id" name="coupon_id" value="{{ $coupon_id }}" />
        <input type="hidden" id="_method"  name="_method" value="{{ $method }}" />
    @endif
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {{ Html::ul($errors->all()) }}
                        </div>
                    @endif

                    <!-- Start: Tab panel -->
                    <div class="tabbable">
                        <div class="row">
                            {{--  =================  language   =================  --}}

                                @include('../common._dropdown_right', ['language'=> $language] )
                            {{--  ================= End language   =================  --}}
                            <!-- Tab content -->
                                <input type="hidden" name="coupon_type" value="{{isset($coupon['coupon_type'])? $coupon['coupon_type'] : ''}}">
                                <input type="hidden" name="least_amount" value="{{isset($coupon['least_amount'])? $coupon['least_amount'] : ''}}">
                                <input type="hidden" name="maximum_discount" value="{{isset($coupon['maximum_discount'])? $coupon['maximum_discount'] : ''}}">
                                <input type="hidden" name="usage_count " value="{{isset($coupon['usage_count'])? $coupon['usage_count'] : ''}}">
                                
                                <input type="hidden" name="exclude_products" 
                                    value={!! isset($coupon['exclude_products'])? json_encode($coupon['exclude_products']) : '' !!}>

                            <!-- hidden input  -->
                         </div>
                               
                        <!-- endhidden input  -->
                        <div class="tab-content">
                            @foreach($language as $lang)
                            <div class="tab-pane fade {{ $lang == $language[0] ? 'in active' : '' }}" id="tab-panel-{{ $lang }}">
                                <!-- Name panel -->
                                @include('coupon.form.name',[
                                    'language' => $lang,
                                    'name' => isset($coupon['coupon_name'][$lang])? $coupon['coupon_name'][$lang] : ''
                                ])
                                <!-- End Name panel -->
                                <div class="clearfix"></div>

                                <!-- Name panel -->
                                @include('coupon.form.description',[
                                    'language' => $lang,
                                    'name' => isset($coupon['description'][$lang])? $coupon['description'][$lang] : ''
                                ])
                                <!-- End Name panel -->
                                <div class="clearfix"></div>
                            </div>
                            @endforeach
                        </div>
                        <!-- End Tab content -->
                    </div>
                    <!-- End: Tab panel -->
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                    <label for="name">
                                    {!! Html::decode(Form::label('coupon_code', '<span class="text-danger">*</span> Coupon Code')) !!}
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    {{ Form::text('coupon_code', $coupon['coupon_code'], [
                                        'placeholder' => 'Coupon Code',
                                        'maxlength' => 8,
                                        'class'     => 'form-control'
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                    <label for="name">
                                    {!! Html::decode(Form::label('ref_code', '<span class="text-danger">*</span> Ref. Code')) !!}
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    {{ Form::text('ref_code', isset($coupon['ref_code'])? $coupon['ref_code'] : '', [
                                        'placeholder' => 'Ref. Code',
                                        'maxlength' => 100,
                                        'class'     => 'form-control'
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                {!! Html::decode(Form::label('discount_type', '<span class="text-danger">*</span> Coupon Type')) !!}
                                </div>
                                <div class="col-lg-6">
                                    @include('common._select', [
                                    'data' => [
                                        'cart discount'      => 'Fixed Cart Discount',
                                        'product discount'     => 'Fixed Product Discount',
                                    ],
                                    'name' => 'discount_type',
                                    'id' => 'discount_type',
                                    'value' => isset($coupon["coupon_type"]) ? $coupon["coupon_type"] : null 
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row row-product">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                    {!! Html::decode(Form::label('product', '<span class="text-danger">*</span> Product')) !!}
                                </div>
                                <div class="col-lg-6">
                                    {{ Form::text('product', isset($product['id'])?$product['name'] : '', [
                                        'placeholder' => 'Type for search a product.',
                                        'maxlength' => 150,
                                        'class'     => 'form-control typeahead'
                                    ]) }}
                                </div>
                                    <input type="hidden" name="product_old_id" id="product_old_id" value="{{ isset($product['id'])? $product['id'] : '' }}">
                                    <input type="hidden" name="product_id" id="product_id" value="{{ isset($product['id'])? $product['id'] : '' }}">
                            </div>
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                    {!! Html::decode(Form::label('product_threshold', '<span class="text-danger">*</span> Product Threshold')) !!}
                                </div>
                                <div class="col-lg-6">
                                {{ Form::text('product_threshold', isset($coupon['product_threshold'])?$coupon['product_threshold']:'', [
                                        'placeholder' => 'Product Threshold',
                                        'maxlength' => 150,
                                        'class'     => 'form-control',
                                        'OnKeyPress' => "return chkNumber(this)"
                                ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row row-cart_threshold">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                {!! Html::decode(Form::label('least_amount', '<span class="text-danger">*</span> Cart Threshold')) !!}
                                </div>
                                <div class="col-lg-6">
                                    {{ Form::text('least_amount', isset($coupon['least_amount'])?$coupon['least_amount']:'', [
                                        'placeholder' => 'Cart Threshold',
                                        'maxlength' => 150,
                                        'class'     => 'form-control',
                                        'OnKeyPress' => "return chkNumber(this)"
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                {!! Html::decode(Form::label('discount', '<span class="text-danger">*</span> Discount')) !!}
                                </div>
                                <div class="col-lg-6">
                                    {{ Form::text('discount', isset($coupon['amount'])?$coupon['amount']:'', [
                                        'placeholder' => 'Discount',
                                        'maxlength' => 150,
                                        'class'     => 'form-control',
                                        'OnKeyPress' => "return chkNumber(this)"
                                    ]) }}
                                </div>
                                <div class="col-lg-2 control-label">
                                    <label for="name">
                                        <span class="text-gray">THB</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row row-product">
                            <div class="form-group col-lg-12">
                                @include('coupon.form._switchThump',[
                                        'language' => $lang,
                                        'text_name' => 'Thumbnail Display',
                                        'name' => 'thumbnail_display',
                                    ])
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3">
                                    <label for="name">
                                        Valid Period
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label>From</label>
                                    {{ Form::text('started_date', isset($coupon['started_date'])?$coupon['started_date']:'', [
                                        'id'          => 'started_date',
                                        'class'       => 'form-control',
                                        'placeholder' => 'DD/MM/YYYY H:i'
                                    ]) }}
                                </div>
                                <div class="col-md-3">
                                    <label>To</label>
                                    {{ Form::text('end_date', isset($coupon['end_date'])?$coupon['end_date']:'', [
                                        'id'          => 'end_date',
                                        'class'       => 'form-control',
                                        'placeholder' => 'DD/MM/YYYY H:i'
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                {!! Html::decode(Form::label('limit_per_customer', 'Limit per customer')) !!}
                                </div>
                                <div class="col-lg-6">
                                    {{ Form::text('limit_per_customer', isset($coupon['limit_per_customer'])?$coupon['limit_per_customer']:'', [
                                        'placeholder' => 'Limit per customer, blank for unlimited.',
                                        'maxlength' => 150,
                                        'class'     => 'form-control',
                                        'OnKeyPress' => "return chkNumber(this)"
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                {!! Html::decode(Form::label('limit_per_coupon', 'Limit per coupon')) !!}
                                </div>
                                <div class="col-lg-6">
                                    {{ Form::text('limit_per_coupon', isset($coupon['limit_per_coupon'])?$coupon['limit_per_coupon']:'', [
                                        'placeholder' => 'Limit per coupon, blank for unlimited.',
                                        'maxlength' => 150,
                                        'class'     => 'form-control',
                                        'OnKeyPress' => "return chkNumber(this)"
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <div class="col-lg-3 control-label">
                                {!! Html::decode(Form::label('division', 'Division')) !!}
                                </div>
                                <div class="col-lg-6">
                                    {{ Form::text('division', 'e-commerce', [
                                        'placeholder' => 'Division',
                                        'maxlength' => 100,
                                        'class'     => 'form-control',
                                        'readonly'
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                @include('coupon.form._switch',[
                                        'language' => $lang,
                                        'text_name' => 'Action',
                                        'onText'    => 'Publish',
                                        'offText'   => 'Unpublish',
                                        'name' => 'status',
                                    ])
                            </div>
                        </div>
                    </div>    
                        <input type="hidden" name="amount" id="amount" value="0">
                        <input type="hidden" name="amount_currency_percentage" id="amount_currency_percentage" value="THB">
                        
                    <!-- Start: Save button -->
                    <div class="pull-right">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {{ Form::button('<i class="icon-checkmark"></i> Save', [
                                    'type'  => 'submit',
                                    'class' => 'btn bg-primary-800 btn-raised btn-submit'
                                ]) }}
                            </div>
                        </div>
                    </div>
                    <!-- End: Save button -->

                </div>
            </div>
        </div>
    </div>
{{ Form::close() }}