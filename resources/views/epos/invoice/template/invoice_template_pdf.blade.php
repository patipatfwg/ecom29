<html xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
{{--<html moznomarginboxes mozdisallowselectionprint>--}}

<head>
    <meta http-equiv=Content-Type content="text/html; charset=windows-874">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1990 9:00:00 GMT" />
    {{ Html::style('assets/css/pdf_template.css') }}
</head>

<body link="#0563C1" vlink="#954F72">
@for ($copy = 0; $copy <= 1; $copy++)
    @for ($page = 1; $page <= $totalDetail['page']; $page++)
        <table border=0 cellpadding=0 cellspacing=0 style='border-collapse:collapse;'>
            <tr>
                <!-- <td height=29 style="width: 10%;"></td> -->
                <td class=xl97 rowspan=2 colspan=3 style="line-height:10px;font-size:14px;width: 35%;padding-bottom:10mm">
                    {{ isset($company_name) ? $company_name : '' }}<br><br>
                    {{ isset($head_office) ? $head_office : '' }} {{ isset($tel) ? $tel : '' }} {{ isset($fax) ? $fax : '' }}<br><br>
                    {{ isset($makro_tax_id) ? $makro_tax_id : '' }}<br><br>
                    {{ isset($makro_record_no) ? $makro_record_no : '' }}
                </td>
                <td rowspan=2 colspan=2 style="text-align:center; padding:0; width: 30%;">
                    <img width=195 height=113 src="{{ $logo_path }}">
                </td>
                {{--Condition for Return or Replace Invoice--}}
                @if ($invoices[0][0]->ExtnOldInvoiceNumber !== '')
                    @if ($invoices[0][0]->ExtnRunningNumber > 0)          
                        <td style="text-align:center;">&nbsp;</td>
                        <td style="text-align:center;">&nbsp;</td>
                        <td style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                    
                        <td height=29 style="border:1px solid #000000;text-align:center;color:#5b9bd5;font-size:14px;">
                            @if ($copy == 0)
                                ต้นฉบับลูกค้า<br>
                                For customer
                            @else
                                สำเนาบริษัท<br>
                                For company
                            @endif
                        </td>
                    @else
                        <td style="text-align:center;">&nbsp;</td>
                        <td style="text-align:center;">&nbsp;</td>
                        <td style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                    
                        <td height=29 style="border:1px solid #000000;text-align:center;color:#5b9bd5;font-size:14px;">
                            @if ($copy == 0)
                                ต้นฉบับลูกค้า<br>
                                For customer
                            @else
                                สำเนาบริษัท<br>
                                For company
                            @endif
                        </td>
                    @endif
                @else
                    @if ($invoices[0][0]->ExtnRunningNumber > 0)                      
                        <td style="text-align:center;">&nbsp;</td>
                        <td style="text-align:center;">&nbsp;</td>
                        <td style="text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                    
                        <td style="border:1px solid #000000;text-align:center;color:#5b9bd5;font-size:14px;">
                            @if ($copy == 0)
                                ต้นฉบับลูกค้า<br>
                                For customer
                            @else
                                สำเนาบริษัท<br>
                                For company
                            @endif
                        </td>
                    @else
                        <td colspan=3 style='mso-ignore:colspan;  width:25%;'></td>
                        <td height=25 width=100 style="border:1px solid #000000;text-align:center;color:#5b9bd5;font-size:14px;">
                            @if ($copy == 0)
                                ต้นฉบับลูกค้า<br>
                                For customer
                            @else
                                สำเนาบริษัท<br>
                                For company
                            @endif
                        </td>
                    @endif
                @endif
                
            </tr>
            <tr>
                @if ($invoices[0][0]->ExtnOldInvoiceNumber !== '')
                    @if ($invoices[0][0]->ExtnRunningNumber > 0)
                        <td colspan=4 style='mso-ignore:colspan;text-align: right;font-size:14px;'>ใบแทนออกให้ครั้งที่ {{($invoices[0][0]->ExtnRunningNumber)}} วันที่ {{$full_print_date}} สาเหตุ ต้นฉบับสูญหาย<br><br>ลงชื่อ........................................</td>
                    @else
                        <td colspan=4 style='mso-ignore:colspan;font-size:14px;'>เป็นการยกเลิกและออกใบกำกับภาษีฉบับใหม่ แทนฉบับเดิมเลขที่ {{$invoices[0][0]->ExtnOldInvoiceNumber }}</td>
                    @endif
                @else
                    @if ($invoices[0][0]->ExtnRunningNumber > 0)
                        <td colspan=4 style='mso-ignore:colspan;text-align: right;font-size:14px;'>ใบแทนออกให้ครั้งที่ {{($invoices[0][0]->ExtnRunningNumber)}} วันที่ {{$full_print_date}} สาเหตุ ต้นฉบับสูญหาย<br><br>ลงชื่อ........................................</td>             
                    @else
                        <td colspan=4 style='mso-ignore:colspan;'></td>
                    @endif
                @endif
            </tr>
            
            <tr height=40>
                <td colspan=10 style="text-align:center;padding:0px 0px 0px 0px; font-size:18px;" class=xl118>
                    {{$invoiceTypeName['TH']}}<br>
                    {{$invoiceTypeName['EN']}}
                </td>
                {{--<td colspan=9 style="text-align:center;padding:0px 0px 0px 0px;" class=xl118>--}}
                    {{--<table border=0 cellpadding=0 cellspacing=0 width=100%>--}}
                        {{--<tr>--}}
                            {{--<td width="25%"></td>--}}
                            {{--<td width="50%" style="text-align:center;padding:0px 0px 0px 0px;" class=xl118>--}}
                                {{--{{$invoiceTypeName['TH']}}<br>--}}
                                {{--{{$invoiceTypeName['EN']}}--}}
                            {{--</td>--}}
                            {{--<td width="25%" style='mso-ignore:colspan; text-align: right;'>หน้าที่ {{$page}} จาก {{$totalDetail['page']}}</td>--}}
                        {{--</tr>--}}
                    {{--</table>--}}
                {{--</td>--}}
            </tr>
            <tr height=26 style='mso-height-source:userset;'>
                <td height=26 colspan=10 style='mso-ignore:colspan; text-align: right;font-size:14px'>หน้าที่ {{$page}} จาก {{$totalDetail['page']}}</td>
            </tr>
        </table>
    
        <table border=0 cellpadding=0 cellspacing=0 width=1100 style='border-collapse:collapse;table-layout:fixed;'>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl98 colspan=2 align=left style='mso-ignore:colspan;font-size:22px;'>ชื่อลูกค้า/ Customer Name</td>
                <td class=xl68 colspan=3 style='font-size:22px;'>{{ $order->search_criteria2 }}
                <!-- <td class=xl68>&nbsp;</td> -->
                <td class=xl102 colspan=2 align=left style='mso-ignore:colspan;font-size:22px'>สาขาที่ออกใบกำกับภาษี/ Branch<span style='mso-spacerun:yes'>&nbsp;</span></td>
                <td class=xl67 colspan=4 style="padding-left:20px;font-size:22px;border-right:1px solid #000000;">{{ $store_info[0]->store_legal_no }}</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl99 colspan=2 align=left style='mso-ignore:colspan;font-size:22px'>รหัสสมาชิกลูกค้า/ Customer No. </td>
                <td class=xl65 colspan=2 style='font-size:22px;'>{{ $order->makro_member_card }}</td>
                <td class=xl69>&nbsp;</td>
                <td class=xl103 colspan=2 align=left style='mso-ignore:colspan;font-size:22px;'>ที่อยู่/ Address<span style='mso-spacerun:yes'>&nbsp;</span></td>
                <td colspan=2 class=xl65 style='mso-ignore:colspan;padding-left:20px;font-size:22px;'>{{ $store_info[0]->name->th }}</td>
                <td colspan=2 class=xl69>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl99 colspan=2 align=left style='mso-ignore:colspan;font-size:22px;'>ที่อยู่/ Address</td>
                @if ($strlen == 0)
                    <td class=xl65 colspan=5 style='font-size:22px;'>{{ $address_1 }}</td>
                @else
                    <td class=xl65 colspan=3 style='font-size:22px;border-right:1px solid #000000;'>{{ $address_1 }}</td>
                    <td class=xl103></td>
                    <td class=xl93></td>
                @endif
                <td colspan=2 class=xl65 style='mso-ignore:colspan;padding-left:20px;font-size:22px;'>{{ isset($store_address[0]->address->th)? $store_address[0]->address->th : '' }} {{ isset($store_address[0]->district->th)? $store_address[0]->district->th : '' }}</td>
                <td colspan=2 class=xl69>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                {{--<td class=xl99>&nbsp;</td>--}}
                {{--<td class=xl65 style='mso-ignore:colspan'></td>--}}
                {{--<td class=xl65 style='mso-ignore:colspan'>--}}
                <td class=xl65 colspan="4" style='mso-ignore:colspan;border-left: .5px solid windowtext;font-size:22px;'>
                    {{ $address_2 }} {{ $order->additionalAddress->addressLine4 }} {{ $order->additionalAddress->city }} {{ $order->additionalAddress->state }} {{ $order->additionalAddress->zipCode }}
                </td>
                <td class=xl69></td>
                <td class=xl103></td>
                <td class=xl93></td>
                <td colspan=2 class=xl65 style='font-size:22px;mso-ignore:colspan;padding-left:20px'>{{ isset($store_address[0]->province->th)? $store_address[0]->province->th : '' }} {{ isset($store_address[0]->postcode)? $store_address[0]->postcode : '' }}</td>
                <td colspan=2 class=xl69>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl100 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>เลขประจำตัวผู้เสียภาษี/ Tax ID</td>
                <td class=xl70 colspan=2 style='font-size:22px;'>{{ isset($order->tax_payer_id) ? $order->tax_payer_id : '' }}</td>
                <td width=175 class=xl101 align=left style='font-size:22px;'>สาขา<span
                            style='mso-spacerun:yes'>&nbsp;</span><span style="color: #000000;font-size:22px;">
                            {{ isset($order->branch) ? $order->branch : '' }}
                </span></td>
                <td class=xl103 align=left style='font-size:22px;'>POS ID</td>
                <td class=xl93></td>
                {{--<td class=xl70 colspan=2 style='font-size:22px;mso-ignore:colspan;padding-left:20px'>{{ isset($store_info[0]->pos_id) ? $store_info[0]->pos_id : '' }}</td>--}}
                <td class=xl70 colspan=2 style='mso-ignore:colspan;padding-left:20px'>&nbsp;</td>
                <td class=xl70>&nbsp;</td>
                <td class=xl71>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl98 colspan=3 align=left style='font-size:22px;mso-ignore:colspan;'>สถานที่ส่งสินค้า/ Shipping address</td>
                <td class=xl68 colspan=2 style='border-top:none'>&nbsp;</td>
                <td class=xl98 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        เลขที่/  No.
                    @else
                        เลขที่ใบกำกับภาษี/Tax Invoice No.
                    @endif
                </td>
                <td class=xl67 style="font-size:22px;padding-left: 20px;">
                    @if ($invoices[0][0]->ExtnNewInvoiceNumber != '')
                        {{ $invoices[0][0]->ExtnNewInvoiceNumber }}
                    @else
                        {{ $invoices[0][0]->InvoiceNo }}
                    @endif
                </td>
                <td class=xl67>&nbsp;</td>
                <td class=xl67>&nbsp;</td>
                <td class=xl68>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl99 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>ที่อยู่/ Address</td>
                <td class=xl65 colspan=2>
                    {{--ALM-2556 [EPOS][Replace Invoice] When replace invoice from short form to long form, shipping address display incorrectly--}}
                    {{--@if ($order->tax_payer_id != '')--}}
                    {{--{{ $order->additionalAddress->addressLine1 }} {{ $order->additionalAddress->addressLine2 }} {{ $order->additionalAddress->addressLine4 }}--}}
                    {{--@else--}}
                    {{--{{ $invoices[0][0]->ShipToAddressLine1 }} {{ $invoices[0][0]->ShipToAddressLine2 }} {{ $invoices[0][0]->ShipToAddressLine4 }}--}}
                    {{--@endif--}}
                </td>
                <td class=xl69></td>
                <td class=xl99 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        วันที่ / Date
                    @else
                        วันที่ใบกำกับภาษี/Tax Invoice Date
                    @endif
                </td>
                <td class=xl65 style="font-size:22px;padding-left: 20px">
                    @if ($invoices[0][0]->SearchCriteria1 == 'CC' && (($invoices[0][0]->InvoiceType == 'INFO') || ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO')))
                        {{ $invoices[0][0]->IssueDate }}
                    @else
                        {{ $invoices[0][0]->CreateDate }}
                    @endif
                </td>
                <td class=xl65></td>
                <td class=xl65>&nbsp;</td>
                <td class=xl69>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29 ></td> -->
                <td class=xl99>&nbsp;</td>
                <td class=xl65 colspan=2 style='mso-ignore:colspan'></td>
                <td class=xl65 style='font-size:22px;mso-ignore:colspan'>
                    {{--@if ($order->tax_payer_id != '')--}}
                    {{--{{ $order->additionalAddress->city }} {{ $order->additionalAddress->state }} {{ $order->additionalAddress->zipCode }}--}}
                    {{--@else--}}
                    {{--{{ $invoices[0][0]->ShipToCity }} {{ $invoices[0][0]->ShipToState }} {{ $invoices[0][0]->ShipToZipCode }}--}}
                    {{--@endif--}}
                </td>
                <td class=xl69>&nbsp;</td>
                <td class=xl103 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>วันที่สั่งซื้อ/ Order Date</td>
                <td colspan=2 class=xl65 style='font-size:22px;mso-ignore:colspan;padding-left:20px'>{{ $order->orderDate }}</td>
                <td colspan=2 class=xl69>&nbsp;</td>
                
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl99 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>ชื่อผู้รับสินค้า/ Receiver</td>
                <td class=xl65 colspan=2>
                    {{--@if ($order->tax_payer_id != '')--}}
                    {{--{{ $order->search_criteria2 }}--}}
                    {{--@else--}}
                    {{--{{ $invoices[0][0]->ShipToFirstName }} {{ $invoices[0][0]->ShipToLastName }}--}}
                    {{--@endif--}}
                </td>
                <td class=xl69>&nbsp;</td>
                <td class=xl99 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>เลขที่สั่งซื้อ/ Order No.</td>
                <td colspan=2 class=xl65 style='font-size:22px;mso-ignore:colspan; padding-left:20px'>{{ $order_number }}</td>
                <td colspan=2 class=xl69>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl99 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>อีเมล์/ E-mail</td>
                <td class=xl65 colspan=2>
                    {{--@if ($order->tax_payer_id != '')--}}
                    {{--{{ isset($order->additionalAddress->email) ? $order->additionalAddress->email : '' }}--}}
                    {{--@else--}}
                    {{--{{ $invoices[0][0]->ShipToEMailID }}--}}
                    {{--@endif--}}
                </td>
                <td class=xl69>&nbsp;</td>
                <td class=xl99 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>วิธีการชำระเงิน/ Payment type</td>
                <td colspan=2 class=xl65 style='font-size:22px;mso-ignore:colspan;padding-left:20px'>{{ $invoices[0][0]->SearchCriteria1 }}</td>
                <td colspan=2 class=xl69>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <!-- <td height=29></td> -->
                <td class=xl100 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>เบอร์ติดต่อ/ TEL No.</td>
                <td class=xl70 colspan=2>
                    {{--@if ($order->tax_payer_id != '')--}}
                    {{--{{ $order->additionalAddress->phone }}--}}
                    {{--@else--}}
                    {{--{{ $invoices[0][0]->ShipToDayPhone }}--}}
                    {{--@endif--}}
                </td>
                <td class=xl71>&nbsp;</td>
                <td class=xl100 colspan=2 align=left style='font-size:22px;mso-ignore:colspan;'>เลขที่ใบรับมัดจำ/ Deposit receipt</td>
                <td class=xl70 colspan=2 style='font-size:22px;mso-ignore:colspan;padding-left:20px'>
                    {{--                @if ($invoices[0][0]->InvoiceType != 'INFO')--}}
                    @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        {{ $invoices[0][0]->MasterInvoiceNo }}
                    @endif
                </td>
                <td colspan=2 class=xl71>&nbsp;</td>
            </tr>

            {{--Condition for Credit note wording or normal wording--}}
            @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                <tr height=29 style='mso-height-source:userset;'>
                    <!-- <td height=29></td> -->
                    <td class=xl100 colspan=4 align=left style='font-size:22px;border-bottom:none;mso-ignore:colspan;border-left:none;'>อ้างถึง
                        ใบกำกับภาษีเลขที่<span style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='display:none'><span
                                    style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></span></td>
                    <td class=xl65 align=left style='font-size:22px;'>{{ $invoices[0][0]->MasterInvoiceNo }}</td>
                    <td class=xl65></td>
                    <td class=xl104 align=left style="font-size:22px;text-align: left;">ลงวันที่</td>
                    <td colspan=3 class=xl65 style='font-size:22px;mso-ignore:colspan'>{{ $invoices[0][0]->MasterInvoiceDate }}</td>
                    <td class=xl104 style='font-size:22px;'>(หน่วย:บาท)</td>
                </tr>
            @else
                <tr height=29 style='mso-height-source:userset;'>
                    <!-- <td height=29></td> -->
                    <td class=xl103 colspan=4 align=left style='font-size:22px;mso-ignore:colspan'>อ้างถึง
                        ใบแจ้งหนี้เลขที่<span
                                style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                    <td colspan=4 class=xl65 style='mso-ignore:colspan'></td>
                    <td colspan=2 class=xl104 style='font-size:22px;'>(หน่วย:บาท)</td>
                </tr>
            @endif
        </table>
        <table border=0 cellpadding=0 cellspacing=0 width=1096 style='border-collapse:collapse;table-layout:fixed;margin-left: 4px;'>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl105 width="60">ลำดับที่</td>
                <td class=xl106 width="90" style='border-left:none;'>รหัสสินค้า</td>
                <td width=430 colspan=2 class=xl106 style='border-right:.5pt solid black;'>รายละเอียด</td>
                <td class=xl105 width="90" style='border-left:none;'>จำนวน/น้ำหนัก</td>
                <td class=xl105 width="80" style='border-left:none;'>หน่วยบรรจุ</td>
                <td class=xl105 width="140" style='border-left:none;'>ราคาต่อหน่วย</td>
                <td class=xl105 width="80" style='border-left:none;'>รหัส ภ.พ.</td>
                <td class=xl105 width="160" style='border-left:none;'>จำนวนเงินรวม</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl107>ITEM</td>
                <td class=xl108 style='border-left:none;'>ARTICLE NO.</td>
                <td colspan=2 class=xl108 style='border-right:.5pt solid black;'>DESCRIPTION</td>
                <td class=xl107 style='border-left:none;'>QUANTITY</td>
                <td class=xl107 style='border-left:none;'>UNIT</td>
                <td class=xl107 style='border-left:none;'>UNIT PRICE</td>
                <td class=xl107 style='border-left:none;'>VAT CODE</td>
                <td class=xl107 style='border-left:none;'>TOTAL</td>
            </tr>

            @for ($i = 0; $i < $totalDetail['total'] + $totalDetail['fill']; $i++)
                @if ($totalDetail['line'][$i]['Page'] == $page)
                    @if ($totalDetail['line'][$i]['Type'] == 'INFO')
                        <tr height=52 style='mso-height-source:userset;'>
                            <td class=xl73>{{ $totalDetail['line'][$i]['Items'] }}</td>
                            <td class=xl76 style='border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['ID'] }}</td>
                            <td class=xl74 align=left>{{ $totalDetail['line'][$i]['Name'] }}</td>
                            <td class=xl75>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['Quantity'] }}</td>
                            <td class=xl73 style='border-left:none'>{{ $totalDetail['line'][$i]['Unit'] }}</td>
                            <td class=xl77 style='border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['SellPrice'] }}</td>
                            <td class=xl73 style='border-left:none'>{{ $totalDetail['line'][$i]['VATCode'] }}</td>
                            <td class=xl77 style='border-left:none'>{{ $totalDetail['line'][$i]['TotalPrice'] }}&nbsp;</td>
                        </tr>
                    @elseif ($totalDetail['line'][$i]['Type'] == 'ITEM')
                        <tr height=52 style='mso-height-source:userset;'>
                            {{--<td class=xl73 style='vertical-align:top;padding:5px 0px 5px 0px;'>{{ $totalDetail['line'][$i]['Items'] }}</td>--}}
                            {{--<td class=xl76 style='vertical-align:top;padding:5px 0px 5px 0px;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['ID'] }}</td>--}}
                            {{--<td class=xl74 colspan=2 style="vertical-align:top;white-space:normal;word-wrap: break-word;border-right:.5px solid windowtext;">&nbsp;{!! $totalDetail['line'][$i]['Name'] !!}</td>--}}
                            {{--<td class=xl73 style='vertical-align:top;padding:5px 0px 5px 0px;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['Quantity'] }}</td>--}}
                            {{--<td class=xl73 style='vertical-align:top;padding:5px 0px 5px 0px;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['Unit'] }}</td>--}}
                            {{--<td class=xl73 style='vertical-align:top;padding:5px 0px 5px 0px;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['SellPrice'] }}</td>--}}
                            {{--<td class=xl73 style='vertical-align:top;padding:5px 0px 5px 0px;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['VATCode'] }}</td>--}}
                            {{--<td class=xl77 style='vertical-align:top;padding:5px 0px 5px 0px;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['TotalPrice'] }}&nbsp;</td>--}}
                            <td class=xl73 style='vertical-align:top;'>{{ $totalDetail['line'][$i]['Items'] }}</td>
                            <td class=xl76 style='vertical-align:top;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['ID'] }}</td>
                            <td class=xl74 colspan=2 style="vertical-align:top;white-space:normal;word-wrap: break-word;border-right:.5px solid windowtext;">&nbsp;{!! $totalDetail['line'][$i]['Name'] !!}</td>
                            <td class=xl73 style='vertical-align:top;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['Quantity'] }}</td>
                            <td class=xl73 style='vertical-align:top;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['Unit'] }}</td>
                            <td class=xl73 style='vertical-align:top;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['SellPrice'] }}</td>
                            <td class=xl73 style='vertical-align:top;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['VATCode'] }}</td>
                            <td class=xl77 style='vertical-align:top;border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['TotalPrice'] }}&nbsp;</td>
                        </tr>
                    @elseif ($totalDetail['line'][$i]['Type'] == 'COMPLEX')
                        <tr height=52 style='mso-height-source:userset;'>
                            <td class=xl73>&nbsp;{{ $totalDetail['line'][$i]['Items'] }}</td>
                            <td class=xl74 style='border-left:none'>&nbsp;{{ $totalDetail['line'][$i]['ID'] }}</td>
                            <td class=xl72 style='border-top:none'>&nbsp;</td>
                            <td class=xl75>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>{{ $totalDetail['line'][$i]['Unit'] }}</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                        </tr>
                    @elseif ($totalDetail['line'][$i]['Type'] == 'SUMMARY_HEAD')
                        <tr height=52 style='mso-height-source:userset;'>
                            <td class=xl73>&nbsp;</td>
                            <td class=xl99 align=left style='border-left:none'>{{ $totalDetail['line'][$i]['ID'] }}</td>
                            <td class=xl140>{{ $totalDetail['line'][$i]['Name'] }}</td>
                            <td class=xl141>{{ $totalDetail['line'][$i]['Quantity'] }}</td>
                            <td class=xl156 style='border-left:none'>{{ $totalDetail['line'][$i]['Unit'] }}</td>
                            <td class=xl156 style='border-left:none'>{{ $totalDetail['line'][$i]['SellPrice'] }}</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                        </tr>
                    @elseif ($totalDetail['line'][$i]['Type'] == 'SUMMARY')
                        <tr height=52 style='mso-height-source:userset;'>
                            <td class=xl73>&nbsp;</td>
                            <td class=xl74 style='border-left:none'>&nbsp;
                                {{ $totalDetail['line'][$i]['ID'] }}
                            </td>
                            <td class=xl79>{{ $totalDetail['line'][$i]['Name'] }}</td>
                            @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                                <td class=xl144>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0.00</td>
                                <td class=xl122 style='border-left:none'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0.00</td>
                                <td class=xl122 style='border-left:none'><span style='mso-spacerun:yes'>&nbsp;</span>0.00</td>
                            @else
                                <td class=xl144>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $totalDetail['line'][$i]['Quantity'] }}</td>
                                <td class=xl122 style='border-left:none'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $totalDetail['line'][$i]['Unit'] }}</td>
                                <td class=xl122 style='border-left:none'><span style='mso-spacerun:yes'>&nbsp;</span>{{ $totalDetail['line'][$i]['SellPrice'] }}</td>
                            @endif

                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                        </tr>
                    @elseif ($totalDetail['line'][$i]['Type'] == 'LASTLINE')
                        <tr height=26 style='mso-height-source:userset;'>
                            <td class=xl73>&nbsp;</td>
                            <td class=xl74 style='border-left:none'>&nbsp;</td>
                            <td class=xl79>&nbsp;</td>
                            <td class=xl75>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                            <td class=xl73 style='border-left:none'>&nbsp;</td>
                        </tr>
                    @endif
                    @if ($i===((($totalDetail['total'] + $totalDetail['fill']) - (($totalDetail['page'] - $page) * $totalDetail['linePerPage'])) - 1))
                        @if (($totalDetail['page'] - $page) !== 0)
                            <tr height=26 style='mso-height-source:userset;'>
                                <td class=xl73>&nbsp;</td>
                                <td class=xl74 style='border-left:none'>&nbsp;</td>
                                <td class=xl79>&nbsp;</td>
                                <td class=xl75>&nbsp;</td>
                                <td class=xl73 style='border-left:none'>&nbsp;</td>
                                <td class=xl73 style='border-left:none'>&nbsp;</td>
                                <td class=xl73 style='border-left:none'>&nbsp;</td>
                                <td class=xl73 style='border-left:none'>&nbsp;</td>
                                <td class=xl73 style='border-left:none; text-align: right;'>&nbsp;มีต่อหน้า {{$page+1}}&nbsp;</td>
                            </tr>
                        @else
                            <tr height=26 style='mso-height-source:userset;'>
                                <td class=xl73>&nbsp;</td>
                                <td class=xl74 style='border-left:none'>&nbsp;</td>
                                <td class=xl79>&nbsp;</td>
                                <td class=xl75>&nbsp;</td>
                                <td class=xl73 style='border-left:none'>&nbsp;</td>
                                <td class=xl73 style='border-left:none'>&nbsp;</td>
                                <td class=xl73 style='border-left:none'>&nbsp;</td>
                                <td class=xl73 style='border-left:none'>&nbsp;</td>
                                <td class=xl73 style='border-left:none; text-align: right;'>&nbsp;&nbsp;</td>
                            </tr>
                        @endif
                    @endif
                @endif
            @endfor

        </table>
        <table border=0 cellpadding=0 cellspacing=0 width=1096 style='border-collapse:collapse;table-layout:fixed;margin-left: 4px;'>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl109 width="153" colspan=3 style='mso-ignore:colspan;'>จำนวนเงินที่ต้องชำระ (ตัวอักษร)</td>
                <!-- <td class=xl81 width="170">&nbsp;</td> -->
                <td class=xl81 colspan=3>&nbsp;
                    @if ($page == $totalDetail['page']) {{ $invoices[0][0]->TotalStringThai }} @endif
                </td>
                <td class=xl109 width="221" colspan=2 style='mso-ignore:colspan;border-right:.5pt solid black'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        {{--มูลค่าตามใบกำกับภาษีเดิม/ ORIGINAL AMOUNT--}}
                    @else
                        ราคาสินค้ารวมภาษีมูลค่าเพิ่ม/ TOTAL
                    @endif
                </td>
                <td class=xl83 width="160" style='border-left:none'>&nbsp;
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        @if ($page == $totalDetail['page']) &nbsp;@endif
                    @elseif ($invoices[0][0]->InvoiceType == 'SHIPMENT' || $invoices[0][0]->InvoiceType == 'INFO')
                        @if ($page == $totalDetail['page']) {{ number_format($deposit_summary['Deposit']['AMT_PRICE'], 2) }}&nbsp;@endif
                    @endif
                </td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl98 colspan=2 align=left style='mso-ignore:colspan;'>หมายเหตุ/ Remark</td>
                <td class=xl111 style='border-top:none;font-size:18px;'>เงื่อนไข/ Condition</td>
                <td class=xl84 style='border-top:none'>&nbsp;</td>
                <td class=xl84 style='border-top:none'>&nbsp;</td>
                <td class=xl85 style='border-top:none;'>&nbsp;</td>
                <td class=xl110 colspan=2 style='mso-ignore:colspan;border-right:.5pt solid black'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        {{--มูลค่าที่ถูกต้อง/ CORRECT AMOUNT--}}
                    @else
                        หักส่วนลด/ DISCOUNT
                    @endif
                </td>
                <td class=xl83 style='border-top:none;border-left:none'>&nbsp;
                    @if ($invoices[0][0]->InvoiceType == 'INFO')
                        @if ($page == $totalDetail['page'])0.00&nbsp;@endif
                    @elseif ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        @if ($page == $totalDetail['page']) &nbsp;@endif
                    @elseif ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        {{--                        @if ($page == $totalDetail['page']) {{ number_format($deposit_summary['Deposit']['AMT_DISCOUNT'], 2) }}&nbsp;@endif--}}
                        @if ($page == $totalDetail['page']) {{ number_format($complex_discount_total, 2) }}&nbsp;@endif
                    @endif
                </td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl79 colspan=2 style="">&nbsp;&nbsp;&nbsp;&nbsp;
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        @if ($page == $totalDetail['page']) ซื้อสินค้าผิดรายการ @endif
                    @endif
                </td>
                <td class=xl112 colspan=4 style='mso-ignore:colspan;border-right:.5pt solid black;font-size:18px'>1.ใบเสร็จรับเงินนี้จะสมบูรณ์เมื่อมีตราประทับบริษัทและลายเซ็นต์เจ้าหน้าที่ผู้ได้รับมอบอำนาจ<span
                            style='mso-spacerun:yes'>&nbsp;</span></td>
                <td class=xl109 colspan=2 style='border-top:none;border-left:none;border-right:.5pt solid black'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        {{--ผลต่าง/ DIFFERENCE--}}
                        จำนวนเงินรวม/ AMOUNT
                    @else
                        จำนวนเงินรวมสุทธิ/ AMOUNT
                    @endif
                </td>
 
                <td class=xl83 style='border-top:none;border-left:none'>&nbsp;
                    @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        @if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE'] - $complex_discount_total), 2) }}&nbsp;@endif
                    @else
                        @if ($page == $totalDetail['page']) {{ number_format($deposit_summary['Deposit']['AMT_PRICE'], 2) }}&nbsp;@endif
                    @endif
                    {{--@if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO')--}}
                    {{--@if ($page == $totalDetail['page']) {{ number_format($deposit_summary['Deposit']['AMT_PRICE'], 2) }}&nbsp;@endif--}}
                    {{--@elseif ($invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')--}}
                    {{--@if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE']), 2) }}&nbsp;@endif--}}
                    {{--@elseif ($invoices[0][0]->InvoiceType == 'SHIPMENT')--}}
                    {{--@if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE'] - $complex_discount_total), 2) }}&nbsp;@endif--}}
                    {{--@elseif ($invoices[0][0]->InvoiceType == 'INFO')--}}
                    {{--@if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE']), 2) }}&nbsp;@endif--}}
                    {{--@endif--}}
                </td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl79>&nbsp;</td>
                <td class=xl65></td>
                <td class=xl112 colspan=3 style='mso-ignore:colspan;font-size:18px'>และบริษัทได้รับชำระหนี้หรือเรียกเก็บเงินครบถ้วนแล้ว</td>
                <td class=xl75>&nbsp;</td>
                <td class=xl110 colspan=2 style='mso-ignore:colspan;border-right:.5pt solid black'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        ภาษีมูลค่าเพิ่ม/ VAT
                    @else
                        หักเงินมัดจำ/ DEPOSIT
                    @endif
                </td>
                <td class=xl83 style='border-top:none;border-left:none'>&nbsp;
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        @if ($page == $totalDetail['page']) {{ number_format($vat_grand_total, 2) }}&nbsp;@endif
                    @elseif ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        @if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE'] - $complex_discount_total), 2) }}&nbsp;@endif
                    @elseif ($invoices[0][0]->InvoiceType == 'INFO')
                        @if ($page == $totalDetail['page']) 0.00&nbsp;@endif
                    @endif
                </td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl79>&nbsp;</td>
                <td class=xl65></td>
                <td class=xl112 colspan=4 style='mso-ignore:colspan;border-right:.5pt solid black;font-size:18px'>2.บริษัทขอสงวนสิทธิ์ในกรณีที่เป็นบัตรเครดิตใบเสร็จจะสมบูรณ์เมื่อได้เรียกเก็บเงินจากธนาคารเรียบร้อยแล้ว</td>
                <td class=xl110 colspan=2 style='mso-ignore:colspan;border-right:.5pt solid black'>
                    จำนวนเงินก่อนภาษีมูลค่าเพิ่ม/ SUBTOTAL
                </td>
                <td class=xl83 style='border-top:none;border-left:none'>&nbsp;
                    @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        @if ($page == $totalDetail['page']) 0.00&nbsp;@endif
                    @else
                        @if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE']) - ($vat_grand_total), 2) }}&nbsp;@endif
                    @endif
                </td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl79>&nbsp;</td>
                <td class=xl65></td>
                <td class=xl112 colspan=3 style='mso-ignore:colspan;font-size:18px'>3.โปรดเก็บเอกสารไว้เป็นหลักฐานในการติดต่อกับทางบริษัท</td>
                <td class=xl75>&nbsp;</td>
                <td class=xl110 colspan=2 style='mso-ignore:colspan;border-right:.5pt solid black'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        &nbsp;
                    @else
                        ภาษีมูลค่าเพิ่ม/ VAT
                    @endif
                </td>
                <td class=xl83 style='border-top:none;border-left:none'>&nbsp;
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        &nbsp;
                    @elseif ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        @if ($page == $totalDetail['page']) 0.00&nbsp;@endif
                    @elseif ($invoices[0][0]->InvoiceType == 'INFO')
                        @if ($page == $totalDetail['page']) {{ number_format($vat_grand_total, 2) }}&nbsp;@endif
                    @endif
                </td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl88>&nbsp;</td>
                <td class=xl70>&nbsp;</td>
                <td class=xl113 colspan=4 style='mso-ignore:colspan;border-right:.5pt solid black;font-size:18px'>4.บริษัทจะรับคืนสินค้าภายใน 7 วัน ยกเว้นของสดรับคืนภายในวันที่ซื้อ และสินค้าที่รับคืนต้องอยู่ในสภาพเดิม</td>
                <td class=xl110 colspan=2 style='mso-ignore:colspan;border-right:.5pt solid black'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        มูลค่าลดหนี้/ CREDIT AMOUNT
                    @else
                        จำนวนเงินที่ต้องชำระ/ NET AMOUNT
                    @endif
                </td>
                <td class=xl91 style='border-top:none;border-left:none'>&nbsp;
                    {{--@if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')--}}
                    {{--@if ($page == $totalDetail['page']) 0.00&nbsp;@endif--}}
                    {{--@elseif ($invoices[0][0]->InvoiceType == 'SHIPMENT')--}}
                    {{--@if ($page == $totalDetail['page']) 0.00&nbsp;@endif--}}
                    {{--@elseif ($invoices[0][0]->InvoiceType == 'INFO')--}}
                    {{--@if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE']), 2) }}&nbsp;@endif--}}
                    {{--@endif--}}
                    @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        @if ($page == $totalDetail['page']) 0.00&nbsp;@endif
                    @else
                        @if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE']), 2) }}&nbsp;@endif
                    @endif
                </td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl153 colspan=3 style='mso-ignore:colspan;border-right:.5pt solid black;'>ประเภทการชำระเงิน (Payment type)</td>
                <td class=xl142 style='border-top:none;border-left:none;'>&nbsp;Cash</td>
                <td class=xl142 style='border-top:none;border-left:none;'>&nbsp;Credit Card</td>
                <td class=xl142 style='border-top:none;border-left:none;'>&nbsp;Coupon</td>
                <td class=xl83  style='border-top:none;border-left:none;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class=xl143 style='border-top:none;border-left:none;'>&nbsp;</td>
                <td class=xl147 style='border-top:none;border-left:none;'>&nbsp;ยอดเงินรวม</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl153 colspan=3 style='mso-ignore:colspan;border-right:.5pt solid black;'>จำนวนเงิน (Amount)</td>
                <td class=xl146 style='border-top:none;border-left:none'>&nbsp;
                    @if ($invoices[0][0]->SearchCriteria1 == 'PayAtStore')
                        @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                            &nbsp;
                        @else
                            @if ($page == $totalDetail['page'] && $invoices[0][0]->SubPaymentType == 'Cash') {{ number_format(($deposit_summary['Deposit']['AMT_PRICE']), 2)}}&nbsp;@endif
                        @endif
                    @endif
                </td>
                <td class=xl146 style='border-top:none;border-left:none'>&nbsp;
                 
                @if ($invoices[0][0]->SearchCriteria1 == 'CC')
                    @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        &nbsp;
                    @else
                        @if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE']), 2) }}&nbsp;@endif
                    @endif
                @elseif ($invoices[0][0]->SearchCriteria1 == 'PayAtStore' && $invoices[0][0]->SubPaymentType == 'Credit Card')  
                    @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        &nbsp;
                    @else
                        @if ($page == $totalDetail['page']) {{number_format(($deposit_summary['Deposit']['AMT_PRICE']), 2)}}&nbsp;@endif
                    @endif
                @endif
                </td>
                <td class=xl146 style='border-top:none;border-left:none'>&nbsp;</td>
                <td class=xl146 style='border-top:none;border-left:none'>&nbsp;</td>
                <td class=xl146 style='border-top:none;border-left:none'>&nbsp;</td>
                <td class=xl146 style='border-top:none;border-left:none'>&nbsp;
                    @if ($invoices[0][0]->InvoiceType == 'SHIPMENT')
                        @if ($page == $totalDetail['page']) &nbsp;@endif
                    @else
                        @if ($page == $totalDetail['page']) {{ number_format(($deposit_summary['Deposit']['AMT_PRICE']), 2) }}&nbsp;@endif
                    @endif
                </td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td colspan=2 class=xl106 style='border-right:.5pt solid black'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        ผู้รับคืนสินค้า / Receiver
                    @else
                        ผู้ส่งสินค้า / Sender
                    @endif
                </td>
                <td class=xl105 style='border-top:none;border-left:none'>
                    @if ($invoices[0][0]->InvoiceType == 'CREDIT_MEMO' || $invoices[0][0]->InvoiceType == 'RETURN' || $invoices[0][0]->InvoiceType == 'Return Invoiced')
                        ผู้ส่งคืนสินค้า / Sender
                    @else
                        ผู้รับสินค้า / Receiver
                    @endif
                </td>
                <td colspan=2 class=xl168 style='border-right:.5pt solid black'>ผู้รับเงิน / Collector</td>
                <td colspan=2 class=xl106 style='border-right:.5pt solid black;border-left:none'>ผู้ตรวจสอบ / Checked By</td>
                <td colspan=2 class=xl106 style='border-right:.5pt solid black;border-left:none'>ผู้ได้รับมอบอำนาจ / Authorized Signature</td>
            </tr>
            <tr height=20 style='mso-height-source:userset;'>
                <td class=xl99>&nbsp;</td>
                <td class=xl114>&nbsp;</td>
                <td class=xl115 style='border-left:none'>&nbsp;</td>
                <td class=xl103></td>
                <td class=xl114>&nbsp;</td>
                <td class=xl99 style='border-left:none'>&nbsp;</td>
                <td class=xl114>&nbsp;</td>
                <td colspan=2 class=xl140 style='border-right:.5pt solid black;border-left:
  none'>&nbsp;</td>
            </tr>
            <tr height=20 style='mso-height-source:userset;'>
                <td class=xl99>&nbsp;</td>
                <td class=xl114>&nbsp;</td>
                <td class=xl115 style='border-left:none'>&nbsp;</td>
                <td class=xl103></td>
                <td class=xl114>&nbsp;</td>
                <td class=xl99 style='border-left:none'>&nbsp;</td>
                <td class=xl114>&nbsp;</td>
                <td class=xl103></td>
                <td class=xl114>&nbsp;</td>
            </tr>
            <tr height=29 style='mso-height-source:userset;'>
                <td class=xl100 colspan=2 align=left style='mso-ignore:colspan;border-right:.5pt solid black;font-size:18px'>วันที่/ DATE</td>
                <td class=xl116 style='border-left:none;font-size:18px'>วันที่/ DATE</td>
                <td class=xl117 align=left style='border-left:none;font-size:18px'>วันที่/ DATE</td>
                <td class=xl101>&nbsp;</td>
                <td class=xl100 align=left style='border-left:none;font-size:18px'>วันที่/ DATE</td>
                <td class=xl101>&nbsp;</td>
                <td class=xl117 align=left style='border-left:none;font-size:18px'>วันที่/ DATE</td>
                <td class=xl101>&nbsp;</td>
            </tr>
        </table>

        @if ($copy == 1 && $page == $totalDetail['page'])
        @else
            <div style="page-break-after: always"></div>
            <br/>
        @endif
    @endfor
@endfor

</body>

</html>
