<?php
$scripts = [
    'datatables',
    'datatablesFixedColumns',
    'select2',
    'sweetalert',
    'datetimepicker'
];
?>

@extends('layouts.main')

@section('title', 'Invoice Search')

@section('breadcrumb')
<li>Invoice Search</li>
<li class="active">Replace</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Modify Cutomer Information - Invoice #1000259 [IDM_ReplaceInvoice]</h6>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
            {!! Form::open([
                'autocomplete' => 'off',
                'class'        => 'form-horizontal',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('first_name', 'First Name<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('first_name', null, [
                                'id'          => 'first_name',
                                'class'       => 'form-control',
                                'placeholder' => 'Tita [@CustomerFirstName]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('last_name', 'Last Name<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('last_name', null, [
                                'id'          => 'last_name',
                                'class'       => 'form-control',
                                'placeholder' => 'Wiroj [@CustomerLastName]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('address_line_1', 'Address<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('address_line_1', null, [
                                'id'          => 'address_line_1',
                                'class'       => 'form-control',
                                'placeholder' => '17/4 Village No.5 Dindaeng, Dindaeng, Dindaeng District, Bangkok, 10400'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('city', 'City<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <!-- Default select -->
                        <select class="form-control" data-width="100%" name="city[name]">
                            <option value="">City</option>
                            <option value="1">...</option>
                            <option value="2">...</option>
                            <option value="3">...</option>
                        </select>
                        <!-- /default select -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('country', 'Country<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <!-- Default select -->
                        <select class="form-control" data-width="100%" name="country[name]">
                            <option value="">Country</option>
                            <option value="1">...</option>
                            <option value="2">...</option>
                            <option value="3">...</option>
                        </select>
                        <!-- /default select -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('zipcode', 'Zipcode<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('zipcode', null, [
                                'id'          => 'zipcode',
                                'class'       => 'form-control',
                                'placeholder' => '10290 [@Zipcode]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('phone', 'Phone<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('phone', null, [
                                'id'          => 'phone',
                                'class'       => 'form-control',
                                'placeholder' => '0202223900 [@CustomerPhoneNo]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('tax_id', 'TAX ID<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('tax_id', null, [
                                'id'          => 'tax_id',
                                'class'       => 'form-control',
                                'placeholder' => '1101401999115 [@TaxPayerId]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('branch_id', 'Branch ID<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('branch_id', null, [
                                'id'          => 'branch_id',
                                'class'       => 'form-control',
                                'placeholder' => '[@SearchCriteria2]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                    {{ Form::button('<i class="icon-checkmark"></i> Save', array(
                        'type'  => 'submit',
                        'class' => 'pull-right btn bg-primary-800 btn-raised legitRipple legitRipple'
                    )) }}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection

@section('footer_script')
{{--{{ Html::script('js/coupons/datatable.js') }}--}}
@endsection