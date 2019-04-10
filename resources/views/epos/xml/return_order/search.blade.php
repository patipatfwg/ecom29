<?='<?xml version="1.0" encoding="UTF-8"?>'?>
<Order {{ $shipNode }} xmlns="http://www.sterlingcommerce.com/documentation/YFS/getOrderList/input" DocumentType="0003">
    <OrderLine>
        <DerivedFrom OrderNo="{{ $orderNo }}"/>
    </OrderLine>
</Order>