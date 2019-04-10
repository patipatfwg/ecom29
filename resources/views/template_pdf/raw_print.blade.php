<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <META HTTP-EQUIV="Content-Type"CONTENT="text/html; charset=windows-874">
    <title>Document</title>

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" media="screen"/>
    <link href="/assets/css/invoice/test/main.css" rel="stylesheet" media="all"/>
    <link href="/assets/css/invoice/test/layout.css" rel="stylesheet" media="all"/>
    <link href="/assets/css/invoice/test/print.css" rel="stylesheet" media="print"/>

</head>
<body>

    @foreach($invoices as $invoice)
    <div class="invoice">

        @include('template.invoice.header', ['invoice' => $invoice])
        
        @include('template.invoice.pagination', ['invoice' => $invoice])
        
        @include('template.invoice.info', ['invoice' => $invoice])

        @include('template.invoice.reference', ['invoice' => $invoice])
        
        @include('template.invoice.detail.main', ['invoice' => $invoice])

    </div> 
    @endforeach

</body>
</html>