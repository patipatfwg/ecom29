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

@section('title', 'Order')

@section('breadcrumb')
<li>Refund List Search</li>
<li class="active">Refunds Detail</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Refund Details - Credit Note#1000261</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-heading bg-gray">
                        <h6 class="panel-title">Refund Summary</h6>
                    </div>
                    <div class="panel-body panel-order">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_credit_note_number', 'Credit Note Number')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('credit_note_number', ': 1000261 [@InvoiceNo]')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_created_date', 'Created Date')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('created_date', ': 06/02/2017 [@Createts]')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_refund_amount', 'Refund Amount')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('refund_amount', ': 10.00 [@TotalAmount]')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_refund_status', 'Refund Status')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_refund_status', ': Not Settled [@ExtnStatus]')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_refund_reason', 'Refund Reason')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('refund_reason', ': Return [???]')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_modified_date', 'Modified Date')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('modified_date', ': 06/02/2017 [@Modifyts]')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                {{--{{ Form::open([--}}
                    {{--'autocomplete' => 'off',--}}
                    {{--'id'           => 'form-profile-address',--}}
                    {{--'url'          => '/member/' . $result['online_customer_id'],--}}
                    {{--'method'       => 'PUT'--}}
                {{--]) }}--}}
                <div class="panel">
                    <div class="panel-heading bg-gray">
                        <h6 class="panel-title">Payment Summary</h6>
                    </div>
                    <div class="panel-body panel-order">
                        {{--{{ Form::hidden('mode', 'profile_address') }}--}}
                        {{--{{ Form::hidden('profile_id', array_get($address, 'profile.address.0.id', '')) }}--}}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_total_amount', 'Total Amount')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('total_amount', ': 1000.00 [???]')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_payment_type', 'Payment Type')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('payment_type', ': Pay@Store [@PaymentType]')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_installment_type', 'Installment Type')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('installment_type', ': -')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_payment_id', 'Payment ID')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('payment_id', ': -')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_request_id', 'Request ID')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('request_id', ': -')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_credit_card_no', 'Credit Card No.')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('credit_card_no', ': -')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_installment_date', 'Installment Date')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('installment_date', ': -')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--{{ Form::close() }}--}}
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="col-lg-12">
            <div class="text-right">
                {{ Form::button('<i class="icon-undo2"></i> Back', array(
                    'type'  => 'submit',
                    'class' => 'btn bg-teal-400 btn-raised legitRipple loading'
                )) }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer_script')
{{ Html::script('js/members/datatable.js') }}
@endsection