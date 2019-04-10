<?php
$scripts = ['sweetalert'];
?>

@extends('layouts.epos.main')

@section('title', 'Invoice Search')

@section('breadcrumb')
    <li><a href="/epos/invoice">Invoice Search</a></li>
    <li class="active">Invoice Replace</li>
@endsection

@section('header_script')@endsection

@section('content')

    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Modify Cutomer Information - Order Number #{{ $order_number }} {{-- [IDM_ReplaceInvoice] --}}</h6>
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
                    'id'           => 'form-submit',
                    'method'       => 'PUT',
                    'url'          => '/epos/invoice/replace'
                ]) !!}

                <input type="hidden" name="order_invoice_key" id="orderInvoiceKey" value="{{$order_invoice_key}}" />
                <input type="hidden" name="replace_invoice_number" id="replaceinvoiceNumber" value="{{$replace_invoice_number}}" />
                <input type="hidden" name="order_number" id="orderNumber" value="{{$order_number}}" />
                <input type="hidden" name="store_id" id="store_id" value="{{$store_id}}" />
                <input type="hidden" name="payment_type" id="paymentType" value="{{$payment_type}}" />
                <input type="hidden" name="invoice_type" id="invoiceType" value="{{$invoice_type}}" />
                <input type="hidden" name="old_shop_name" value="{{$customerInfo->shopName}}" />
                <input type="hidden" name="old_tax_id" value="{{$customerInfo->taxId}}" />
                <input type="hidden" name="old_branch_id" value="{{$customerInfo->branchId}}" />
                <!-- <input type="hidden" name="old_phone" value="{{$customerInfo->phone}}" /> -->
                <input type="hidden" name="old_address_line_1" value="{{$customerInfo->addressLine1}}" />
                <input type="hidden" name="old_provinces" value="{{$customerInfo->province}}" />
                <input type="hidden" name="old_districts" value="{{$customerInfo->districts}}" />
                <input type="hidden" name="old_sub_districts" value="{{$customerInfo->sub_districts}}" />
                <input type="hidden" name="old_zipcode" value="{{$customerInfo->zipcode}}" />
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('first_name', 'Company / Personal Name<span class="text-danger">*</span> : ')) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                {{ Form::text('shop_name', $customerInfo->shopName, [
                                    'id'          => 'shop_name',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Company / Personal Name'
                                ]) }}
                                <span class="input-group-addon"><i class="icon-pen6"></i></span>
                            </div>
                            <!-- <div id="shop_name-error-100char" style="color:#D84315; font-size: 12px; margin-top: 8px;">Company or Personal Name must be less than or equal to 100 characters</div>
                            <div id="shop_name-error-specialcharacter" style="color:#D84315; font-size: 12px; margin-top: 8px;">Company or Personal name must not be included in { , } , < and ></div> -->
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
                                {{ Form::text('tax_id', $customerInfo->taxId, [
                                    'id'          => 'tax_id',
                                    'class'       => 'form-control number-only',
                                    'placeholder' => 'Tax ID',
                                    'maxlength'   => 13,
                                ]) }}
                                <span class="input-group-addon"><i class="icon-pen6"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('branch_id', 'Head Office / Branch ID<span class="text-danger">*</span> : ')) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                {{ Form::text('branch_id', $customerInfo->branchId, [
                                    'id'          => 'branch_id',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Head Office / Branch ID',
                                    'maxlength'   => 5,
                                ]) }}
                                <span class="input-group-addon"><i class="icon-pen6"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('phone', 'Tel. / Mobile Number<span class="text-danger">*</span> : ')) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                {{ Form::text('phone', $customerInfo->phone, [
                                    'id'          => 'phone',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Tel. / Mobile Number',
                                    'maxlength'   => 10,
                                ]) }}
                                <span class="input-group-addon"><i class="icon-pen6"></i></span>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('address_line_1', 'Address Line1<span class="text-danger">*</span> : ')) !!}
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                {{ Form::text('address_line_1', $customerInfo->addressLine1, [
                                    'id'          => 'address_line_1',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Address Line1'
                                ]) }}
                                <span class="input-group-addon"><i class="icon-pen6"></i></span>
                            </div>
                            <!-- <div id="address_line_1-error-100char" style="color:#D84315; font-size: 12px;">Address must be less than or equal to 100 characters</div>
                            <div id="address_line_1-error-specialcharacter" style="color:#D84315; font-size: 12px; margin-top: 8px;">Address must not be included in { , } , < and ></div> -->
                        </div>
                    </div> 
                </div>
                {{--<div class="row">--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="col-md-3">--}}
                            {{--{!! Html::decode(Form::label('address_line_2', 'Address Line2    : ')) !!}--}}
                        {{--</div>--}}
                        {{--<div class="col-md-6">--}}
                            {{--<div class="input-group">--}}
                                {{--{{ Form::text('address_line_2', $customerInfo->addressLine2, [--}}
                                    {{--'id'          => 'address_line_2',--}}
                                    {{--'class'       => 'form-control',--}}
                                    {{--'placeholder' => 'Address Line2'--}}
                                {{--]) }}--}}
                                {{--<span class="input-group-addon"><i class="icon-pen6"></i></span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('province', 'Province<span class="text-danger">*</span> : ')) !!}
                        </div>
                        <div class="col-md-6">
                            <!-- Default select -->
                            <select id="province" class="form-control" data-width="100%" name="province">
                                <option value=""></option>
                            </select>
                            <!-- /default select -->
                            <input type="hidden" name="province_text" id="provinceText" value="{{$customerInfo->province}}" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('districts', 'Districts<span class="text-danger">*</span> : ')) !!}
                        </div>
                        <div class="col-md-6">
                            <!-- Default select -->
                            <select id="districts" class="form-control" data-width="100%" name="districts">
                                <option value=""></option>
                            </select>
                            <!-- /default select -->
                            <input type="hidden" name="districts_text" id="districtsText" value="{{$customerInfo->districts}}" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group">
                        <div class="col-md-3">
                            {!! Html::decode(Form::label('sub_districts', 'Sub Districts<span class="text-danger">*</span> : ')) !!}
                        </div>
                        <div class="col-md-6">
                            <!-- Default select -->
                            <select id="sub_districts" class="form-control" data-width="100%" name="sub_districts">
                                <option value=""></option>
                            </select>
                            <!-- /default select -->
                            <input type="hidden" name="sub_districts_text" id="sub_districtsText" value="{{$customerInfo->sub_districts}}" />
                            {{--<input type="hidden" name="sub_districts_text" id="sub_districtsText" value="บ้านซ่ง" />--}}

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
                                {{ Form::text('zipcode', $customerInfo->zipcode, [
                                    'id'          => 'zipcode',
                                    'class'       => 'form-control',
                                    'placeholder' => 'Zipcode'
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
                            'id' => 'buttonSubmit',
                            'type' => 'submit',
                            'class' => 'pull-right btn bg-primary-800 btn-raised legitRipple legitRipple'
                        )) }}
                        <a id="btnReset" class="pull-right btn">Reset</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('footer_script')
    <script>

        var province = $('#province');
        var districts = $('#districts');
        var subDistricts = $('#sub_districts');
        var zipcode = $('#zipcode');
        var provinceText = $('#provinceText');
        var districtsText = $('#districtsText');
        var sub_districtsText = $('#sub_districtsText');
        var arr_zipcode = new Array;

