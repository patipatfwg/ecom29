<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<Order BuyerUserId="" CustomerContactID="" CustomerEMailID="" CustomerFirstName="" CustomerLastName="" CustomerPhoneNo="" DocumentType="" EnterpriseCode="" OrderDate="" OrderHeaderKey="" OrderNo="" PaymentStatus="" ReqDeliveryDate="" ReqShipDate="" SearchCriteria1="" SearchCriteria2="" ShipNode="" Status="" TaxPayerId="">
    <PersonInfoShipTo AddressLine1="" AddressLine2="" AddressLine3="" AddressLine4="" AddressLine5="" AddressLine6="" City="" Country="" DayPhone="" EmailID="" FirstName="" LastName="" MiddleName="" State="" ZipCode=""/>
    <PersonInfoBillTo AddressLine1="" AddressLine2="" AddressLine3="" AddressLine4="" AddressLine5="" AddressLine6="" City="" Country="" DayPhone="" EmailID="" FirstName="" LastName="" MiddleName="" State="" ZipCode=""/>
    <PriceInfo Currency="" TotalAmount=""/>
    <OverallTotals GrandCharges="" GrandDiscount="" GrandTax="" GrandTotal=""/>
    <PaymentMethods>
        <PaymentMethod PaymentReference1="" PaymentReference2="" PaymentReference3="" PaymentReference4="" PaymentType="" TotalCharged=""/>
    </PaymentMethods>
    <OrderLines>
        <OrderLine OrderedQty="" OrderLineKey="" PrimeLineNo="" ReqDeliveryDate="" ReqShipDate="" ReservationID="" ShipNode="" Status="">
            <OrderLineReservations>
                <OrderLineReservation Quantity=""/>
            </OrderLineReservations>
            <Item ItemID="" UnitCost="" UnitOfMeasure=""/>
            <LineOverallTotals Charges="" Discount="" ExtendedPrice="" LineTotal="" Tax="" UnitPrice=""/>
            <PersonInfoShipTo AddressLine1="" AddressLine2="" AddressLine3="" AddressLine4="" AddressLine5="" AddressLine6="" City="" Country="" DayPhone="" EmailID="" FirstName="" LastName="" MiddleName="" State="" ZipCode=""/>
        </OrderLine>
    </OrderLines>
</Order>