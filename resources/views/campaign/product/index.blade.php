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
<li><a href="/campaign/{{ $campaign_id }}">{{ $campaign['slug'] }}</a></li>
<li class="active">Add Product</li>
@endsection

@section('header_script')
    {{ Html::style('assets/css/dropdown.custom.css') }}
@endsection

@section('content')
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
                                    {{ Form::text('item_id_text', null, [
                                        'id'          => 'item_id_text',
                                        'class'       => 'form-control',
                                        'placeholder' => 'Item ID'
                                    ]) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    @include('common._dropdown',['data' => $productCategoryList, 'defaultText' => 'select product category', 'group' => 'product', 'language' => 'th'])
                                </div>
                                <div class="col-md-6">
                                    @include('common._dropdown',['data' => $businessCategoryList, 'defaultText' => 'select business category', 'group' => 'business', 'language' => 'th'])
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            {{ Form::button('<i class="icon-search4"></i> Search', [
                                'type'  => 'submit',
                                'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple',
                                'id' => 'search-submit'
                            ]) }}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
 
    <div class="panel">
        <div class="panel-body table-responsive">
            <table class="table table-border-gray table-striped table-hover datatable-dom-position" id="product-mapping-table" data-page-length="10" width="100%">
                <thead>
                    <tr>
                        <th class="" width="5">
                            <input type="checkbox" class="check-all">
                        </th>
                        <th class="10" width="">No.</th>
                        <th class="15" width="">Item ID</th>
                        <th class="" >Product Name (TH)</th>
                        <th class="" >Product Name (EN)</th>
                        <th class="" width="10">Normal Price</th>
                        <th class="" width="10">Approval Status</th>
                        <th class="" width="10">Published</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="9" class="text-center">Loading ...</td>
                    </tr>
                </tbody>
            </table>
        </div>
         <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pull-right">
                        <div class="form-group">
                            {{ Form::button('<i class="icon-checkmark"></i> Save', [ 'type' => 'submit', 'class' => 'btn bg-primary-800 btn-raised btn-submit' ]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_script')
    <script type="text/javascript">
        $('body').on('click', '.btn-submit', function(event) {
            event.preventDefault();

            if(isChecked()){
                 //console.log(getCheckedId());
                 callAjax('POST', url, { product_id:getCheckedId() }, null, function(){
                    window.location = '/campaign/{{ $campaign_id }}';
                });
            }        
        });
    </script>
    @include('campaign.product._add_product_script')
    @include('common._dropdown_script')
@endsection