<?php
    $scripts = [
        'nestable',
        'sweetalert',
        'select2',
        'datatables',
    ];
?>

@extends('layouts.main')

@section('title', 'Delivery Area')

@section('breadcrumb')
    <li class="active"><a href="/">Delivery Area</a></li>
@endsection

@section('header_script')
@endsection

@section('content')
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title font-bold">Search</h6>
        </div>
        <div class="panel-body search_section">
            <div class="col-lg-12">
                <input type="hidden" id="cancel_data_change" value="{{ $message['cancel_data_change'] }}" />
                <input type="hidden" id="update_validate_delivery_area" value="{{ $message['update_validate_delivery_area'] }}" />
                {!! Form::open([
                    'autocomplete' => 'off',
                    'class'        => 'form-horizontal',
                    'id'           => 'search-form'
                ]) !!}
                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-3">
                                <label>Province</label>
                                {{ Form::select('province.id', $province, null, [
                                    'id'          => 'select-province',
                                    'class'       => 'form-control select2',
                                    'placeholder' => 'All'
                                ]) }}
                            </div>
                            <div class="col-lg-3">
                                <label>District</label>
                                {{ Form::select('district.id', $district, null, [
                                    'id'          => 'select-district',
                                    'class'       => 'form-control select2',
                                    'placeholder' => 'Please select province'
                                ]) }}
                            </div>
                            <div class="col-lg-3">
                                <label>Subdistrict</label>
                                {{ Form::select('sub_district.id', $district, null, [
                                    'id'          => 'select-sub-district',
                                    'class'       => 'form-control select2',
                                    'placeholder' => 'Please select district'
                                ]) }}
                            </div>
                            <div class="col-lg-3">
                                <label>Postcode</label>
                                {{ Form::text('postcode', null, [
                                    'id'          => 'postcode',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Postcode',
                                    'size'        => 5,
                                    'maxlength'  => 5
                                ]) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-lg-3">
                                <label>Status</label>
                                <div class="form-group">
                                    @include('common._select', [
                                        'id'           => 'status',
                                        'name'         => 'status',
                                        'data'         => $status
                                    ])
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div style="margin-top: 31px;">
                                    <a href="javascript:void(0);" id="btn-search" class="pull-right btn bg-teal-400 btn-raised legittRipple"><i class="icon-search4"></i> Search</a>
                                </div>
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="panel">
        <div class="panel-body table-responsive">
            <table class="table" id="delivery-area-table" data-page-length="10" width="100%">
                <thead>
                    <tr>
                        <th width="15%">Postcode</th>
                        <th width="15%">Province</th>
                        <th width="15%">District</th>
                        <th width="15%">Subdistrict</th>
                        <th width="10%">Status</th>
                        <th width="10%">Inventory Store</th>
                        <th width="10%">Price Store</th>
                        <th width="10%">Price Professional Store</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('footer_script')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('\App\Http\Requests\DeliveryAreaSearchRequest', '#search-form') !!}
{{ Html::script('js/delivery_area/index.js') }}
@endsection