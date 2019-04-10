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

@section('title', 'Return Order Search')

@section('breadcrumb')
<li class="active">Return Order Search</li>
@endsection

@section('header_script')@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Return Order Search [IDM_OrderDetails]</h6>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>
    <div class="panel-body">
        <div class="col-lg-12">
            {!! Form::open([
                'method'      => 'get',
                'autocomplete' => 'off',
                'class'        => 'form-horizontal',
                'id'           => 'search-form'
            ]) !!}
            <div class="row">
                <div class="form-group">
                    {{--<div class="col-md-3">--}}
                        {{--{!! Html::decode(Form::label('order_number', 'Sale Order Number<span class="text-danger">*</span> : ')) !!}--}}
                    {{--</div>--}}
                    <div class="col-md-12">
                        <div class="input-group">
                            {{ Form::text('order_number', null, [
                                'id'          => 'order_number',
                                'name'        => 'order_number',
                                'class'       => 'form-control',
                                'placeholder' => 'Sale Order Number : MTH170124000001 [@OrderNo]'
                            ]) }}
                            <span class="input-group-addon"><i class="icon-pen6"></i></span>
                        </div>
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
        <h6 class="panel-title">Return Order Lists of Order #MTH170124000001 [@OrderNo]</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <table class="table table-border-gray table-striped datatable-dom-position" id="order-return-table" data-page-length="10" width="100%">
                    <thead>
                    <tr>
                        <th width="80">No.</th>
                        <th width="200">Return Number</th>
                        <th>Return Date</th>
                        <th>Return Amount</th>
                        <th>Return Channel</th>
                        <th width="50">Manage</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--<tr><td colspan="6" class="text-center"><i class="icon-spinner2 spinner"></i> Loading ...</td></tr>--}}
                    <tr>
                        <td width="80">1</td>
                        <td width="200">@InvoiceNo</td>
                        <td>@DateInvoiced</td>
                        <td>@TotalAmount</td>
                        <td>@ShipNode</td>
                        <td width="50">Manage</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_script')
    <script>
        <?php
        $js_array = json_encode($result['return_order_datatable']);
        echo "var dataSet = ". $js_array . ";\n";
        ?>
        $(document).ready(function() {
            $('#order-return-table').DataTable( {
                data: dataSet,
                "bPaginate": false, //hide pagination
                "bFilter": false, //hide Search bar
                "bInfo": false, // hide showing entries
                columns: [
                    { title: "Id" },
                    { title: "Order number" },
                    { title: "Date" },
                    { title: "Amount" },
                    { title: "Channel" },
                    { title: "Manage" }
                ]
            } );
        } );
    </script>
{{--{{ Html::script('js/epos/return_datatable.js') }}--}}
@endsection