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
        'to-markdown',
        'showdown'
    ];
?>

@extends('layouts.main')

@section('title', 'Payment Option')

@section('breadcrumb')
    <li><a href="/{{ $url['index'] }}">Payment Option</a></li>
    <li class="active"><a href="/{{ $url['edit'] }}/{{ $id }}">Edit</a></li>
@endsection

@section('header_script')@endsection

@section('content')
{{ Form::open([
    'autocomplete' => 'off',
    'id'           => 'form-edit-payment-method',
    'url'          => '/' . $url['edit'] . '/' . $id,
    'method'       => 'PUT'
]) }}
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Setting</h6>
    </div>
    <div class="panel-body">
        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <p class="text-bold">Setting</p>
                    <hr class="header-hr-line" />
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <p style="margin: 17px auto;">Priority</p>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-group">
                    {{ Form::number('data[priority]', (isset($data['priority']) && $data['priority'] != '') ? $data['priority'] : 99, [
                        'id'            => 'priority',
                        'class'         => 'form-control',
                        'placeholder'   => 'Enter number 1-99 only',
                        'style'         => 'margin: 9px auto;',
                        'min'           => 1,
                        'max'           => 99
                    ]) }}
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <p class="text-right font-bold" style="margin: 17px auto;">Action</p>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <div class="checkbox checkbox-switch">
                        <label>
                            <input name="data[status]" type="checkbox" data-on-color="success" data-off-color="danger" data-on-text="Active" data-off-text="Inactive" class="switch" {{ $data['status'] == 'active' ? 'checked="checked"' : ''}} value="active">
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tabbable panel">
                    <ul class="nav nav-tabs bg-teal-400 nav-justified">
                        @foreach($language as $lang)
                        <li class="{{ $lang == 'th' ? 'active' : '' }}">
                            <a href="#tab-panel-{{ $lang }}" data-toggle="tab">{{ trans('form.title.'.$lang) }}</a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach($language as $lang)
                            @include('config.form._edit_payment_method_switch', [
                                'id' => $id,
                                'url' => $url,
                                'lang' => $lang
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-heading bg-gray" style="border-top: 1px solid #ddd;">
        <h6 class="panel-title">Configuration</h6>
    </div>
    <div class="panel-body">
        <!-- <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-12">
                    <p class="text-bold">Configuration</p>
                    <hr class="header-hr-line" />
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">Payment Gateway <span class="ic-red">*</span></p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        {{ Form::text('data[payment_gateway]', $data['payment_gateway'], [
                            'id'        => 'payment-gateway-' . $lang,
                            'class'     => 'form-control',
                            'readonly'  => true
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">Minimum Pay Amount <span class="ic-red">*</span></p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        {{ Form::text('data[min_amount]', $data['min_amount'], [
                            'id'            => 'min-' . $lang,
                            'class'         => 'form-control',
                            'readonly'      => false,
                            'placeholder'   => 'Enter 2 decimal places only'
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">Maximum Pay Amount</p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        {{ Form::text('data[max_amount]', $data['max_amount'], [
                            'id'            => 'max-' . $lang,
                            'class'         => 'form-control',
                            'readonly'      => false,
                            'placeholder'   => 'Enter 2 decimal places only. Leave it blank in case of unlimited pay amount'
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">% Payment Fee <span class="ic-red">*</span></p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        {{ Form::text('data[percent_of_charge]', $data['percent_of_charge'], [
                            'id'            => 'fee-' . $lang,
                            'class'         => 'form-control',
                            'readonly'      => false,
                            'placeholder'   => 'Enter 2 decimal places only'
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">VAT of Payment Fee <span class="ic-red">*</span></p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        {{ Form::text('data[vat_percentage]', $data['vat_percentage'], [
                            'id'            => 'var-' . $lang,
                            'class'         => 'form-control',
                            'readonly'      => false,
                            'placeholder'   => 'Enter 2 decimal places only'
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="form-group">
                        <p style="margin: 8px auto;">% W.H. TAX <span class="ic-red">*</span></p>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="form-group">
                        {{ Form::text('data[withholding_tax]', $data['withholding_tax'], [
                            'id'            => 'tax-' . $lang,
                            'class'         => 'form-control',
                            'readonly'      => false,
                            'placeholder'   => 'Enter 2 decimal places only'
                        ]) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="col-lg-12">
            <div class="text-right">
                {{ Form::button('<i class="glyphicon glyphicon-ok"></i> Save', [
                    'type'  => 'submit',
                    'id' => 'btn-submit',
                    'class' => 'btn btn-primary btn-raised legitRipple margin-right-10 margin-left-10'
                ]) }}
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
@endsection

@section('footer_script')
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript" src="/assets/js/plugins/forms/styling/switch.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('\App\Http\Requests\ConfigPaymentMethodEditRequest', '#form-edit-payment-method') !!}

<script type="text/javascript">
$(".switch").bootstrapSwitch();
CKEDITOR.replace("description_th");
CKEDITOR.replace("description_en");

for (var i in CKEDITOR.instances) {
    CKEDITOR.instances[i].on('change', function() {
        // $("#" + i).html(CKEDITOR.instances[i].getData());
        // if($(this).attr('id') == '1'){
        //     $("#description_th").html(CKEDITOR.instances.description_th.getData());
        // } else if($(this).attr('id') == '2'){
        //     $("#description_en").html(CKEDITOR.instances.description_en.getData());
        // }
    });
}

$("#btn-submit").click(function(event){
    $(this).button("loading");
    $("#description_th").html(CKEDITOR.instances.description_th.getData());
    $("#description_en").html(CKEDITOR.instances.description_en.getData());
    event.preventDefault(event);
    var form = $("#form-edit-payment-method");
    var id = form.attr('id');
    var url = form.attr('url');
    var method = form.attr('method');
    var data = form.serialize();

    form.valid();
    callAjax(method, url, data, null, function(){
        location.reload();
    },
    null,
    function(){
        $("#btn-submit").button('reset');
    });
});
</script>

@include('common._call_ajax')

@endsection

