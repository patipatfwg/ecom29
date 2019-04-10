<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<style>
    body {
        font-family: 'THSarabun';
    }
    /*
    div[size="A4"] {
        background: white;
        width: 21cm;
        height: 29.7cm;
        display: block;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        padding: 8px;
    }
    */
    table {
        border-collapse: collapse;
    }
    .border-all {
        border-width: 1px;
        border-color: black;
        border-style: solid;
    }

    .border-right {
        border-right-width: 1px;
        border-right-color: black;
        border-right-style: solid;
    }
    .border-bottom {
        border-bottom-width: 1px;
        border-bottom-color: black;
        border-bottom-style: solid;
    }
    .border-top {
        border-top-width: 1px;
        border-top-color: black;
        border-top-style: solid;
    }
    .head-document {
        font-size: 15.5px;
        font-weight: bold;
    }
    .text-value {
        font-size: 14.5px;
    }
    .text-vertical-top {
        vertical-align: top;
    }
    .text-reprint {
        font-size: 13.5px;
    }
    .text-condition {
        font-size: 13px;
    }
    .head-title {
        font-size: 14.5px;
        color: #337cb5;
        padding: 4px 3px 4px 3px;
        line-height: 13px;
    }
    .text-company {
        line-height: 16px;
    }
    .head-content {
        font-size: 14.5px;
    }
    .item_detail {
        padding: 0px 3px 2px 3px;
        font-size: 14.5px;
    }
    .text-right {
        text-align: right;
    }
    .text-left {
        text-align: left;
    }
    .text-center {
        text-align: center;
    }
    .color-blue{
        color: #337cb5;
    }
    .img-signature {
        width: 113px;
        padding: 2px 20px 0px 20px;
    }
    .text-signature {
        padding: 12px 3px;
    }
    .text-sign-signature {
        padding: 12px 3px 32px 3px;
    }
    .background-column {
        background-color: #f2f2f2;
    }

</style>
<body>
@foreach($invoices as $invoice)
    <div size="A4">
        @include('pdf.header', ['invoice' => $invoice])

        @include('pdf.pagination', ['invoice' => $invoice])

        @include('pdf.info', ['invoice' => $invoice])

        @include('pdf.reference', ['invoice' => $invoice])

        @include('pdf.detail.main', ['invoice' => $invoice])

    </div>
@endforeach
</body>
</html>