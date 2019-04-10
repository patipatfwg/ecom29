<?php
namespace App\Http\Controllers\EPOS\Models;

class Product {
    public $id;
    public $name;
    public $price;
    public $origin_quantity;
    public $quantity;
    public $vatpercent;
    public $status;
    public $simple_discount;
    public $complex_discount;
}