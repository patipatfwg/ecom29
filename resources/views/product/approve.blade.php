<?php
    $scripts = [
    	'angular',
        'datatables',
        'nestable',
        'sweetalert',
        'multi',
        'select2',
    	'inputupload',
		'dropzone',
        'ckeditor',
        'sortable',
        'datetimepicker',
        'showdown',
        'to-markdown',
    ];
    ?>
@extends('layouts.main')
@section('title', 'Product')
@section('breadcrumb')
<li class="active">Product</li>
@endsection
@section('header_script')
	{{ Html::style('assets/css/dropdown.custom.css') }}
@endsection
@section('content')

<script type="text/javascript" src="/assets/js/core/libraries/jquery/2.1.4/jquery.min.js"></script>
<div ng-app="productApp">
    <div ng-controller="productController">
        <form id="form-submit" class="form-horizontal" autocomplete="off" method="PUT">
            <input type="hidden" name="productId" id="productId" value="{{ $product_id }}">
            <input type="hidden" name="language" id="language" value="{{app()->getLocale()}}">

            @include('product.compareRms',[
                'editAble' => $editAble
            ]);

            @include('product.compareOnline',[
                'editAble' => $editAble
            ])

            @include('product.compareStatus',[
                'editAble' => $editAble
            ])

            @include('product.comparePricing',[
                'editAble' => $editAble
            ])
            
            <div id="brand">
                @include('product.productBrand')
            </div>
            <div id="categories">
                @include('product.businessCategory')
            </div>
            <div id="categories">
                @include('product.productCategory')
            </div>
			<div id="attribute">
                @include('product.attribute')
            </div>
            <div id="attribute">
                @include('common._images_upload',[
                    'hidden' => true
                ])
            </div>
            <div class="modal fade" id="rejectModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Reject</h4>
                        </div>
                        <div class="modal-body">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="comment">Reject Reason:</label>
                                    <textarea class="form-control" rows="5" name="rejectReason"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            {{ Form::button('<i class="icon-checkmark"></i> Reject', [
                            'class' => 'btn bg-primary-800 btn-raised btn-reject',
                            'style' => 'margin-left:20px;',
                            'data-toggle' => 'modal',
                            'data-target' => '#rejectModal',
                            ]) }}
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="setStatus" name="setStatus" value="">
            <div class="pull-right">
                <div class="col-lg-12">
                    <div class="form-group">
                        {{ Form::button('<i class="icon-checkmark"></i> Approve', [
                        'class' => 'btn bg-primary-800 btn-raised btn-approve'
                        ]) }}

                        {{ Form::button('<i class="icon-checkmark"></i> Reject', [
                        'class' => 'btn bg-primary-800 btn-raised',
                        'style' => 'margin-left:20px;',
                        'data-toggle' => 'modal',
                        'data-target' => '#rejectModal',
                        ]) }}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('footer_script')

    {!! Html::script('js/products/datetime_picker.js') !!}
    {!! Html::script('assets/js/plugins/forms/tags/tagsinput.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/tags/tokenfield.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switchery.min.js') !!}
    {!! Html::script('assets/js/plugins/forms/styling/switch.min.js') !!}
    {!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}

    @include('product._footer_script')
    @include('common._dropdown_script')
    @include('common._seo_script',[
        'id' => 'productIntermediate'
    ])

<script type="text/javascript">
    $(".switch").bootstrapSwitch();
</script>
<script type="text/javascript">  </script>
@endsection