//        province.prop('disabled', true);
//        districts.prop('disabled', true);
//        subDistricts.prop('disabled', true);
//        zipcode.prop('disabled', true);
        province.prop('disabled', false);
        districts.prop('disabled', false);
        subDistricts.prop('disabled', false);
        zipcode.prop('disabled', false);
        $.getJSON($("meta[name='root-url']").attr('content') + '/api/provinces', function(data) {
            province.empty().append('<option></option>');
            $.each(data, function (i, item) {
                if (item.name == provinceText.val()) {
                    province.append("<option value='"+ item.province_id +"' selected>"+ item.name +"</option>");
                } else {
                    if (item.name_en == provinceText.val()) {
                        province.append("<option value='"+ item.province_id +"' selected>"+ item.name +"</option>");
                    } else {
                        province.append($('<option>', {
                            value: item.province_id,
                            text : item.name
                        }));
                    }
                }
            });
            province.prop('disabled', false);
            province.trigger('change');
            districts.prop('disabled', false);
            districts.focus();
        });

        province.on('change', function() {
            var provinceId = this.value;
            provinceText.val(province.find("option[value='" + provinceId + "']").text());
            if (provinceId) {
                districts.prop('disabled', true);
                subDistricts.prop('disabled', true);
                $.getJSON($("meta[name='root-url']").attr('content') + '/api/districts/' + provinceId, function(data) {
                    districts.empty().append('<option></option>');
                    subDistricts.empty().append('<option></option>');

                    $.each(data, function (i, item) {
                        if (item.name == districtsText.val()) {
                            districts.append("<option value='"+ item.district_id +"' selected>"+ item.name +"</option>");
                        } else {
                            if (item.name_en == districtsText.val()) {
                                districts.append("<option value='"+ item.district_id +"' selected>"+ item.name +"</option>");
                            } else {
                                districts.append($('<option>', {
                                    value: item.district_id,
                                    text : item.name
                                }));
                            }
                        }
                    });
                    districts.prop('disabled', false);
                    districts.trigger('change');
                    subDistricts.prop('disabled', false);
                });
            }
        });

        districts.on('change', function() {
            var districtId = this.value;
            districtsText.val(districts.find("option[value='" + districtId + "']").text());
            if (districtId) {
                subDistricts.prop('disabled', true);
                $.getJSON($("meta[name='root-url']").attr('content') + '/api/districts/'+districtId+'/sub-districts', function(data) {
                    subDistricts.empty().append('<option></option>');

                    $.each(data, function (i, item) {
                        arr_zipcode[item.sub_district_id] = item.postcode;
                        if (item.name == sub_districtsText.val()) {
                            subDistricts.append("<option value='"+ item.sub_district_id +"' selected>"+ item.name +"</option>");

                            if (zipcode.val() == '') {
                                zipcode.val(item.postcode);
                            }
                        } else {
                            if (item.name_en == sub_districtsText.val()) {
                                subDistricts.append("<option value='"+ item.sub_district_id +"' selected>"+ item.name +"</option>");

                                if (zipcode.val() == '') {
                                    zipcode.val(item.postcode);
                                }

                            } else {
                                subDistricts.append($('<option>', {
                                    value: item.sub_district_id,
                                    text : item.name
                                }));
                            }
                        }
                    });
                    subDistricts.prop('disabled', false);
                    zipcode.prop('disabled', false);
                });
            }
        });

        subDistricts.on('change', function() {
            var subDistrictsId = this.value;
            sub_districtsText.val(subDistricts.find("option[value='" + subDistrictsId + "']").text());

            if (zipcode.val() == '') {
                zipcode.val(arr_zipcode[subDistrictsId]);
            }
        });

        $('#buttonSubmit').on('click', function (e) {
            e.preventDefault()
            // submint
            swal({
                title: "Are you sure?",
                text: "Are you sure want to modify customer information for order number #{{ $order_number }} \n(this action cannot be undone)",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Cancel",
                confirmButtonColor: '#DD6B55',
                confirmButtonText: "Confirm",
                closeOnConfirm: true,
                showLoaderOnConfirm: true
            },
            function(isConfirm){
                if (isConfirm) {
                    $('#form-submit').submit();
                }
            })
        })

    </script>
    {!! Html::script('vendor/jsvalidation/js/jsvalidation.js') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\InvoiceReplaceRequest', '#form-submit') !!}

@endsection