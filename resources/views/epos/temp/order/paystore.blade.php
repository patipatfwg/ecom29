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

@section('title', 'Pay@Store Payment')

@section('breadcrumb')
<li class="active">Pay@Store Payment </li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Pay@Store Payment</h6>
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
                        {!! Html::decode(Form::label('order_number', 'Sale Order Number<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('order_number', null, [
                                'id'          => 'order_number',
                                'class'       => 'form-control',
                                'placeholder' => '201705300009'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('deposit_invoice', 'Deposit Tax Invoice Number<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('deposit_invoice', null, [
                                'id'          => 'deposit_invoice',
                                'class'       => 'form-control',
                                'placeholder' => '201705300009'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-md-3">
                        {!! Html::decode(Form::label('amount', 'Amount<span class="text-danger">*</span> : ')) !!}
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            {{ Form::text('amount', null, [
                                'id'          => 'amount',
                                'class'       => 'form-control',
                                'placeholder' => '0.00'
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
                    {{ Form::button('<i class="icon-cart-remove"></i> Submit', array(
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