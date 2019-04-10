<div class="remark border-bottom">
    <div class="remark-label full-height fl border-right pd-l-1">
        <div>หมายเหตุ/ Remark</div>
        <div class="break-word black font-read">{{ isset($invoice['remark'])? $invoice['remark'] : '' }}</div>
    </div>
 
    @if($invoice['invoice_type'] == 'credit-note') <!-- include refund / return -->
    <div class="condition condition-text full-height fl border-right pd-l-1">
        <div class="remark-cell full-height">เงื่อนไข/ Condition</div>
        <div class="remark-cell full-height">1.เอกสารนี้จะสมบูรณ์เมื่อมีตราประทับบริษัทและลายเซ็นต์เจ้าหน้าที่ผู้ได้รับมอบอำนาจ</div>
        <div class="remark-cell full-height">และบริษัทได้รับชำระหนี้หรือเรียกเก็บเงินครบถ้วนแล้ว</div>
        <div class="remark-cell full-height">2.โปรดเก็บเอกสารไว้เป็นหลักฐาน ในการติดต่อกับทางบริษัท</div>
        <div class="remark-cell full-height">3.บริษัทจะรับคืนสินค้าภายใน 7 วัน ยกเว้นของสดรับคืนภายในวันที่รับสินค้า และสินค้าที่รับคืนต้องอยู่ในสภาพเดิม</div>
        <div class="remark-cell full-height"></div>
    </div>
    <div class="discount-label full-height fl border-right invoice-grey">
        <div class="remark-cell full-height border-bottom pd-l-1">มูลค่าที่ถูกต้อง/ CORRECT AMOUNT</div>
        <div class="remark-cell full-height border-bottom pd-l-1">ผลต่าง/ DIFFERENCE</div>
        <div class="remark-cell full-height border-bottom pd-l-1">ภาษีมูลค่าเพิ่ม/ VAT</div>
        <div class="remark-cell full-height border-bottom pd-l-1">จำนวนเงินก่อนภาษีมูลค่าเพิ่ม/ SUBTOTAL</div>
        <div class="remark-cell full-height border-bottom pd-l-1"></div>
        <div class="remark-cell pd-l-1">มูลค่าลดหนี้/ CREDIT AMOUNT</div>
    </div>
    <div class="discount-amount full-height fl text-right black font-read">
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary-credit-note']['correct-order-amount'])? $invoice['summary-credit-note']['correct-order-amount'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary-credit-note']['diff-order-amount'])? $invoice['summary-credit-note']['diff-order-amount'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary-credit-note']['amount'])? $invoice['summary-credit-note']['vat'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary-credit-note']['vat'])? $invoice['summary-credit-note']['sub-total'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">      
            
        </div>
        <div class="remark-cell invoice-grey pd-r-1">
            {{ isset($invoice['summary-credit-note']['credit-amount'])? $invoice['summary-credit-note']['credit-amount'] : '' }}
        </div>
    </div>
    @elseif($invoice['invoice_type'] == 'deposit') <!-- deposit -->
    <div class="condition condition-text full-height fl border-right pd-l-1">
        <div class="remark-cell full-height">เงื่อนไข/ Condition</div>
        <div class="remark-cell full-height">1.ใบเสร็จรับเงินนี้จะสมบูรณ์เมื่อมีตราประทับบริษัทและลายเซ็นต์เจ้าหน้าที่ผู้ได้รับมอบอำนาจ</div>
        <div class="remark-cell full-height">และบริษัทได้รับชำระหนี้หรือเรียกเก็บเงินครบถ้วนแล้ว</div>
        <div class="remark-cell full-height">2.บริษัทขอสงวนสิทธิ์ในกรณีที่เป็นบัตรเครดิตใบเสร็จจะสมบูรณ์เมื่อได้เรียกเก็บเงินจากธนาคารเรียบร้อยแล้ว</div>
        <div class="remark-cell full-height">3.โปรดเก็บเอกสารไว้เป็นหลักฐานในการติดต่อกับทางบริษัท</div>
        <div class="remark-cell full-height">4.บริษัทจะรับคืนสินค้าภายใน 7 วัน ยกเว้นของสดรับคืนภายในวันที่ซื้อ และสินค้าที่รับคืนต้องอยู่ในสภาพเดิม</div>
    </div>
    <div class="discount-label full-height fl border-right invoice-grey">
        <div class="remark-cell full-height border-bottom pd-l-1">หักส่วนลด/ DISCOUNT</div>
        <div class="remark-cell full-height border-bottom pd-l-1">จำนวนเงินรวมสุทธิ/ AMOUNT</div>
        <div class="remark-cell full-height border-bottom pd-l-1">หักเงินมัดจำ/ DEPOSIT</div>
        <div class="remark-cell full-height border-bottom pd-l-1">จำนวนเงินก่อนภาษีมูลค่าเพิ่ม/ SUBTOTAL</div>
        <div class="remark-cell full-height border-bottom pd-l-1">ภาษีมูลค่าเพิ่ม/ VAT</div>
        <div class="remark-cell pd-l-1">จำนวนเงินที่ต้องชำระ/ NET AMOUNT</div>
    </div>
    <div class="discount-amount full-height fl text-right black font-read">
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['discount'])? $invoice['summary']['discount'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['amount'])? $invoice['summary']['amount'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['deposit'])? $invoice['summary']['deposit'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['sub-total'])? $invoice['summary']['sub-total'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['vat'])? $invoice['summary']['vat'] : '' }}
        </div>
        <div class="remark-cell invoice-grey pd-r-1">
            {{ isset($invoice['summary']['net-amount'])? $invoice['summary']['net-amount'] : '' }}
        </div>
    </div>
    @else  <!-- normal -->
    <div class="condition condition-text full-height fl border-right pd-l-1">
        <div class="remark-cell full-height">เงื่อนไข/ Condition</div>
        <div class="remark-cell full-height">1.โปรดเก็บเอกสารไว้เป็นหลักฐาน ในการติดต่อกับทางบริษัท</div>
        <div class="remark-cell full-height">2.บริษัทจะรับคืนสินค้าภายใน 7 วัน ยกเว้นของสดรับคืนภายในวันที่รับสินค้า และสินค้าที่รับคืนต้องอยู่ในสภาพเดิม</div>
        <div class="remark-cell full-height"></div>
        <div class="remark-cell full-height"></div>
        <div class="remark-cell full-height"></div>
    </div>
    <div class="discount-label full-height fl border-right invoice-grey">
        <div class="remark-cell full-height border-bottom pd-l-1">หักส่วนลด/ DISCOUNT</div>
        <div class="remark-cell full-height border-bottom pd-l-1">จำนวนเงินรวมสุทธิ/ AMOUNT</div>
        <div class="remark-cell full-height border-bottom pd-l-1">หักเงินมัดจำ/ DEPOSIT</div>
        <div class="remark-cell full-height border-bottom pd-l-1">จำนวนเงินก่อนภาษีมูลค่าเพิ่ม/ SUBTOTAL</div>
        <div class="remark-cell full-height border-bottom pd-l-1">ภาษีมูลค่าเพิ่ม/ VAT</div>
        <div class="remark-cell pd-l-1">จำนวนเงินที่ต้องชำระ/ NET AMOUNT</div>
    </div>
    <div class="discount-amount full-height fl text-right black font-read">
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['discount'])? $invoice['summary']['discount'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['amount'])? $invoice['summary']['amount'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['deposit'])? $invoice['summary']['deposit'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['sub-total'])? $invoice['summary']['sub-total'] : '' }}
        </div>
        <div class="remark-cell full-height border-bottom pd-r-1">
            {{ isset($invoice['summary']['vat'])? $invoice['summary']['vat'] : '' }}
        </div>
        <div class="remark-cell invoice-grey pd-r-1">
            {{ isset($invoice['summary']['net-amount'])? $invoice['summary']['net-amount'] : '' }}
        </div>
    </div>
    @endif
</div>