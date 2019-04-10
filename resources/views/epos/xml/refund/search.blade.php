<?='<?xml version="1.0" encoding="UTF-8"?>'?>
<OrderInvoice xmlns="http://www.sterlingcommerce.com/documentation/YFS/getOrderInvoiceList/input"
              InvoiceType="{{$invoiceType}}"
              FromDateInvoiced="{{$fromDate}}"
              ToDateInvoiced="{{$endDate}}">
    <Extn ExtnStatus="{{$status == '' ? '' : $status}}"/>
    {{$shipNode}}
</OrderInvoice>