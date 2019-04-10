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
<li class="active">Coupon</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Search</h6>
    </div>
    <div class="panel-body search_section">
        <div class="col-lg-12">
            {!! Form::open([
                'autocomplete' => 'off',
                'class'        => 'form-horizontal',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-4">
                        <label>Coupon Code</label>
                        <div class="input-group" style="width:100%">
                            {{ Form::text('coupon_code', null, [
                                'name'        => 'coupon_code',
                                'id'          => 'coupon_code',
                                'class'       => 'form-control',
                                'placeholder' => 'Coupon Code'
                            ]) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Coupon Name</label>
                        <div class="input-group" style="width:100%">
                            {{ Form::text('coupon_name', null, [
                                'name'        => 'coupon_name',
                                'id'          => 'coupon_name',
                                'class'       => 'form-control',
                                'placeholder' => 'Coupon Name'
                            ]) }}
                        </div>
                    </div>        
                    <div class="col-md-4">
                        <div class="row">
                            <label>Coupon Type</label>
                             @include('common._select', [
                                'data' => [
                                    'all'               => 'All',
                                    'cart discount'     => 'Fixed Cart Discount',
                                    'product discount' => 'Fixed Product Discount'
                                ],
                                'name' => 'coupon_type','id' => 'select-type' ]
                            )
                            <input type="hidden" id="status" name="value">
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
    <div class="panel-body table-responsive">
        <table class="table table-striped table-hover datatable-dom-position" id="coupons-table" data-page-length="10" width="100%">
            <thead>
                <tr>
                    <th width="80"><input type="checkbox" class="check-all"></th>
                    <th width="80">No.</th>
                    <th width="100">Coupon Code</th>
                    <th width="200">Coupon Name (TH)</th>
                    <th width="200">Coupon Name (EN)</th>
                    <th>Coupon Type</th>
                    <th>Coupon Discount</th>
                    <th>Created Date</th>
                    <th>Started Date</th>
                    <th>End Date</th>
                    <th>Expired Date</th>
                    <th>Usage</th>
                    <th>Published</th>
                    <th>Edit</th>
                    <th>Delete</th>     
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="14" class="text-center">Loading ...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('footer_script')
{{ Html::script('js/coupons/datatable.js') }}
<script type="text/javascript">
    $('div.datatable-header').append(`
        @include('common._status_dropdown')&nbsp;
        @include('common._delete_button', [ 'url' => '/coupon/delete' ] )&nbsp;
        @include('common._print_button')&nbsp;
        @include('common._create_button', ['url' => URL::to('coupon/create')])
    `);

    $(".select-dropdown").select2({
        minimumResultsForSearch: -1,
        placeholder: "Select Coupon Type"
    });

</script>
@include('common._datatable')
@endsection