
<div class="col-lg-6">
    {{ Form::open([
        'autocomplete' => 'off',
        'id'           => 'form-tax',
        'url'          => '/member/' . $result['online_customer_id'],
        'method'       => 'PUT'
    ]) }}
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Tax Address</h6>
        </div>
        <div class="panel-body panel-profile">
            <input type="hidden" name="mode" value="tax">
            {{ Form::hidden('tax_address_id', array_get($address, 'tax.address.id')) }}
            {{ Form::hidden('online_customer_id', array_get($result, 'online_customer_id')) }}
            <div class="row">

                <div class="col-lg-8">
                    <div class="form-group">
                        {!! Html::decode(Form::label('business_shop_name', ' Company Name/Personal Name')) !!}
                        {{ Form::text('business_shop_name', array_get($result, 'business.shop_name', ''), [
                            'placeholder' => 'Company Name / Personal Name',
                            'maxlength' => 100,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('business_branch', ' Head Office/Branch No.')) !!}
                        {{ Form::text('business_branch', array_get($result, 'business.branch', ''), [
                            'maxlength' => 150,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tax_id', '<span class="text-danger">*</span> Tax ID')) !!}
                        {{ Form::text('tax_id', array_get($result, 'tax_id', ''), [
                            'placeholder' => 'Company/Personal Tax ID',
                            'maxlength' => 40,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('business_main_mobile', '<span class="text-danger">*</span> Mobile Number')) !!}
                        {{ Form::text('business_phone', array_get($address, 'tax.address.contact_phone', ''), [
                            'placeholder' => '08xxxxxxxx',
                            'maxlength'   => 40,
                            'class'       => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('business_email', ' Email')) !!}
                        {{ Form::text('business_email', array_get($result, 'business.email', ''), [
                            'placeholder' => 'sample@sample.com',
                            'maxlength' => 150,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tax_address_1', '<span class="text-danger">*</span> Address Line 1')) !!}
                            {{ Form::text('tax_address_1', array_get($address, 'tax.address.address.th', ''), [
                                'placeholder' => 'Address Line 1',
                                'class'       => 'form-control',
                                'maxlength'   => 70,
                                'disabled' => ''
                            ]) }}
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tax_address_2', ' Address Line 2')) !!}
                            {{ Form::text('tax_address_2', array_get($address, 'tax.address.address2.th', ''), [
                                'placeholder' => 'Address Line 2',
                                'class'       => 'form-control',
                                'maxlength'   => 70,
                                'disabled' => ''
                            ]) }}
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tax_province', '<span class="text-danger">*</span> Province')) !!}
                        {{ Form::select('tax_province', array_get($address, 'provinces', []), array_get($address, 'tax.address.province.id', ''), [
                            'class'       => 'form-control select2 _address',
                            'group'       => 'tax',
                            'type'        => 'districts',
                            'placeholder' => 'Select Province ...',
                            'data-placeholder' => 'Select Province ...',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tax_districts', '<span class="text-danger">*</span> District')) !!}
                        {{ Form::select('tax_districts', array_get($address, 'tax.districts', []), array_get($address, 'tax.address.district.id', ''), [
                            'class'       => 'form-control select2 _address',
                            'group'       => 'tax',
                            'type'        => 'sub_district',
                            'placeholder' => 'Select District ...',
                            'data-placeholder' => 'Select District ...',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('tax_sub_district', '<span class="text-danger">*</span> Sub-District')) !!}
                        {{ Form::select('tax_sub_district', array_get($address, 'tax.subdistricts', []), array_get($address, 'tax.address.subdistrict.id', ''), [
                            'class'       => 'form-control select2 _address',
                            'group'       => 'tax',
                            'type'        => 'postcode',
                            'placeholder' => 'Select Sub-District ...',
                            'data-placeholder' => 'Select Sub-District ...',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('business_postcode', '<span class="text-danger">*</span> Postcode')) !!}
                        {{ Form::text('tax_postcode', array_get($address, 'tax.address.postcode', ''), [
                            'id' => 'tax_postcode',
                            'placeholder' => 'Postcode',
                            'maxlength' => 35,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
            </div>

        </div>
        <div class="panel-footer">
            <div class="col-lg-12">
                <div class="text-right">
                    {{ Form::button('Save', array(
                        'type'  => 'submit',
                        'class' => 'btn bg-teal-400 btn-raised legitRipple loading',
                        'disabled' => ''
                    )) }}
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>