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
                @if ($replace_invoice_number != '')
                    {{ $replace_invoice_number }}
                @else
                    {{ $invoice_number }}
                @endif
            </h6>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="btn-group pull-right">
                    @if ($replace_invoice_number != '')
                        <a href="/epos/invoice/{{$invoice_number}}/{{$order_number}}/{{$replace_invoice_number}}/{{$payment_type}}/{{$invoice_type}}/{{$store_id}}/replace" class="btn bg-teal-400 btn-raised legitRipple legitRipple"><i class="icon-pencil7"></i> Replace</a>
                    @else
                        <a href="/epos/invoice/{{$invoice_number}}/{{$order_number}}/0/{{$payment_type}}/{{$invoice_type}}/{{$store_id}}/replace" class="btn bg-teal-400 btn-raised legitRipple legitRipple"><i class="icon-pencil7"></i> Replace</a>
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
                        style="background: #fff"
                        srcdoc="{{ $form_template }}"
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

        $('#printBtn').click(function(){

            swal({
                    title: "Are You Sure to Print Invoice?",
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

                        // inc counter
                        var url = '/epos/invoice/print/counter';
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                invoiceNumber: '{{$invoice_number}}',
                                replaceInvoiceNumber: '{{$replace_invoice_number}}',
                                paymentType: '{{$payment_type}}',
                                invoiceType: '{{$invoice_type}}',
                                invoiceDate: '{{$invoice_date}}',
                                subtotal:    '{{$subtotal}}',
                                vat:         '{{$vat}}',
                                netamount:   '{{$net_amount}}',
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

                                    var iframe = document.getElementById('iframePreview');
                                    iframe.contentWindow.focus();
 
                                    iframe.contentWindow.print();
 
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 3000);
                                    //iframe.src = iframe.src;

                                } else {
                                    swal({ title: "Update fail", text: data.messages, type: 'error' });
                                }
                            }
                        });

                    }
                });

        });
    </script>
@endsection