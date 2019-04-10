<table class="border-all text-value" style="width:100%;">
    <tbody>
    <!-- Not Data Customer Name 2 -->
    @if( empty($invoice['information']['customer_name2']) )
        <tr>
            <!-- Customer : Name 1 -->
            <td width="21%" class="head-title">ชื่อลูกค้า/ Customer Name</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['customer_name1'])? $invoice['information']['customer_name1'] : '' }}</td>
            <!-- End Customer : Name 1 -->
            <!-- Makro Store : Type -->
            <td width="22%" class="head-title">
            @if($invoice['invoice_type'] == 'normal')
                สาขาที่ออกเอกสาร/ Branch
            @else
                สาขาที่ออกใบกำกับภาษี/ Branch
            @endif
            </td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['receipt_branch'])? $invoice['information']['receipt_branch'] : '' }}</td>
            <!-- End Makro Store : Type -->
        </tr>
        <tr>
            <!-- Customer : No -->
            <td width="21%" class="head-title">รหัสสมาชิกลูกค้า/ Customer No.</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['customer_no'])? $invoice['information']['customer_no'] : '' }}</td>
            <!-- End Customer : No -->
            <!-- Makro Store : Address -->
            <td width="22%" class="head-title">ที่อยู่/ Address</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['receipt_address1'])? $invoice['information']['receipt_address1'] : '' }}</td>
            <!-- End Makro Store : Address -->
        </tr>
        <tr>
            <!-- Customer : Address -->
            <td width="21%" class="head-title">ที่อยู่/ Address</td>
            <td width="29%" class="head-content border-right" colspan="3">
                {{ isset($invoice['information']['customer_address1'])? $invoice['information']['customer_address1'] : '' }}
            </td>
            <!-- End Customer : Address -->
            <!-- Makro Store : Address 2 -->
            <td width="50%" class="head-content" colspan="2">
                {{ isset($invoice['information']['receipt_address2'])? $invoice['information']['receipt_address2'] : '' }}
            </td>
            <!-- End Makro Store : Address -->
        </tr>
        <tr>
            <!-- Customer : Address 2 -->
            <td width="50%" class="head-content border-right" colspan="4">
                {{ isset($invoice['information']['customer_address2'])? $invoice['information']['customer_address2'] : '' }}
            </td>
            <!-- End Customer : Address 2 -->
            <td width="22%" class="head-title">&nbsp;</td>
            <td width="28%" >&nbsp;</td>
        </tr>
        <tr>
            <!-- Customer : Tax -->
            <td width="21%" class="head-title">เลขประจำตัวผู้เสียภาษี/ Tax ID</td>
            <td width="11%" class="head-content">{{ (isset($invoice['information']['customer_tax_id']) && !empty($invoice['information']['customer_tax_id'])) ? $invoice['information']['customer_tax_id'] : '&nbsp;' }}</td>
            <td width="7%" class="head-title">สาขา</td>
            <td width="11%" class="head-content border-right">{{ isset($invoice['information']['customer_branch'])? $invoice['information']['customer_branch'] : '' }}</td>
            <!-- End Customer : Tax -->
            <td width="22%" class="head-title">&nbsp;</td>
            <td width="28%" class="head-content">&nbsp;</td>
        </tr>
        <tr>
            <td width="21%" class="head-title border-bottom">&nbsp;</td>
            <td width="29%" class="head-content border-right border-bottom" colspan="3">&nbsp;</td>
            <td width="22%" class="head-title border-bottom">&nbsp;</td>
            <td width="28%" class="head-content border-bottom">&nbsp;</td>
        </tr>
    @else
        <tr>
            <!-- Customer : Name 1 -->
            <td width="21%" class="head-title">ชื่อลูกค้า/ Customer Name</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['customer_name1'])? $invoice['information']['customer_name1'] : '' }}</td>
            <!-- End Customer : Name 1 -->

            <!-- Makro Store : Type -->
            <td width="22%" class="head-title">
            @if($invoice['invoice_type'] == 'normal')
                สาขาที่ออกเอกสาร/ Branch
            @else
                สาขาที่ออกใบกำกับภาษี/ Branch
            @endif
            </td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['receipt_branch'])? $invoice['information']['receipt_branch'] : '' }}</td>
            <!-- End Makro Store : Type -->
        </tr>
        <!-- Customer : Name 2 -->
        <tr>
            <td width="29%" class="head-content border-right" colspan="4">{{ isset($invoice['information']['customer_name2'])? $invoice['information']['customer_name2'] : '' }}</td>
            <!-- Makro Store : Address -->
            <td width="22%" class="head-title">ที่อยู่/ Address</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['receipt_address1'])? $invoice['information']['receipt_address1'] : '' }}</td>
            <!-- End Makro Store : Address -->
        </tr>
        <!-- End Customer : Name 2 -->
        <tr>
            <!-- Customer : No -->
            <td width="21%" class="head-title">รหัสสมาชิกลูกค้า/ Customer No.</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['customer_no'])? $invoice['information']['customer_no'] : '' }}</td>
            <!-- End Customer : No -->
            <!-- Makro Store : Address 2 -->
            <td width="50%" class="head-content" colspan="2">
                {{ isset($invoice['information']['receipt_address2'])? $invoice['information']['receipt_address2'] : '' }}
            </td>
            <!-- End Makro Store : Address -->
        </tr>
        <tr>
            <!-- Customer : Address -->
            <td width="21%" class="head-title">ที่อยู่/ Address</td>
            <td width="29%" class="head-content border-right" colspan="3">
                {{ isset($invoice['information']['customer_address1'])? $invoice['information']['customer_address1'] : '' }}
            </td>
            <!-- End Customer : Address -->
            <td width="22%" class="head-title">&nbsp;</td>
            <td width="28%" class="head-content">&nbsp;</td>
        </tr>
        <tr>
            <!-- Customer : Address 2 -->
            <td width="50%" class="head-content border-right" colspan="4">
                {{ isset($invoice['information']['customer_address2'])? $invoice['information']['customer_address2'] : '' }}
            </td>
            <!-- End Customer : Address 2 -->
            <td width="22%" class="head-title">&nbsp;</td>
            <td width="28%" >&nbsp;</td>
        </tr>
        <tr>
            <!-- Customer : Tax -->
            <td width="21%" class="head-title border-bottom">เลขประจำตัวผู้เสียภาษี/ Tax ID</td>
            <td width="11%" class="head-content border-bottom">{{ (isset($invoice['information']['customer_tax_id']) && !empty($invoice['information']['customer_tax_id'])) ? $invoice['information']['customer_tax_id'] : '&nbsp;' }}</td>
            <td width="7%" class="head-title border-bottom">สาขา</td>
            <td width="11%" class="head-content border-right border-bottom">{{ isset($invoice['information']['customer_branch'])? $invoice['information']['customer_branch'] : '' }}</td>
            <!-- End Customer : Tax -->
            <td width="22%" class="head-title border-bottom">&nbsp;</td>
            <td width="28%" class="head-content border-bottom">&nbsp;</td>
        </tr>
    @endif

    <!-- empty shipping address 2  -->
    @if( empty($invoice['information']['shipping_address2']) )
        <tr>
            <!-- Receive Information : Shipping address -->
            <td width="21%" class="head-title">สถานที่ส่งสินค้า/ Shipping Address</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['shipping_address1'])? $invoice['information']['shipping_address1'] : '' }}</td>
            <!-- End Receive Information : Shipping address -->
            <!-- Tax : Invoice No -->
            <td width="22%" class="head-title">
            @if($invoice['invoice_type'] == 'normal')
                เลขที่/ No.
            @else
                เลขที่ใบกำกับภาษี/ Tax Invoice No.
            @endif
            </td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['tax_invoice_no'])? $invoice['information']['tax_invoice_no'] : '' }}</td>
            <!-- End Tax : Invoice No -->
        </tr>
        <tr>
            <!-- Receive Information : Address -->
            <td width="21%" class="head-title">ที่อยู่/ Address</td>
            <td width="29%" class="head-content border-right" colspan="3">
                {{ isset($invoice['information']['address'])? $invoice['information']['address'] : '' }}
            </td>
            <!-- End Receive Information : Address -->
            <!-- Tax : Invoice Date -->
            <td width="22%" class="head-title">
            @if($invoice['invoice_type'] == 'normal')
                วันที่/ Date
            @else
                วันที่ใบกำกับภาษี/ Tax Invoice Date
            @endif
            </td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['tax_invoice_date'])? $invoice['information']['tax_invoice_date'] : '' }}</td>
            <!-- End Tax : Invoice Date -->
        </tr>
        <tr>
            <!-- Receive Information : Address 2 -->
            <td width="50%" class="head-content border-right" colspan="4">
                {{ isset($invoice['information']['address2'])? $invoice['information']['address2'] : '' }}
            </td>
            <!-- End Receive Information : Address 2 -->
            <!-- Tax : Order Date -->
            <td width="22%" class="head-title">วันที่สั่งซื้อ/ Order Date</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['order_date'])? $invoice['information']['order_date'] : '' }}</td>
            <!-- End Tax : Order Date -->
        </tr>
        <tr>
            <!-- Receive Information : Receiver -->
            <td width="21%" class="head-title">ชื่อผู้รับสินค้า/ Receiver</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['receiver_name'])? $invoice['information']['receiver_name'] : '' }}</td>
            <!-- End Receive Information : Receiver -->
            <!-- Tax : Order No -->
            <td width="22%" class="head-title">เลขที่สั่งซื้อ/ Order No.</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['order_no'])? $invoice['information']['order_no'] : '' }}</td>
            <!-- End Tax : Order No -->
        </tr>
        <tr>
            <!-- Receive Information : E-mail -->
            <td width="21%" class="head-title">อีเมล/ E-mail</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['receiver_email'])? $invoice['information']['receiver_email'] : '' }}</td>
            <!-- End Receive Information : E-mail -->
            <!-- Tax : Payment type -->
            <td width="22%" class="head-title">วิธีการชำระเงิน/ Payment Type</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['payment_type'])? $invoice['information']['payment_type'] : '' }}</td>
            <!-- End Tax : Payment type -->
        </tr>
        <tr>
            <!-- Receive Information : TEL -->
            <td width="21%" class="head-title">เบอร์ติดต่อ/ Tel No.</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['receiver_telephone'])? $invoice['information']['receiver_telephone'] : '' }}</td>
            <!-- End Receive Information : TEL -->
            <!-- Tax : Payment Deposit receipt -->
            @if($invoice['invoice_type'] == 'normal')
            <td width="22%" class="head-title">เลขที่ใบรับมัดจำ/ Deposit receipt</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['deposit_receipt'])? $invoice['information']['deposit_receipt']:'' }}</td>
            @else
            <td width="22%" class="head-title">&nbsp;</td>
            <td width="28%" class="head-content">&nbsp;</td>
            @endif
            <!-- End Tax : Payment Deposit receipt -->
        </tr>
        <tr>
            <td width="21%" class="head-title">&nbsp;</td>
            <td width="29%" class="head-content border-right" colspan="3">&nbsp;</td>
            <td width="22%" class="head-title border-bottom">&nbsp;</td>
            <td width="28%" class="head-content border-bottom">&nbsp;</td>
        </tr>
    @else
        <tr>
            <!-- Receive Information : Shipping address -->
            <td width="21%" class="head-title">สถานที่ส่งสินค้า/ Shipping Address</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['shipping_address1'])? $invoice['information']['shipping_address1'] : '' }}</td>
            <!-- End Receive Information : Shipping address -->
            <!-- Tax : Invoice No -->
            <td width="22%" class="head-title">
            @if($invoice['invoice_type'] == 'normal')
                เลขที่/ No.
            @else
                เลขที่ใบกำกับภาษี/ Tax Invoice No.
            @endif
            </td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['tax_invoice_no'])? $invoice['information']['tax_invoice_no'] : '' }}</td>
            <!-- End Tax : Invoice No -->
        </tr>
        <tr>
            <!-- Receive Information : Address 2 -->
            <td width="50%" class="head-content border-right" colspan="4">
                {{ isset($invoice['information']['shipping_address2'])? $invoice['information']['shipping_address2'] : '' }}
            </td>
            <!-- End Receive Information : Address 2 -->
            <!-- Tax : Invoice Date -->
            <td width="22%" class="head-title">
            @if($invoice['invoice_type'] == 'normal')
                วันที่/ Date
            @else
                วันที่ใบกำกับภาษี/ Tax Invoice Date
            @endif
            </td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['tax_invoice_date'])? $invoice['information']['tax_invoice_date'] : '' }}</td>
            <!-- End Tax : Invoice Date -->
        </tr>
        <tr>
            <!-- Receive Information : Address -->
            <td width="21%" class="head-title">ที่อยู่/ Address</td>
            <td width="29%" class="head-content border-right" colspan="3">
                {{ isset($invoice['information']['address'])? $invoice['information']['address'] : '' }}
            </td>
            <!-- End Receive Information : Address -->
            <!-- Tax : Order Date -->
            <td width="22%" class="head-title">วันที่สั่งซื้อ/ Order Date</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['order_date'])? $invoice['information']['order_date'] : '' }}</td>
            <!-- End Tax : Order Date -->
        </tr>
        <tr>
            <!-- Receive Information : Address 2 -->
            <td width="50%" class="head-content border-right" colspan="4">
                {{ isset($invoice['information']['address2'])? $invoice['information']['address2'] : '' }}
            </td>
            <!-- End Receive Information : Address 2 -->
            <!-- Tax : Order No -->
            <td width="22%" class="head-title">เลขที่สั่งซื้อ/ Order No.</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['order_no'])? $invoice['information']['order_no'] : '' }}</td>
            <!-- End Tax : Order No -->
        </tr>
        <tr>
            <!-- Receive Information : Receiver -->
            <td width="21%" class="head-title">ชื่อผู้รับสินค้า/ Receiver</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['receiver_name'])? $invoice['information']['receiver_name'] : '' }}</td>
            <!-- End Receive Information : Receiver -->
            <!-- Tax : Payment type -->
            <td width="22%" class="head-title">วิธีการชำระเงิน/ Payment Type</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['payment_type'])? $invoice['information']['payment_type'] : '' }}</td>
            <!-- End Tax : Payment type -->
        </tr>
        <tr>
            <!-- Receive Information : E-mail -->
            <td width="21%" class="head-title">อีเมล/ E-mail</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['receiver_email'])? $invoice['information']['receiver_email'] : '' }}</td>
            <!-- End Receive Information : E-mail -->
            <!-- Tax : Payment Deposit receipt -->
            @if($invoice['invoice_type'] == 'normal')
            <td width="22%" class="head-title">เลขที่ใบรับมัดจำ/ Deposit Receipt</td>
            <td width="28%" class="head-content">{{ isset($invoice['information']['deposit_receipt'])? $invoice['information']['deposit_receipt']:'' }}</td>
            @else
            <td width="22%" class="head-title">&nbsp;</td>
            <td width="28%" class="head-content">&nbsp;</td>
            @endif
            <!-- End Tax : Payment Deposit receipt -->
        </tr>
        <tr>
            <!-- Receive Information : TEL -->
            <td width="21%" class="head-title">เบอร์ติดต่อ/ Tel No.</td>
            <td width="29%" class="head-content border-right" colspan="3">{{ isset($invoice['information']['receiver_telephone'])? $invoice['information']['receiver_telephone'] : '' }}</td>
            <!-- End Receive Information : TEL -->
            <td width="22%" class="head-title border-bottom">&nbsp;</td>
            <td width="28%" class="head-content border-bottom">&nbsp;</td>
        </tr>
    @endif
    </tbody>
</table>