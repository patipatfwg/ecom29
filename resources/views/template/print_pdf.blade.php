<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/invoice/main.css" rel="stylesheet" media="all"/>
    <link href="/assets/css/invoice/layout.css" rel="stylesheet" media="all"/>

</head>
<body>
    <div class="row">
        <div class="col-xs-1">test1</div>
        <div class="col-xs-1">test2</div>
        <div class="col-xs-1">test3</div>
        <div class="col-xs-1">test4</div>
        <div class="col-xs-1">test5</div>
        <div class="col-xs-1">test6</div>
        <div class="col-xs-1">test7</div>
        <div class="col-xs-1">test8</div>
        <div class="col-xs-1">test9</div>
        <div class="col-xs-1">test10</div>
        <div class="col-xs-1">test11</div>
        <div class="col-xs-1">test12</div>
    </div>
    @foreach($invoices as $invoice)

        @include('template.invoice.header', ['invoice' => $invoice])

    @endforeach
</body>
</html>