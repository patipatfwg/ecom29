<?php
namespace App\Http\Controllers\EPOS\Models;

class Invoice
{
    // NS1:InvoiceHeader
    public $InvoiceNo;
    public $CreateDate;
    public $IssueDate;
    public $InvoiceType;
    public $LineSubTotal;
    public $MasterInvoiceNo;
    public $MasterInvoiceDate;
    public $OrderInvoiceKey;
    public $Reference1;
    public $TotalAmount;
    public $TotalStringThai;


    //NS1:Order
    public $BuyerUserId;
    public $CustomerContactID;
    public $CustomerFirstName;
    public $CustomerLastName;
    public $CustomerPhoneNo;
    public $DocumentType;
    public $EnterpriseCode;
    public $OrderDate;
    public $OrderHeaderKey;
    public $OrderNo;
    public $SearchCriteria1;
    public $SearchCriteria2;
    public $ShipNode;
    public $TaxPayerId;
    public $SubPaymentType;

    //NS1:OverallTotals
    public $AdditionalLinePriceTotal;
    public $GrandCharges;
    public $GrandDiscount;
    public $GrandShippingBaseCharge;
    public $GrandShippingCharges;
    public $GrandShippingDiscount;
    public $GrandShippingTotal;
    public $GrandTax;
    public $GrandTotal;
    public $HdrCharges;
    public $HdrDiscount;
    public $HdrShippingBaseCharge;
    public $HdrShippingCharges;
    public $HdrShippingDiscount;
    public $HdrShippingTotal;
    public $HdrTax;
    public $HdrTotal;
//    public $OverallTotalsLineSubTotal;
    public $ManualDiscountPercentage;
    public $PercentProfitMargin;
    public $NoteText;

    //NS1:PersonInfoShipTo
    public $ShipToAddressLine1;
    public $ShipToAddressLine2;
    public $ShipToAddressLine3;
    public $ShipToAddressLine4;
    public $ShipToAddressLine5;
    public $ShipToAddressLine6;
    public $ShipToCity;
    public $ShipToCountry;
    public $ShipToDayPhone;
    public $ShipToEMailID;
    public $ShipToFirstName;
    public $ShipToLastName;
    public $ShipToMiddleName;
    public $ShipToState;
    public $ShipToZipCode;

    //NS1:PersonInfoBillTo
    public $BillToAddressLine1;
    public $BillToAddressLine2;
    public $BillToAddressLine3;
    public $BillToAddressLine4;
    public $BillToAddressLine5;
    public $BillToAddressLine6;
    public $BillToCity;
    public $BillToCountry;
    public $BillToDayPhone;
    public $BillToEMailID;
    public $BillToFirstName;
    public $BillToLastName;
    public $BillToMiddleName;
    public $BillToState;
    public $BillToZipCode;

    //NS1:PersonInfo
    public $PersonInfoAddressLine1;
    public $PersonInfoAddressLine2;
    public $PersonInfoAddressLine3;
    public $PersonInfoAddressLine4;
    public $PersonInfoAddressLine5;
    public $PersonInfoAddressLine6;
    public $PersonInfoCity;
    public $PersonInfoCountry;
    public $PersonInfoDayPhone;
    public $PersonInfoEMailID;
    public $PersonInfoFirstName;
    public $PersonInfoLastName;
    public $PersonInfoMiddleName;
    public $PersonInfoState;
    public $PersonInfoZipCode;

    //NS1:PersonInfo
    public $Currency;

    //NS1:Extn
    public $ExtnMakroInvoiceNumber;
    public $ExtnNewInvoiceNumber;
    public $ExtnOldInvoiceNumber;
    public $ExtnRunningNumber;
    public $ExtnSettlementDate;
    public $ExtnStatus;

    //NS1:OrderLine
    public $OrderedQty;
    public $PrimeLineNo;
    public $OrderLineShipNode;
    public $Status;

    //NS1:Item
    public $ItemID;
    public $ItemName;
    public $ProductClass;
    public $UnitOfMeasure;

    //NS1:LinePriceInfo
    public $TaxableFlag;
    public $UnitPrice;
    public $SellingPrice;
    public $VatRate;
    public $SellingTotal;

    //NS1:PersonInfoShipTo
    public $PersonInfoShipToAddressLine1;
    public $PersonInfoShipToAddressLine2;
    public $PersonInfoShipToAddressLine3;
    public $PersonInfoShipToAddressLine4;
    public $PersonInfoShipToAddressLine5;
    public $PersonInfoShipToAddressLine6;
    public $PersonInfoShipToCity;
    public $PersonInfoShipToCountry;
    public $PersonInfoShipToDayPhone;
    public $PersonInfoShipToEMailID;
    public $PersonInfoShipToFirstName;
    public $PersonInfoShipToLastName;
    public $PersonInfoShipToMiddleName;
    public $PersonInfoShipToState;
    public $PersonInfoShipToZipCode;

    //NS1:Note
    public $NoteNoteText;

    //NS1:LineDetailTranQuantity
    public $PricingQty;
    public $Quantity;
    public $ShippedQty;
    public $TransactionalUOM;

    //NS1:LineCharge
    public $LineChargeChargeAmount;
    public $LineChargeChargeCategory;
    public $LineChargeChargeName;
    public $LineChargeChargePerLine;
    public $LineChargeChargePerUnit;
    public $LineChargeIsBillable;
    public $LineChargeIsDiscount;
    public $LineChargeIsShippingCharge;
    public $LineChargeOriginalChargePerLine;
    public $LineChargeOriginalChargePerUnit;
    public $LineChargeReference;

    //NS1:LineTax
    public $ChargeCategory;
    public $ChargeName;
    public $TaxName;
    public $TaxPercentage;

    //NS1:TaxSummaryDetail
    public $TaxSummaryDetailTax;
    public $TaxSummaryDetailTaxName;

    public $PrintCounter;

   //Complex Discount
    public $Complex_Title;
    public $Complex_Discount;

    //Simple Discount
    public $Simple_Discount;
    public $Simple_Discount_PerUnit;


}