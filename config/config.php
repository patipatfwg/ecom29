<?php

return [
	'type_category_product'  => 'product',
	'type_category_business' => 'business',
	'type_category_brand'    => 'brand',
	'type_category_content'  => 'content',
	'path_centralize_log'    => env('CENTRALIZE_LOG', '/var/log/admin'),
	'customer_channel'		 => [
		'normal'       => 'Makroclick',
		'professional' => 'Store Order'
	],
];
