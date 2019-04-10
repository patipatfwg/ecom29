<?php
$scripts = [
	'sweetalert'
];
?>

@extends('layouts.epos.main')

@section('title', 'Invoice Generate')

@section('breadcrumb')
<li class="active">Invoice Generate</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Normal Tax Invoice Generate</h6>
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
				'class'  => 'form-horizontal',
				'method' => 'POST',
				'id'     => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            {{ Form::text('order_number', null, [
								'id'    => 'order_number',
								'name'  => 'order_number',
								'class' => 'form-control',
                                'placeholder' => 'Order Number'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                {{ Form::button('<i class="icon-search4"></i> Generate', [
                    'type'  => 'submit',
                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple loading'
                ]) }}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@section('footer_script')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script type="text/javascript">
$(function() {

    var loading = $("#search-form button.loading");

    $("#search-form").validate({
        errorElement: "span",
        errorClass: "help-block error-help-block",
        errorPlacement: function(r, e) {
            r.insertAfter(e.parent());
        },
        highlight: function(r, e) {
            $(r).closest(".form-group").addClass("has-error");
        },
        onkeyup: function(element, event){
            $(element).valid();
        },
        success: function(r) {
            $(r).closest(".form-group").removeClass("has-error").addClass("has-success")
        },
        focusInvalid: false,
        submitHandler: function(r) {

            loading.button("loading");

            $.ajax({
                type: 'POST',
                url: $("meta[name='root-url']").attr('content') + '/epos/invoice_generate_code',
                data: { orderNumber: $('#order_number').val() },
                success: function(dataOrder) {

                    loading.button("reset");

                    if (dataOrder.status) {

                        swal({
                            html: true,
                            title: "Are you sure?",
                            text: "You want to confirm to generate normal <br/> tax invoice of order " + dataOrder.orderNumber,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: '#DD6B55',
                            confirmButtonText: "Confirm",
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true
                        }, function() {

                            $.ajax({
                                type: 'POST',
                                url: $("meta[name='root-url']").attr('content') + '/epos/invoice_generate_oms',
                                data: { orderNumber: dataOrder.orderNumber },
                                success: function(dataOms) {
                                    if (dataOms.status) {
                                        window.location.href = $("meta[name='root-url']").attr('content') + '/epos/invoice?search_value=' + dataOrder.orderNumber;
                                    } else {
                                        swal({ title: dataOms.title, text: dataOms.message, type: 'error' });
                                    }
                                }
                            });
                        });

                    } else {

                        swal({ title: dataOrder.title, text: dataOrder.message, type: 'error' });
                    }
                }
            });
        },
        rules: {"order_number":{"laravelValidation":[["Required",[],"Order Number is required.",true],["Numeric",[],"Order Number must be a number.",false]]}}
    });
})
</script>
@endsection