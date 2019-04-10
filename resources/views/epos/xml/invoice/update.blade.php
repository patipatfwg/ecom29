<?='<?xml version="1.0" encoding="UTF-8"?>'?>
<Order Action="MODIFY" DocumentType="{{$document_type}}" EnterpriseCode="TH" OrderNo="{{$order_number}}"
       TaxPayerId="{{$tax_payer_id}}" SearchCriteria2="{{$shop_name}}"
       xmlns="http://www.sterlingcommerce.com/documentation/YFS/OrderUpdate/input">
    <Notes>
        <Note NoteText="Updating Customer ID" />
    </Notes>
    <AdditionalAddresses>
        <AdditionalAddress AddressType="Tax">
            <PersonInfo AddressLine1="{{$addressLine1}}" AddressLine4="{{$addressLine4}}" AddressLine5="{{$addressLine5}}"
                        City="{{$city}}" Country="{{$country}}" State="{{$state}}" ZipCode="{{$zipcode}}" />
        </AdditionalAddress>
    </AdditionalAddresses>
</Order>