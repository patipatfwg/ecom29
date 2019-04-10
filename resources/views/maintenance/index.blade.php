<?php
$scripts = [
    'datetimepicker',
    'sweetalert',
    'bootstrap-select',
    'switch',
    'touchspin'
];
?>

@extends('layouts.main')

@section('title','Maintenance Page')

@section('breadcrumb')
    <li class="active"><a href="/maintenance">Maintenance</a></li>
@endsection

@section('header_script')
@endsection

@section('content')
    {!! Form::open([
        'autocomplete' => 'off',
        'id'           => 'form-submit',
        'class'        => 'form-horizontal',
        'route'        => [
            'maintenance.update'
        ],
        'method'       => 'PUT'
    ]) !!}

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                {{--<div class="col-lg-12">--}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="col-lg-12">
                                {!! Html::decode(Form::label('deploy_start_date', 'Deploy Start Date : <span class="text-danger">*</span>')) !!}
                                {{ Form::text('start_date', !empty($maintenance['start_datetime'])? date('d/m/Y H:i', strtotime($maintenance['start_datetime'])) : date('d/m/Y 00:00', strtotime('+1 day')), [
                                    'id' => 'txt_start_date',
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {!! Html::decode(Form::label('deploy_end_date', 'Deploy End Date : <span class="text-danger">*</span>')) !!}
                                {{ Form::text('end_date', !empty($maintenance['end_datetime'])? date('d/m/Y H:i', strtotime($maintenance['end_datetime'])) : date('d/m/Y 02:00', strtotime('+1 day')), [
                                    'id' => 'txt_end_date',
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="col-lg-12">
                                {!! Html::decode(Form::label('Disable_Checkout_Button_Before', 'Disable Checkout Button Before : <span class="text-danger">*</span>')) !!}
                                {{ Form::text('disable_value', isset($maintenance['value']) ? $maintenance['value'] : 30, [
                                    'id' => 'txt_disable_value',
                                    'class' => 'touchspin-postfix form-control',
                                ]) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <div class="col-lg-12">
                                {!! Html::decode(Form::label('status', 'Status : <span class="text-danger">*</span>')) !!}
                                <div class="checkbox-switch">
                                    {{ Form::checkbox('status', null, (isset($maintenance['status']) && $maintenance['status'] == 'active') ? true : false ,[
                                        'id'             => 'chk_status',
                                        'class'          => 'switch',
                                        'data-on-text'   => 'Publish',
                                        'data-off-text'  => 'Unpublish',
                                        'data-on-color'  => 'success',
                                        'data-off-color' => 'danger',
                                        'data-size'      => 'mini'
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <div class="pull-right">
                                    {{ Form::button('<i class="icon-checkmark"></i> Save', [
                                        'id'    => 'btn_submit',
                                        'type'  => 'submit',
                                        'class' => 'btn bg-primary-800 btn-raised'
                                    ]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

@section('footer_script')
    <script type="text/javascript">
        $(function () {
            $('.switch').bootstrapSwitch();
            // Postfix
            $(".touchspin-postfix").TouchSpin({
                min: 1,
                max: 60,
                step: 1,
                postfix: 'minute(s)'
            });
        });
    </script>

    @include('common._datetime_start_date_end_date', [
        'refer_start'   => '#txt_start_date',
        'refer_end'     => '#txt_end_date',
        'format'        => 'd/m/Y H:i',
        'formatDate'    => 'd/m/Y',
        'timepicker'    => true,
        'minDate_start' => 0,
        'editable'      => false
    ])
    {!! Html::script('assets/js/core/libraries/jquery_ui/full.min.js') !!}
    {!! Html::script('/vendor/jsvalidation/js/jsvalidation.js') !!}
    {!! JsValidator::formRequest('\App\Http\Requests\MaintenanceRequest', '#form-submit') !!}
@endsection
