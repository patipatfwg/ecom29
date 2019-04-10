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

@section('title', 'Refund List Search')

@section('breadcrumb')
<li class="active">Refund List Search</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Refund List Search</h6>
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
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-4">
                        <div class="input-group">
                            {{ Form::text('start_date', null, [
                                'id'          => 'start_date',
                                'class'       => 'form-control',
                                'placeholder' => 'Create Date : [@FromDateInvoiced]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-calendar2"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            {{ Form::text('end_date', null, [
                                'id'          => 'end_date',
                                'class'       => 'form-control',
                                'placeholder' => 'End Date : [@ToDateInvoiced]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-calendar2"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <!-- Default select -->
                        <select class="form-control" data-width="100%" name="product[approve_status]">
                            <option value="">Refund Status [@ExtnStatus]</option>
                            <option value="1">New Sync</option>
                            <option value="2">Waiting</option>
                            <option value="3">Approve</option>
                        </select>
                        <!-- /default select -->
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    {{ Form::button('<i class="icon-search4"></i> Search', array(
                        'type'  => 'submit',
                        'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                    )) }}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Refund List</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-border-gray table-striped datatable-dom-position" id="coupons-table" data-page-length="10" width="160%">
                    <thead>
                    <tr>
                        <th width="80">No.</th>
                        <th width="200">Credit Note Number</th>
                        <th>Created Date</th>
                        <th>Refund Amount</th>
                        <th>Payment Type</th>
                        <th>Refund Reason</th>
                        <th>Modified Date</th>
                        <th>Status</th>
                        <th width="50">Manage</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td width="80">1</td>
                        <td width="200">@InvoiceNo</td>
                        <td>@Createts</td>
                        <td>@TotalAmount</td>
                        <td>@PaymentType</td>
                        <td>???</td>
                        <td>@Modifyts</td>
                        <td>???</td>
                        <td width="50">Manage</td>
                    </tr>
                    <tr><td colspan="9" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
{{ Html::script('js/epos/datatable.js') }}
@endsection