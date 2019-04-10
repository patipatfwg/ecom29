
<div class="col-lg-6">
    {{ Form::open([
        'autocomplete' => 'off',
        'id'           => 'form-bill',
        'url'          => '/member/' . $result['online_customer_id'],
        'method'       => 'PUT'
    ]) }}
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Billing Address</h6>
        </div>
        <div class="panel-body panel-profile">
            <input type="hidden" name="mode" value="bill">
            <input type="hidden" name="content_type" value="member">
            {{ Form::hidden('bill_address_id', array_get($address, 'bill.address.id')) }}

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('first_name', '<span class="text-danger">*</span> First Name')) !!}
                        {{ Form::text('first_name', array_get($address, 'bill.address.first_name', ''), [
                            'placeholder' => 'First Name',
                            'maxlength' => 64,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('last_name', '<span class="text-danger">*</span> Last Name')) !!}
                        {{ Form::text('last_name', array_get($address, 'bill.address.last_name', ''), [
                            'placeholder' => 'Last Name',
                            'maxlength' => 64,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('contact_phone', '<span class="text-danger">*</span> Mobile Number')) !!}
                        {{ Form::text('contact_phone', array_get($address, 'bill.address.contact_phone', ''), [
                            'placeholder' => '08xxxxxxxx',
                            'maxlength' => 40,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('email', '<span class="text-danger">*</span> Email')) !!}
                        {{ Form::text('contact_email', array_get($address, 'bill.address.contact_email', ''), [
                            'placeholder' => 'Mobile Number',
                            'maxlength' => 40,
                            'class'     => 'form-control',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
            </div>
            
            <div class="row">

                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('bill_address_1', '<span class="text-danger">*</span> Address Line 1')) !!}
                            {{ Form::text('bill_address_1', array_get($address, 'bill.address.address.th', ''), [
                                'placeholder' => 'Address Line1',
                                'class'       => 'form-control',
                                'maxlength'   => 70,
                                'disabled' => ''
                            ]) }}
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('bill_address_2', ' Address Line 2')) !!}
                            {{ Form::text('bill_address_2', array_get($address, 'bill.address.address2.th', ''), [
                                'placeholder' => 'Address Line2',
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
                        {!! Html::decode(Form::label('bill_province', '<span class="text-danger">*</span> Province')) !!}
                        {{ Form::select('bill_province', array_get($address, 'provinces', []), array_get($address, 'bill.address.province.id', ''), [
                            'class'       => 'form-control select2 col-lg-12 _address',
                            'group'       => 'bill',
                            'type'        => 'districts',
                            'placeholder'   => 'Select Province ...',
                            'data-placeholder' => 'Select Province ...',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('bill_districts', '<span class="text-danger">*</span> District')) !!}
                        {{ Form::select('bill_districts', array_get($address, 'bill.districts', []), array_get($address, 'bill.address.district.id', ''), [
                            'class'       => 'form-control select2 col-lg-12 _address',
                            'group'       => 'bill',
                            'type'        => 'sub_district',
                            'placeholder' => 'Select District ...',
                            'data-placeholder' => 'Select District ...',
                            'disabled' => ''
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('bill_sub_district', '<span class="text-danger">*</span> Sub-District')) !!}
                        {{ Form::select('bill_sub_district', array_get($address, 'bill.subdistricts', []), array_get($address, 'bill.address.subdistrict.id', ''), [
                            'class'       => 'form-control select2 col-lg-12 _address',
                            'group'       => 'bill',
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
                        {!! Html::decode(Form::label('bill_postcode', '<span class="text-danger">*</span> Postcode')) !!}
                        {{ Form::text('bill_postcode', array_get($address, 'bill.address.postcode', ''), [
                            'id' => 'bill_postcode',
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
                        'disabled' => 'disabled'
                    )) }}
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>