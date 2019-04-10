<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="UTF-8"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <META HTTP-EQUIV="Content-Type"CONTENT="text/html; charset=windows-874"> -->
    <title>Document</title>

    <link href="/assets/css/invoice/main2.css" rel="stylesheet" media="all"/>
    <link href="/assets/css/invoice/layout2.css" rel="stylesheet" media="all"/>
    <!-- <link href="/assets/css/invoice/print.css" rel="stylesheet" media="print"/> -->

    <style>
        @include('template.invoice.style.lang')
    </style>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

</head>
<body>

    @foreach($invoices as $invoice)
    <div class="invoice">

        @include('template.invoice.header2', ['invoice' => $invoice])
        
        @include('template.invoice.pagination', ['invoice' => $invoice])
        
        @include('template.invoice.info', ['invoice' => $invoice])

        @include('template.invoice.reference', ['invoice' => $invoice])
        

    </div> 
    @endforeach

</body>
</html>