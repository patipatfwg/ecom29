<?php
namespace App\Http\Controllers\EPOS\Models;

class RefundItem
{
    public $invoiceNo;
    public $createDate;
    public $shipNode;
    public $paymentType;
    public $amount;
    public $reason;
    public $modifyDate;
    public $status;
    public $order;
}