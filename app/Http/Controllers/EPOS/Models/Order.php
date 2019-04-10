<?php
namespace App\Http\Controllers\EPOS\Models;

class Order
{
    public $orderNumber;
    public $orderDate;
    public $orderStatus;
    public $orderPayment; // object
    public $buyer;
    public $customerName;
    public $customerLastName;
    public $makro_member_card;
    public $tax_payer_id;
    public $search_criteria2;
    public $branch;
    public $customerMobile;
    public $customerEmail;
    public $pickupStore;
    public $shoppingAddress; // object
    public $billingAddress; // object
    public $additionalAddress; // object
    public $shoppingCart; // array
    public $orderProducts; // array
    public $data; // array

}

