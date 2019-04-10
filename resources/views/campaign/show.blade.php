<?php
$scripts = [
  'nestable',
  'sweetalert',
  'datatables',
  'select2',
  'bootstrap-select'
];
?>

@extends('layouts.main')

@section('title', 'Campaign - Products')

@section('breadcrumb')
<li><a href="/campaign">Campaign</a></li>
<li class="active">{{ $campaign['slug'] }}</li>
@endsection

@section('header_script')
    {{ Html::style('assets/css/dropdown.custom.css') }}
@endsection

@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-4 control-label">
                            <label>Campaign Code : </label>
                        </div>
                        <div class="col-lg-8">
                            {{ $campaign['campaign_code'] }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 control-label">
                            <label>Campaign Name : </label>
                        </div>
                        <div class="col-lg-8">
                            {{ $campaign_name }}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                    
                    @if(($campaign['currentDateTimestamp'] <= $campaign['endDateTimestamp'] && $campaign['currentDateTimestamp'] >= $campaign['startDateTimestamp']) && $campaign['status'] == 'active' )

                    @else
                        <a href="{{ URL::to('campaign/' . $campaign_id . '/product/add') }}" class="btn bg-teal-400 btn-raised legitRipple pull-right">
                            <i class="icon-plus-circle2 position-left"></i> Add Product
                        </a>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Search</h6>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    {!! Form::open([
                        'autocomplete' => 'off',
                        'class'        => 'form-horizontal',
                        'id'           => 'search-form'
                    ]) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-6">
                                    {{ Form::text('full_text', null, [
                                        'id'          => 'full_text',
                                        'class'       => 'form-control',
                                        'placeholder' => 'Product Name'
                                    ]) }}
                                </div>
                                <div class="col-md-6">
                                    {{ Form::text('full_text', null, [
                                        'id'          => 'item_id_text',
                                        'class'       => 'form-control',
                                        'placeholder' => 'Item ID'
                                    ]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    @include('common._dropdown', ['data' => $productCategoryList, 'defaultText' => 'select product category', 'group' => 'product', 'language' => 'th'])
                                </div>
                                <div class="col-md-6">
                                    @include('common._dropdown', ['data' => $businessCategoryList, 'defaultText' => 'select business category', 'group' => 'business', 'language' => 'th'])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::button('<i class="icon-search4"></i> Search', [
                                'type'  => 'submit',
                                'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                            ]) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include('campaign.product._table')
@endsection

@section('footer_script')
    @include('common._dropdown_script')
    @include('campaign.product._footer_script')
@endsection