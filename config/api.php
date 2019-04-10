<?php

return [
	'makro_member_api'       => env('CURL_API_MEMBER_PROFILE'),
	'makro_attribute_api'    => env('CURL_API_ATTRIBUTE'),
	'makro_category_api'     => env('CURL_API_CATEGORY'),
	'makro_product_api'      => env('CURL_API_PRODUCT'),
	'makro_content_api'      => env('CURL_API_CONTENT'),
	'makro_tag_api'          => env('CURL_API_TAG'),
	'makro_permission_api'   => env('CURL_API_PERMISSION'),
	'makro_campaign_api'     => env('CURL_API_CAMPAIGN'),
	'makro_product_sync_api' => env('CURL_API_PRODUCT_SYNC'),
	'makro_banner_api'       => env('CURL_API_BANNER'),
	'makro_group_api'        => env('CURL_API_GROUP'),
	'makro_store_api'        => env('CURL_API_STORE'),
	'makro_config_api'       => env('CURL_API_CONFIG'),
	'makro_order_api'        => env('CURL_API_ORDER'),
    'makro_address_api'      => env('CURL_API_ADDRESS'),
    'makro_epos_api'         => env('CURL_API_EPOS'),
    'makro_bss_api'          => env('CURL_API_BSS'),
	'makro_payment_api'		 => env('CURL_API_PAYMENT'),
	'makro_edoc'		     => env('EDOC_PATH'),

	'cdn_api'                => [
		'url'          => env('cdn_api_url', 'http://api-cdn.eggdigital.com'),
		'private_key'  => env('cdn_private_key', '8deeb571919334b42210bb13aa0ae1cb'),
		'service_name' => env('cdn_service_name', 'iotalkbacken'),
		'service_id'   => env('cdn_service_id', 7),
	],

];
