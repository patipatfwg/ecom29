<?php
$scripts = [
    'sweetalert'
];
?>
@extends('layouts.main')

@section('title','Normal Delivery Fee Configuration')

@section('breadcrumb')
    <li><a href="/{{ $url['normal']['index'] }}">Normal Delivery Fee</a></li>
    <li class="active"><a href="/{{ $url['normal']['edit'] }}">Edit</a></li>
@endsection

@section('header_script')
@endsection

@section('content')
    {{ Form::open([
        'autocomplete' => 'off',
        'id'           => 'form-delivery-fee-normal',
        'url'          => '/' . $url['normal']['edit'],
        'method'       => 'PUT'
    ]) }}
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Configuration</h6>
        </div>
        <div class="panel-body" style="padding-top: 0px;">
            <div class="row">
                <div class="col-lg-12">
                    <table id="edit-table" class="table">
                        <col width="20%" />
                        <col width="25%" />
                        <col width="20%" />
                        <col width="25%" />
                        <col width="10%" />
                        <thead>
                            <tr>
                                <th colspan="5" class="font-bold" style="border: 1px solid #FFFFFF !important;">Delivery Fee and Condition</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $eachFee)
                            <tr>
                                <td class="no-border">Minimum Threshold <span class="ic-red">*</span></td>
                                <td class="no-border">
                                    {{ Form::text('data[' . $eachFee['id'] .'][min]', $eachFee['value']['min'], [
                                        'id' => 'min' . $index,
                                        'class'     => 'form-control',
                                        'readonly' => ($index == 0)
                                        ]) }}
                                </td>
                                <td class="no-border">Delivery Fee Value <span class="ic-red">*</span></td>
                                <td class="no-border">
                                    {{ Form::text('data[' . $eachFee['id'] . '][fee]', $eachFee['value']['fee'], [
                                        'id' => 'fee' . $index,
                                        'maxlength' => 35,
                                        'class'     => 'form-control',
                                        'readonly' => false
                                        ]) }}
                                </td>
                                <td class="no-border">
                                    @if($index != 0)
                                    <span class="cursor-pointer"><i onclick="$(this).parent().parent().parent().remove();" class="icon-trash ic-red"></i></span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr id="td-add">
                                <td class="no-border" colspan="5">
                                    <button id="btn_add" onclick="addRow();" type="button" class="btn btn-default btn-raised legitRipple" data-toggle="tooltip" title="add more"><i class="icon-plus2"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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

<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('\App\Http\Requests\DeliveryFeeEditRequest', '#form-delivery-fee-normal') !!}
{{ Html::script('js/delivery_fee/normal/edit.js') }}

@include('common._call_ajax')

@endsection

