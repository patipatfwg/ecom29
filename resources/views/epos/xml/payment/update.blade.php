<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<Order xmlns="http://www.sterlingcommerce.com/documentation/YFS/PaymentUpdate/input"
       Action="MODIFY"
       DocumentType="{{$documentType}}"
       EnterpriseCode="{{$enterpriseCode}}"
       OrderNo="{{$orderNumber}}"
       PaymentStatus="{{$paymentStatus}}">
    <OrderHoldTypes>
        <OrderHoldType
                HoldType="PayAtStoreHold"
                Status="1300"
                ReasonText="PayAtStore Hold Resolved"/>
    </OrderHoldTypes>
    <Notes>
        <Note NoteText="PayAtStore Payment Hold is Resolved" />
    </Notes>
    <PaymentMethods>
        <PaymentMethod
                @if (isset($first_name))
                FirstName="{{$first_name}}"
                @endif
                @if (isset($last_name))
                LastName="{{$last_name}}"
                @endif
                MaxChargeLimit="{{$amount}}"
                PaymentTypeGroup="{{$payment_type_group}}"
                PaymentType="{{$payment_type}}"
                PaymentReference1="{{$orderNumber}}"
                PaymentReference3="{{$payment_reference3 }}"
                PaymentReference4="{{$payment_reference4}}"
                PaymentReference5="{{$payment_reference5}}">
            <PaymentDetails
                    ChargeType="CHARGE"
                    ProcessedAmount="{{$amount}}"
                    RequestAmount="{{$amount}}"
                    AuthorizationID="{{$depositInvoice}}" />
        </PaymentMethod>
    </PaymentMethods>
</Order>