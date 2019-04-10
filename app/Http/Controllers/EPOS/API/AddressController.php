<?php
namespace App\Http\Controllers\EPOS\API;

use App\Http\Controllers\EPOS\EPOSBaseController;

class AddressController extends EPOSBaseController
{
    public function getProvinces()
    {
        return $this->getAllProvinces();
    }

    public function getDistricts($provinceId) {
        return $this->getAllDistricts($provinceId);
    }

    public function getSubDistricts($districtId) {
        return $this->getAllSubDistricts($districtId);
    }

    public function getStores($makroStoreId)
    {
        return $this->getAllStores($makroStoreId);
    }
}