<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


Route::get('/provinces', 'EPOS\API\AddressController@getProvinces');
Route::get('/districts/{provinceId}', 'EPOS\API\AddressController@getDistricts');
Route::get('/districts/{districtId}/sub-districts', 'EPOS\API\AddressController@getSubDistricts');
Route::get('/stores/{makroStoreId}', 'EPOS\API\AddressController@getStores');
