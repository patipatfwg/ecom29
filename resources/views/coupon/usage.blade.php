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

@section('title', 'Coupon')

@section('breadcrumb')
<li><a href="/coupon">Coupon</a></li>
<li class="active">Usage History</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
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
                        <div class="col-md-6">
                            <label>Order Number</label>
                            {{ Form::text('full_text', null, [
                                'id'          => 'full_text',
                                'class'       => 'form-control',
                                'placeholder' => 'Order Number'
                            ]) }}
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                                @include('common._select', [
                                'data' => [
                                    'all'      => 'All',
                                    'hold'     => 'Hold',
                                    'used'     => 'Used',
                                    'canceled'  => 'Canceled'
                                ],
                                'name' => 'status','id' => 'select-type']
                                )
                            <input type="hidden" id="status" name="value">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                        <label>Used Date</label>
                            <div class="input-group input-daterange">
                                {{ Form::text('start_date', null, [
                                    'id'          => 'start_date',
                                    'class'       => 'form-control',
                                    'placeholder' => 'DD/MM/YYYY HH:MM:SS'
                                ]) }}
                                <div class="input-group-addon">
                                    to
                                </div>
                                {{ Form::text('end_date', null, [
                                    'id'          => 'end_date',
                                    'class'       => 'form-control',
                                    'placeholder' => 'DD/MM/YYYY HH:MM:SS'
                                ]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="row">
                 
                        {{ Form::button('<i class="icon-search4"></i> Search', [
                            'type'  => 'submit',
                            'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                        ]) }}
                    
                </div>
                {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">{{ $coupon['coupon_name']['en'] }} ({{ $coupon['coupon_code'] }})</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-border-gray table-striped datatable-dom-position" id="history-table" data-page-length="10" width="160%">
                    <thead>
                        <tr>
                            <th width="80">No.</th>
                            <th width="100">Used Date</th>
                            <th width="200">Order Number</th>
                            <th>Order Amount</th>
                            <th>Makro ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Customer Type</th>
                            <th>Mobile Number</th>
                            <th>Email</th>
                            <th>Status</th>               
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="12" class="text-center">
                                <i class="icon-spinner2 spinner"></i> Loading ...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
@include('coupon.history._form_script')
@include('common._datetime_range_script', [
        'format_start'  => 'd/m/Y H:i:00',
        'format_end'    => 'd/m/Y H:i:00',
        'refer_start'   => '#start_date',
        'refer_end'     => '#end_date',
        'timepicker'    => true,
        'editable'      => true
    ])

@endsection