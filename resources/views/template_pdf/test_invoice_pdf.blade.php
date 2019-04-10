
<html lang="en">
<head>

    <title>Document</title>
    
    <style>
        .bg-red{
            background-color: red;
        }
        .bg-blue{
            background-color: blue;
        }
        .bg-green{
            background-color: green;
        }
        .bg-yellow{
            background-color: yellow;
        }
       
    </style>
    
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: whitesmoke;
            color: #337cb5;

            font-size: 12px;
            line-height: 5mm !important;
            font-family: "Garuda";
        }

        .font-read{
            font-size: 2em;
        }

        .nopadding {
            padding: 0 !important;
            margin: 0 !important;
        }

        .fl{
            float: left;
        }

        .fr{
            float: right;
        }

        .height-20{
            height: 20%;
        }

        .height-80{
            height: 80%;
        }

        .full-height{
            height: 100%;
        }

        .half-height{
            height: 50%;
        }

        .full-width{
            width: 100%;
        }

        .half-width{
            width: 50%;
        }

        .text-bold{
            font-weight: bold;
        }

        .black{
            color: black;
        }

        .invoice-grey{
            background: rgba(242, 242, 242, 1);
        }

        .border-all{
            border-width: 1px;
            border-color: black;
            border-style: solid; 
        }

        .border-top{
            border-top-width: 1px;
            border-top-color: black;
            border-top-style: solid; 
        }

        .border-bottom{
            border-bottom-width: 1px;
            border-bottom-color: black;
            border-bottom-style: solid; 
        }

        .border-left{
            border-left-width: 1px;
            border-left-color: black;
            border-left-style: solid; 
        }

        .border-right{
            border-right-width: 1px;
            border-right-color: black;
            border-right-style: solid; 
        }

        .invoice-header .copy-box {
            display: table;
            width: 21mm;
            height: 11mm;
        }

        .invoice-header .copy-box-middle{
            display: table-cell;
            vertical-align: middle;
        }

        .invoice-header .copy-box-inner{
            margin-left: auto;
            margin-right: auto; 
        }

        .invoice-header .contract-us{
            padding-top: 8mm;
            line-height: normal;
            font-size: 2.5mm !important;
        }

        .invoice-header .makro-logo{
            padding-top: 6mm;
            /* font-size: 2.8mm !important; */
        }

        .invoice-header .sign-panel{
            padding-top: 9mm;
        }

        .invoice-header .sign{
            padding-top: 3mm;
        }

        .invoice-detail-summary .remark .condition-text{
            font-size: 3mm;
            font-size: 2.1mm !important;
        }

        .nowrap{
            white-space: nowrap;
        }

        .break-word{
            word-wrap: break-word;
        }

        /* .invoice-name{
            position: fixed;
            width: 120mm;
            right: 13mm;
        } */

        .pd-l-1{
            padding-left: 1px !important;
        }

        .pd-r-1{
            padding-right: 1px !important;
        }

        .detail-column-6{
            padding-right: 1px !important;
        }

        .detail-column-8{
            padding-right: 1px !important;
        }

        .half-width.fl.nopadding.text-right.black{
            padding-right: 1px !important;
        }

        .detail-column-4.fl.full-height.border-right.text-right.black{
            padding-right: 1px !important;
        }

        .tb-dispaly{
            display: table; 
        }

        .va-middle{
            display: table-cell;
            vertical-align: middle;
        }

        /* Start: Layout.css */
        .invoice{
            width: 210mm;
            height: 297mm;
            padding: 5mm;
            margin: 5mm auto;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        /* === Start:Layout Height === */
        .invoice-header {
            height: 40mm;
            /* height: 60mm; */
        }

        .invoice-info{
            height: 58mm;
            /* height: 87mm; */
        }

        .invoice-detail {
            height: 176mm;
            /* height: 264mm; */
        }

        .invoice-line-break {
            height: 5mm;
            /* height: 7.5mm; */
        }

        .invoice-info-top{
            /* Parent Heigth 58mm*/
            /* height: 45%; */
            height: 26.1mm;
        }

        .invoice-info-bottom{
            /* Parent Heigth 58mm*/
            /* height: 55%; */
            height: 31.8mm;
        
        }

        .invoice-info-top .full-height{
            height: 26.1mm;
        }

        .invoice-info-bottom .full-height{
            height: 31.8mm;
        }


        .invoice-detail-summary {
            height: 43.18%;
        }

        .invoice-detail-summary {
            /* Parent height: 176mm; */
            /* height: 43.18%; */
            height: 75mm;
        }

        .invoice-info-line{
            /* Parent Heigth 26.1mm*/
            height: 20%
        }

        .invoice-info-left-side .invoice-info-line{
            /* Parent Heigth 26.1mm*/
            height: 5.22mm;
        }

        .invoice-info-left-side .invoice-info-line .full-height{
            /* Parent Heigth 26.1mm*/
            height: 5.22mm;
        }

        .invoice-info-right-side .invoice-info-line{
            /* Parent Heigth 26.1mm*/
            height: 5.22mm;
        }

        .invoice-info-right-side .invoice-info-line .full-height{
            /* Parent Heigth 26.1mm*/
            height: 5.22mm;
        }

        .invoice-info-address2-line{
            /* Parent Heigth 26.1mm*/
            /* height: 20% */
            height: 5.22mm;
        }

        .invoice-info-address2-line .full-height{
            /* Parent Heigth 26.1mm*/
            /* height: 20% */
            height: 5.22mm;
        }

        .invoice-info-address3-line{
            height: 60%
        }

        .invoice-info-address3-line{
            /* Parent Heigth 26.1mm*/
            /* height: 60% */
            height: 15.66mm;
        }

        .invoice-info-address3-line .full-height{
            /* Parent Heigth 26.1mm*/
            /* height: 20% */
            height: 15.66mm;
        }

        .invoice-info-bottom-line{
            height: 16.6%
        }

        .invoice-info-left-side .invoice-info-bottom-line{
            /* Parent Heigth 31.8mm*/
            height: 5.22mm;
        }

        .invoice-info-left-side .invoice-info-bottom-line .full-height{
            /* Parent Heigth 31.8mm*/
            height: 5.22mm;
        }

        .invoice-info-right-side .invoice-info-bottom-line{
            /* Parent Heigth 31.8mm*/
            height: 5.22mm;
        }

        .invoice-info-right-side .invoice-info-bottom-line .full-height{
            /* Parent Heigth 31.8mm*/
            height: 5.22mm;
        }

        .invoice-info-bottom-address-line{
            height: 33.2%
        }

        .invoice-info-left-side .invoice-info-bottom-address-line{
            /* Parent Heigth 31.8mm*/
            height: 10.55mm;
        }

        .invoice-info-left-side .invoice-info-bottom-address-line .full-height{
            /* Parent Heigth 31.8mm*/
            height: 10.55mm;
        }

        .invoice-detail-header{
            height: 6.25%;
        }

        .invoice-detail-header{
            /* Parent height: 176mm */
            /* height: 6.25%; */
            height: 11mm;
        }

        .invoice-detail-header .full-height{
            /* Parent height: 176mm */
            /* height: 6.25%; */
            height: 11mm;
        }

        .invoice-detail-header .half-height{
            /* Parent height: 176mm */
            /* height: 6.25%; */
            height: 5.5mm;
        }

        .invoice-detail-list{
            /* height: 50.57%; */
            height: 89mm;
        }

        .invoice-detail-list .item-lines{
            height: 89mm;
        }

        .invoice-detail-list .item-lines .line{
            height: 5.56%;
        }

        .invoice-detail-list .item-lines .line{
            height: 4.9mm;
        }

        .invoice-detail-list .item-lines .line .full-height{
            height: 4.9mm;
        }

        .invoice-detail-list .item-groups{
            height: 27.75%;
        }

        .invoice-detail-list .item-groups .line{
            height: 20%;
        }

        .invoice-detail-summary .line{
            /* Parent height: 75mm */
            /* height: 7.46%; */
            height: 5.5mm;
        }

        .invoice-detail-summary .line .full-height{
            height: 5.5mm;
        }

        .invoice-detail-summary .remark{
            /* Parent height: 75mm */
            /* height: 44.76%; */
            height: 33.57mm;
        }

        .invoice-detail-summary .remark .full-height{
            /* Parent height: 75mm */
            /* height: 44.76%; */
            height: 33.57mm;
        }

        .invoice-detail-summary .remark .remark-cell{
            /* Parent height: 33.57mm */
            /* height: 16.66%;  */
            height: 5.59mm;
        }

        .invoice-detail-summary .remark .condition .full-height{
            /* Parent height: 33.57mm */
            /* height: 16.66%;  */
            height: 5.59mm;
        }

        .invoice-detail-summary .remark .discount-label .full-height{
            /* Parent height: 33.57mm */
            /* height: 16.66%;  */
            height: 5.59mm;
        }

        .invoice-detail-summary .remark .discount-amount .full-height{
            /* Parent height: 33.57mm */
            /* height: 16.66%;  */
            height: 5.59mm;
        }

        .invoice-detail-summary .payment-cell{
            height: 16.66%;
        }

        .invoice-detail-summary .signature{
            height: 32.86%; 
        }

        .invoice-detail-summary .signature{
            /* Parent height: 75mm */
            /* height: 32.86%;  */
            height: 24.64mm;
        }

        .invoice-detail-summary .signature .full-height{
            /* Parent height: 75mm */
            /* height: 32.86%;  */
            height: 24.64mm;
        }

        .invoice-detail-summary .signature .height-80{
            /* Parent height: 75mm */
            /* height: 32.86%;  */
            height: 19.71mm;
        }

        .invoice-detail-summary .signature .height-20{
            /* Parent height: 75mm */
            /* height: 32.86%;  */
            height: 4.93mm;
        }

        /* === End:Layout Height === */

        /* === Start:Layout Width === */
        .detail-column-1{
            width: 5.52%;
        }

        .detail-column-2{
            width: 8.55%;
        }

        .detail-column-3{
            width: 35.17%;
        }

        .detail-column-4{
            width: 8.04%;
        }

        .detail-column-5{
            width: 7.55%;
        }

        .detail-column-6{
            width: 13.07%;
        }

        .detail-column-7{
            width: 8.3%;
        }

        .detail-column-8{
            width: 12.57%;
        }

        .detail-column-1-2{
            width: 14.2%;
        }

        .detail-column-2-3{
            width: 43.85%;
        }

        .detail-discount-column-header{
            width: 14.07%;
        }

        .detail-discount-column-description{
            width: 43.72%;
        }

        .invoice-info-left-side{
            width: 49.24%;
        }

        .invoice-info-right-side{
            width: 50.26%;
        }

        .invoice-detail-summary .amount-text{
            width: 65.33%;
        }

        .invoice-detail-summary .total-label{
            width: 21.55%; 
        }

        .invoice-detail-summary .total-amount{
            width: 12.57%; 
        }

        .invoice-detail-summary .remark .remark-label{
            width: 14.07%; 
        }

        .invoice-detail-summary .remark .condition{
            width: 50.9%;
        }

        .invoice-detail-summary .remark .discount-label{
            width: 21.65%; 
        }

        .invoice-detail-summary .remark .discount-amount{
            width: 12.71%; 
        }

        .invoice-detail-summary .payment-type{
            width: 29.65%; 
        }

        .invoice-detail-summary .payment-chanel{
            width: 35.48%; 
        }

        .invoice-detail-summary .payment-unused{
            width: 21.6%; 
        }

        .invoice-detail-summary .payment-total-amount{
            width: 12.67%; 
        }

        .invoice-detail-summary .signature .sign-sender{
            width: 29.8%;
        }

        .invoice-detail-summary .signature .sign-receiver{
            width: 23.55%;
        }

        .invoice-detail-summary .signature .original-sign-sender{
            width: 14.07%;
        }

        .invoice-detail-summary .signature .original-sign-receiver{
            width: 15.58%;
        }

        .invoice-detail-summary .signature .sign-collector{
            width: 23.55%;
        }

        .invoice-detail-summary .signature .sign-checker{
            width: 22.6%;
        }

        .invoice-detail-summary .signature .sign-authorizer{
            width: 23.6%;
        }
        /* === End:Layout Width === */
        /* End: Layout.css */

        html, body {
            width: 100%;
            height: 100%;
            /* zoom: 1; */
            /* background: transparent; */
            font-size: 2.35mm !important;
            line-height: 5mm !important;
        }

        .font-read{
            font-size: 2.7mm !important;
        }

        .makro-logo{
            /* font-size: 2.8mm !important; */
        }

        .condition-text {
            font-size: 1.8mm !important;
        }

        .contract-us {
            font-size: 3mm !important;
        }

        .invoice { 
            width: 100%;
            height: 100%;
            margin: 0 auto;
            background: transparent;
            box-shadow: unset;
        }

        .invoice-header {
            height: 40mm;
        }

        .invoice-info{
            height: 58mm;
        }

        .invoice-detail {
            height: 176mm;
        }

        .invoice-detail .full-height{
            height: 176mm;
        }

        .invoice-line-break {
            height: 5mm;
        }

        .invoice-line-break .full-height{
            height: 5mm;
        }

        .invoice-header .copy-box{
            width: 21mm;
            height: 11mm;
        }

        .invoice-header .contract-us{
            padding-top: 8mm;
        }

        .invoice-header .makro-logo{
            padding-top: 6mm;
        }

        .invoice-header .sign{
            padding-top: 3mm;
        }

        .invoice-name{
            position: fixed;
            width: 120mm;
            right: 13mm;
        }

        .text-center{
            text-align: center;
        }

        .text-right{
            text-align: right;
        }

        .text-left{
            text-align: left;
        }

        .col-xs-1,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9,.col-xs-10,.col-xs-11,.col-xs-12{
            float: left !important;
        }

        .col-xs-1{
            width: 8.33333333% !important;
        }

        .col-xs-2{
            width: 16.66666666% !important;
        }

        .col-xs-3{
            width: 25% !important;
        }

        .col-xs-4{
            width: 33% !important;
        }

        .col-xs-5{
            width: 41.66666666% !important;
        }

        .col-xs-6{
            width: 49.5% !important;
        }

        .col-xs-7{
            width: 58.33333332% !important;
        }

        .col-xs-8{
            width: 66.66666666% !important;
        }

        .col-xs-9{
            width: 75% !important;
        }

        .col-xs-10{
            width: 83.33333333% !important;
        }

        .col-xs-11{
            width: 91.66666666% !important;
        }

        .col-xs-12{
            width: 100% !important;
        }
    </style>

</head>
<body>

    @foreach($invoices as $invoice)
    <div class="invoice">

        @include('template_pdf.invoice.header', ['invoice' => $invoice])
        
        @include('template_pdf.invoice.pagination', ['invoice' => $invoice])
        
        @include('template_pdf.invoice.info', ['invoice' => $invoice])

        @include('template_pdf.invoice.reference', ['invoice' => $invoice])
        
        @include('template_pdf.invoice.detail.main', ['invoice' => $invoice])

    </div> 
    @endforeach

</body>
</html>