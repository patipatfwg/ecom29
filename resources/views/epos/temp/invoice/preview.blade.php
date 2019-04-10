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

@section('title', 'Invoice Search')

@section('breadcrumb')
<li>Invoice Search</li>
<li class="active">Preview</li>
@endsection

@section('header_script')
    <style type="text/css" media="print">
        .no-print { display: none; }
    </style>
@endsection

@section('content')
<div class="panel">
    <div class="panel-heading bg-gray">
        <h6 class="panel-title">Invoice #1000259</h6>
    </div>
    <div class="panel-body">
        <div class="row">
            {{ Form::button('<i class="icon-shredder"></i> Print', array(
                //'type'  => 'submit',
                'id' => 'printBtn',
                'class' => 'pull-right btn bg-primary-800 btn-raised legitRipple legitRipple'
            )) }}
            {{ Form::button('<i class="icon-pencil7"></i> Replace', array(
                //'type'  => 'submit',
                'class' => 'pull-right btn bg-teal-400 btn-raised legitRipple legitRipple'
            )) }}
        </div>
        <div class="row" style="margin-top: 20px;">
            {{--<div class="col-md-8 col-md-offset-1">--}}
                <iframe id="iframePreview"
                        src="{{url('epos/invoice/print/template/1')}}"
                        width="1060"
                        height="2000"
                        scrolling="auto"
                        frameborder="1" ></iframe>
            {{--</div>--}}
        </div>

    </div>
</div>
@endsection

@section('footer_script')
{{--{{ Html::script('js/coupons/datatable.js') }}--}}
    <script>
        $('#printBtn').click(function(){
            var iframe = document.getElementById('iframePreview');

            // var content = iframe.contentDocument.body;
            // var iframeDocument = iframe.contents().find('html').html();
            iframe.contentWindow.focus();
            iframe.contentWindow.print();

            iframe.src = iframe.src;

            /*
             var content = document.getElementById('iframePreview').contentDocument.body.innerHTML;
             // var iframeDocument = iframe.contents().find('html').html();

             var iframe = document.getElementById('iframeToPrint').contentWindow;
             iframe.document.open();
             iframe.document.write(content);
             iframe.document.close();
             iframe.focus();
             iframe.print();
             return false;
             */
        });
    </script>
@endsection