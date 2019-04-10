<?php
$scripts = ['sweetalert'];
?>

@extends('layouts.epos.main')

@section('title', 'Invoice Search')

@section('breadcrumb')
    <li><a href="/epos/invoice">Invoice Search</a></li>
    <li class="active">Invoice Preview</li>
@endsection

@section('header_script')
    <style type="text/css" media="print">
        .no-print { display: none; }
    </style>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-heading bg-gray">
            <h6 class="panel-title">Invoice #
                {{ $customer_invoice_number }}
            </h6>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="btn-group pull-right">
                    @if (!empty($replace_invoice_number))
                        @if ( $invoice_type != 'SHIPMENT' )
                            <a href="/epos/invoice/{{$order_invoice_key}}/{{$order_number}}/{{$replace_invoice_number}}/{{$payment_type}}/{{$invoice_type}}/{{$store_id}}/replace" class="btn bg-teal-400 btn-raised legitRipple legitRipple">
                                <i class="icon-pencil7"></i> Replace
                            </a>
                        @endif
                    @else
                        <a href="/epos/invoice/{{$order_invoice_key}}/{{$order_number}}/0/{{$payment_type}}/{{$invoice_type}}/{{$store_id}}/replace" class="btn bg-teal-400 btn-raised legitRipple legitRipple">
                            <i class="icon-pencil7"></i> Replace
                        </a>
                    @endif
                    <button type="button" id="printBtn" class="btn bg-primary-800 btn-raised legitRipple legitRipple"><i class="icon-shredder"></i>
                        @if ($re_print == '0')
                            Print
                        @else
                            Reprint
                        @endif
                    </button>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <iframe id="iframePreview"
                src="/epos/invoice/pdf/{{$order_invoice_key}}/{{$order_number}}#toolbar=0&zoom=135%"
                width="100%"
                height="2000"
                scrolling="auto"
                frameborder="1" ></iframe>
            </div>

        </div>
    </div>
@endsection

@section('footer_script')
    @parent
    <script src="{{asset('js/epos/srcdoc-polyfill.min.js')}}"></script>
    <script>
        
        var invoiceType = '{{$invoice_type}}';

        $('#printBtn').click(function(){
            if (invoiceType == 'SHIPMENT') {
                var iframe = document.getElementById('iframePreview');
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } else {
                swal({
                    title: "Are You Sure to Print Invoice?",
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonText: "Cancel",
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: "Confirm",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function(isConfirm){
                    //console.log({{$invoice_number}});
                    if (isConfirm) {
                        // inc counter
                        var url = '/epos/invoice/print/counter';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                orderInvoiceKey: '{{$order_invoice_key}}',
                                invoiceNumber: '{{$invoice_number}}',
                                customerInvoiceNumber: '{{$customer_invoice_number}}',
                                replaceInvoiceNumber: '{{$replace_invoice_number}}',
                                paymentType: '{{$payment_type}}',
                                invoiceType: '{{$invoice_type}}',
                                invoiceDate: '{{$invoice_date}}',
                                subtotal:    '{{$subtotal}}',
                                vat:         '{{$vat}}',
                                netamount:   '{{$net_amount}}',
                                shipping_fee:'{{$shipping_fee}}',
                                store_id:    '{{$store_id}}',
                                order_number: '{{$order_number}}',
                                order_date:   '{{$order_date}}',
                                payment_type: '{{$payment_type}}',
                                shop_name:    '{{$shop_name}}',
                                makro_member_card: '{{$makro_member_card}}',
                                tax_id:       '{{$tax_id}}',
                                branch_id:    '{{$branch_id}}',
                                address_line1:  '{{$address_line1}}',
                                mobile_phone:   '{{$mobile_phone}}',
                                provinces:      '{{$provinces}}',
                                districts:      '{{$districts}}',
                                sub_districts:  '{{$sub_districts}}',
                                zip_code:       '{{$zip_code}}'
                            },
                            success: function(data) {

                                if (data.status || data.success) {

                                    swal({
                                        title: "Finish",
                                        timer: 100,
                                        confirmButtonColor: '#66BB6A'
                                    });

                                    var iframe = document.getElementById('iframePreview');
                                    iframe.contentWindow.focus();
                                    iframe.contentWindow.print();

                                    // setTimeout(function () {
                                    //     window.location.reload();
                                    // }, 6000);
                                    //iframe.src = iframe.src;

                                } else {
                                    swal({ title: "Update fail", text: data.messages, type: 'error' });
                                }
                            }
                        });

                    }
                });
            }

        });
    </script>
@endsection