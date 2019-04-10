<div class="col-lg-6">
    {{ Form::open([
        'autocomplete' => 'off',
        'id'           => 'form-profile-address',
        'url'          => '/member/' . $result['online_customer_id'],
        'method'       => 'PUT'
    ]) }}
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Personal Address</h6>
        </div>
        <div class="panel-body panel-profile">
            {{ Form::hidden('profile_id', array_get($address, 'profile.address.id', '')) }}
            <input type="hidden" name="mode" value="profile_address">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! Html::decode(Form::label('address_1', ' Address Line 1')) !!}
                        {{ Form::text('address_1', array_get($address, 'profile.address.address.th', ''), [
                            'placeholder' => 'Address Line1',
                            'maxlength' => 100,
                            'class'     => 'form-control'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('profile_province', ' Province')) !!}
                        {{ Form::select('profile_province', array_get($address, 'provinces', []), array_get($address, 'profile.address.province.id', ''), [
                            'class'       => 'form-control select2 col-lg-12 _address',
                            'group'       => 'profile',
                            'type'        => 'districts',
                            'placeholder' => 'Select Province ...',
                            'data-placeholder' => 'Select Province ...'
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('profile_districts', ' District')) !!}
                        {{ Form::select('profile_districts', array_get($address, 'profile.districts', []), array_get($address, 'profile.address.district.id', ''), [
                            'class'       => 'form-control select2 col-lg-12 _address',
                            'group'       => 'profile',
                            'type'        => 'sub_district',
                            'placeholder' => 'Select District ...',
                            'data-placeholder' => 'Select District ...'
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('profile_sub_district', ' Sub-District')) !!}
                        {{ Form::select('profile_sub_district', array_get($address, 'profile.subdistricts', []), array_get($address, 'profile.address.subdistrict.id', ''), [
                            'class'            => 'form-control select2 col-lg-12 _address',
                            'group'       => 'profile',
                            'type'        => 'postcode',
                            'placeholder' => 'Select Sub-District ...',
                            'data-placeholder' => 'Select Sub-District ...'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! Html::decode(Form::label('postcode', ' Postcode')) !!}
                        {{ Form::text('profile_postcode', array_get($address, 'profile.address.postcode', ''), [
                            'id' => 'profile_postcode',
                            'placeholder' => 'Postcode',
                            'maxlength' => 35,
                            'class'     => 'form-control',
                            'readonly' => ''
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
                        'class' => 'btn bg-teal-400 btn-raised legitRipple loading'
                    )) }}
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
</div>