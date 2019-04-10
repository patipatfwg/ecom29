<div class="col-lg-6">
    {{ Form::open([
        'autocomplete' => 'off',
        'id'           => 'form-profile',
        'url'          => '/member/' . $result['online_customer_id'],
        'method'       => 'PUT'
    ]) }}
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Personal Information</h6>
        </div>
        <div class="panel-body panel-profile">
            <input type="hidden" name="mode" value="profile">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('first_name', 'First Name')) !!}
                        {{ Form::text('first_name', $result['first_name'], [
                            'placeholder' => 'First Name',
                            'maxlength' => 64,
                            'class'     => 'form-control'
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('last_name', 'Last Name')) !!}
                        {{ Form::text('last_name', $result['last_name'], [
                            'placeholder' => 'Last Name',
                            'maxlength' => 64,
                            'class'     => 'form-control'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('phone', 'Mobile Number')) !!}
                        {{ Form::text('phone', $result['phone'], [
                            'placeholder' => '08xxxxxxxx',
                            'maxlength' => 40,
                            'class'     => 'form-control'
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('email', 'Email')) !!}
                        {{ Form::text('email', $result['email'], [
                            'placeholder' => 'sample@sample.com',
                            'maxlength' => 150,
                            'class'     => 'form-control'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('birth_day', 'Birthday')) !!}
                        {{ Form::text('birth_day', !empty($result['birth_day']) ? convertDateTime($result['birth_day'], 'Y-m-d', 'd/m/Y') : '', [
                            'id'    => 'birth_day',
                            'class' => 'form-control',
                            'placeholder' => 'DD/MM/YYYY',
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('pickup_store', 'Pickup Store')) !!}
                        {{ Form::select('pickup_store', $stores, $result['pickup_store_id'], [
                            'class'       => 'form-control select2',
                            'placeholder' => 'Select Pickup Store ...'
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('status', 'Status')) !!}
                        {{ Form::select('status', [
                            'active'   => 'Active',
                            'inactive' => 'Inactive'
                        ], $result['status'], [
                            'class'       => 'form-control',
                            'placeholder' => 'Select Status...'
                        ]) }}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {!! Html::decode(Form::label('customer_channel', 'Customer Channel')) !!}
                        {{ Form::select('customer_channel', config('config.customer_channel')
                        ,$result['customer_channel'],[
                            'class'       => 'form-control',
                            'placeholder' => 'Select Customer Channel...'
                        ]
                        ) }}
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