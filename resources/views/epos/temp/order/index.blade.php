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
<li class="active">Order Search</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Order Search {{-- [IDM_OrderDetails] --}}</h6>
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
                'method'       => 'get',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="input-group">
                            {{ Form::text('order_number', null, [
                                'id'          => 'order_number',
                                'name'        => 'order_number',
                                'class'       => 'form-control',
                                'placeholder' => 'Sale Order Number {{-- [@OrderNo] --}}'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                {{ Form::button('<i class="icon-search4"></i> Search', array(
                    'type'  => 'submit',
                    'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
                )) }}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Order {{-- #MTH170124000001 --}}</h6>
        {{--<div class="heading-elements">--}}
            {{--<ul class="icons-list">--}}
                {{--<li><a data-action="collapse"></a></li>--}}
            {{--</ul>--}}
        {{--</div>--}}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-heading bg-gray">
                        <h6 class="panel-title">Order Summary</h6>
                    </div>
                    <div class="panel-body panel-order">
                        {{--{{ Form::hidden('mode', 'profile') }}--}}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_order_number', 'Order Number')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('order_number', ': '.$result['summary']['order_number'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_order_date', 'Order Date')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('order_date', ': '.$result['summary']['order_date'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_order_status', 'Order Status')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('order_status', ': '.$result['summary']['order_status'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_customer_name', 'Customer Name')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('customer_name', ': '.$result['summary']['customer_name'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_customer_mobile', 'Customer Mobile')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('customer_mobile', ': '.$result['summary']['customer_mobile'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_customer_email', 'Customer Email')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('customer_email', ': '.$result['summary']['customer_email'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_pickup_store', 'Pickup Store')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('pickup_store', ': '. $result['summary']['pickup_store'])) !!}
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
                        <h6 class="panel-title">Payment Status</h6>
                    </div>
                    <div class="panel-body panel-order">
                        {{--{{ Form::hidden('mode', 'profile_address') }}--}}
                        {{--{{ Form::hidden('profile_id', array_get($address, 'profile.address.0.id', '')) }}--}}
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_payment_type', 'Payment Type')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('payment_type', ': '.$result['payment']['payment_type'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_payment_ref', 'Payment Ref.')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('payment_ref', ': '.$result['payment']['payment_ref'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_payment_status', 'Payment Status')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('payment_status', ': '.$result['payment']['payment_status'])) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_total_amount', 'Total Amount')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('total_amount', ': '.$result['payment']['total_amount'].' '.$result['payment']['currency']. ' [@TotalCharged][@Currency]')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_payment_expired', 'Payment Expired')) !!}
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('payment_expired', ': ???')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="col-lg-12">
                            <div class="text-right">
                                {{ Form::button('<i class="icon-checkmark"></i> Update Payment', array(
                                    'type'  => 'submit',
                                    'class' => 'btn bg-primary-800 btn-raised btn-submit'
                                )) }}
                            </div>
                        </div>
                    </div>
                </div>
                {{--{{ Form::close() }}--}}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-heading bg-gray">
                        <h6 class="panel-title">Shipping Address</h6>
                    </div>
                    <div class="panel-body panel-shipping-address">
                        {{--{{ Form::hidden('mode', 'profile') }}--}}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_shipping_address_name',
                                    $result['shipping_address']['first_name'].' '.
                                    $result['shipping_address']['middle_name'].' '.
                                    $result['shipping_address']['last_name'].' '.
                                    $result['shipping_address']['phone']))
                                     !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_shipping_address',
                                    $result['shipping_address']['address_line1'].' '.
                                    $result['shipping_address']['address_line2'].' '.
                                    $result['shipping_address']['address_line3'].' '.
                                    $result['shipping_address']['address_line4'].' '.
                                    $result['shipping_address']['address_line5'].' '.
                                    $result['shipping_address']['address_line6'].' '.
                                    $result['shipping_address']['city'].' '.
                                    $result['shipping_address']['state'].' '.
                                    $result['shipping_address']['country'].' '.
                                    $result['shipping_address']['zip_code'])) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-heading bg-gray">
                        <h6 class="panel-title">Billing Address</h6>
                    </div>
                    <div class="panel-body panel-shipping-address">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_shipping_address_name',
                                    $result['billing_address']['first_name'].' '.
                                    $result['billing_address']['middle_name'].' '.
                                    $result['billing_address']['last_name'].' '.
                                    $result['billing_address']['phone']))
                                     !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Html::decode(Form::label('lb_shipping_address',
                                    $result['billing_address']['address_line1'].' '.
                                    $result['billing_address']['address_line2'].' '.
                                    $result['billing_address']['address_line3'].' '.
                                    $result['billing_address']['address_line4'].' '.
                                    $result['billing_address']['address_line5'].' '.
                                    $result['billing_address']['address_line6'].' '.
                                    $result['billing_address']['city'].' '.
                                    $result['billing_address']['state'].' '.
                                    $result['billing_address']['country'].' '.
                                    $result['billing_address']['zip_code'])) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading bg-gray">
                        <h6 class="panel-title">Shopping Cart</h6>
                    </div>
                    <table class="table table-border-gray table-striped datatable-dom-position" id="order-detail-table" data-page-length="10" width="100%">
                        <thead>
                        <tr>
                            <th width="80">NO.</th>
                            <th>Item Name</th>
                            <th>Unit Price</th>
                            <th>Qty</th>
                            <th width="80">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td width="80">1</td>
                            <td>[@ItemID]</td>
                            <td>[@UnitCost]</td>
                            <td>[@OrderedQty]</td>
                            <td width="80">[@Status]</td>
                        </tr>
                        <tr><td colspan="5" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('footer_script')
    <script>
        <?php
        $js_array = json_encode($result['shopping_cart_datatable']);
        echo "var dataSet = ". $js_array . ";\n";
        ?>
        $(document).ready(function() {
            $('#order-detail-table').DataTable( {
                data: dataSet,
                "bPaginate": false, //hide pagination
                "bFilter": false, //hide Search bar
                "bInfo": false, // hide showing entries
                columns: [
                    { title: "Id" },
                    { title: "Name" },
                    { title: "Price" },
                    { title: "Qty" },
                    { title: "Status" }
                ]
            } );
        } );
    </script>
{{--{{ Html::script('js/orders/datatable.js') }}--}}
@endsection