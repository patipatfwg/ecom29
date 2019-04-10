<?php
namespace App\Http\Controllers\EPOS\Models;

class CustomerInformation extends OrderAddress {
    public $firstName;
    public $lastName;
    public $shopName;
    public $taxId;
    public $phone;
    public $branchId;
    public $addressLine1;
    public $addressLine2;
    public $addressLine3;
    public $addressLine4;
    public $email;
    public $province;
    public $districts;
    public $sub_districts;
    public $city;
    public $country;
    public $zipcode;
}