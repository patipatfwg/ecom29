<?php

return [
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	'tempDir'               => base_path('/storage/temp/'),
	'margin_left' 			=> 5 ,
	'margin_right' 			=> 5,
	'margin_top' 			=> 3,
	'margin_bottom' 		=> 5,
	'font_path' 			=> base_path('resources/fonts/'),
	'font_data' 			=> [
		"thsarabun" => [
			'R' 		=> 'THSarabunNew.ttf',
			'B' 		=> 'THSarabunNew-Bold.ttf',
			'I' 		=> 'THSarabunNew-Italic.ttf',
			'BI' 		=> 'THSarabunNew-BoldItalic.ttf',
		]
	]
];
