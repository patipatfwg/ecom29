<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['middleware' => ['web','logRequest']], function () {

	Route::get('/', 'LoginController@getLogin');
	Route::get('/login-form', 'LoginController@getLoginForm');
	Route::post('/', 'LoginController@postLogin');
	Route::get('logout', 'LoginController@getLogout');

	/**
	 * Version
	 */
	Route::get('/version', 'VersionController@getVersionAction');

	Route::group(['middleware' => ['login', 'authorize', 'language', 'logging']], function () {

        Route::any('/attribute/getAjaxAttribute', 'AttributeController@getAjaxAttribute');

		/**
		 * Dashboard
		 */
		Route::resource('dashboard', 'DashboardController');

		/**
		 * Menus
		 */
		Route::post('/menu/move', 'MenusController@Move');
		Route::resource('menu', 'MenusController');

		/**
		 * Members
		 */
		Route::any('member/data', 'MembersController@anyData');
		Route::any('member/report', 'MembersController@report');
		Route::post('member/address', 'MembersController@address');
		Route::delete('member', 'MembersController@delete');
		Route::resource('member', 'MembersController');

		/**
		 * Banner
		 */
		 Route::get('banner', 'BannerController@index');
		 Route::group(['prefix' => 'banner'], function () {
			Route::get('{id}/edit', 'BannerController@editBanner');
			Route::get('create', 'BannerController@create');
			Route::get('data', 'BannerController@getData');
			Route::get('position/{position}', 'BannerController@position');
		});
		Route::resource('banner', 'BannerController');

        /**
         * Category Product List
         */
        Route::any('category/data', 'CategoryController@anyData');
        Route::any('category/report', 'CategoryController@report');

        Route::resource('category', 'CategoryController');
        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('create/{parent_id}', 'CategoryController@create');
            Route::post('status', 'CategoryController@setStatus');
            Route::post('priority', 'CategoryController@postPriority');
			Route::post('create', 'CategoryController@store');
            Route::post('{parent_id}', 'CategoryController@update');
            Route::get('position/{position}', 'CategoryController@position');
            Route::get('{category_id}/data', 'CategoryController@aJaxProductByCategory');
            Route::post('{category_id}/product/{type}', 'CategoryController@saveProduct');
            Route::get('{category_id}/get_product_search', 'CategoryController@aJaxProductSearch');
            Route::delete('{category_id}/product/{product_id}', 'CategoryController@aJaxDeleteProduct');
            Route::get('{category_id}/{type}', 'CategoryController@product');
        });
		/**
		 * Category Business List
		 */
		 Route::any('category_business/data', 'CategoryBusinessController@anyData');
		 Route::any('category_business/report', 'CategoryBusinessController@report');
		 Route::resource('category_business', 'CategoryBusinessController');
		 Route::group(['prefix' => 'category_business', 'as' => 'category_business.'], function () {
            Route::get('create/{parent_id}', 'CategoryBusinessController@create');
            Route::post('status', 'CategoryBusinessController@setStatus');
            Route::post('priority', 'CategoryBusinessController@postPriority');
			Route::post('create', 'CategoryBusinessController@store');
            Route::post('{parent_id}', 'CategoryBusinessController@update');
            Route::get('position/{position}', 'CategoryBusinessController@position');

        });

		/**
		 * Brand
		 */
		Route::group(['prefix' => 'brand', 'as' => 'brand.'], function () {
			Route::post('getAjaxBrand', 'BrandController@getAjaxBrand');
			Route::put('priority', 'BrandController@updatePriority');
			Route::post('status', 'BrandController@setStatus');
			Route::get('export', 'BrandController@exportBrand')->name('export');
			Route::get('position/{position}', 'BrandController@position');
			Route::get('check_del/{id}', 'BrandController@check_del');
		});
		Route::resource('brand', 'BrandController');

		/**
		 * Attribute
		 */
		Route::any('attribute/export', 'AttributeController@exportAttributes')->name('attribute.export');
		Route::get('/attribute', 'AttributeController@getIndex')->name('attribute.index');
		Route::get('/attribute/add', 'AttributeController@getAddData')->name('attribute.create');
		Route::post('/attribute/save', 'AttributeController@postSaveData')->name('attribute.store');
		Route::get('/attribute/{edit_id}', 'AttributeController@getEditData')->name('attribute.edit');
		Route::post('/attribute/update', 'AttributeController@postUpdateData')->name('attribute.update');
		Route::delete('/attribute/{id}', 'AttributeController@deleteData');

		/**
		 * CategoryAttribute
		 */
		// Route::any('category_attribute/data', 'CategoryAttributeController@anyData');
		// Route::resource('category_attribute', 'CategoryAttributeController');

		/**
		 * Products
		 */
		Route::resource('product', 'ProductsController', ['except' => ['show', 'create', 'store']]);
		Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
			Route::any('data', 'ProductsController@anyData')->name('data');
			Route::post('status', 'ProductsController@postStatus');
			Route::post('priority', 'ProductsController@postPriority');
            Route::post('sync', 'ProductsController@postSyncSearch');
			Route::post('approve', 'ProductsController@postApprove')->name('approve');
			Route::put('{id}/approve','ProductsController@putApprove');
			/**
			 * Products export
			 */
			Route::post('export', 'ProductsController@exportProducts')->name('export');
			/**
			 * Product images
			 */
			Route::post('{id}/uploadimage', 'ProductsController@postUploadImage');
			Route::delete('deleteImage', 'ProductsController@deleteImage');
			Route::post('moveImage', 'ProductsController@moveImage');
			/**
			 * Product relates
			 */
			Route::post('relate/search', 'ProductRelatedController@postProducts');
			Route::get('{id}/relate', 'ProductRelatedController@getProductRelated');
			Route::get('{id}/relate/{relateId}/delete', 'ProductRelatedController@destroy');
			Route::post('{id}/relate/create', 'ProductRelatedController@create');
			Route::get('{id}/relate/search', 'ProductRelatedController@getProductsBySearch');
			/**
			* Product store
			*/
			Route::get('/store_price','ProductsController@getStorePrice');

			/**
			* Upload image product from folder public/img and storage/img
			*/
			Route::get('dumpImage', 'ProductsController@dumpImage');
		});


		/**
		 * Contents
		 */
		Route::any('content/report', 'ContentsController@report');
		Route::resource('content', 'ContentsController', ['except' => ['show']]);
		Route::group(['prefix' => 'content', 'as' => 'content.'], function () {
			Route::get('data', 'ContentsController@data')->name('data');
			Route::post('priority', 'ContentsController@postPriority');
			Route::post('status', 'ContentsController@setStatus');
			Route::put('status/{id}', 'ContentsController@setStatus');
		});

		/**
		 * Content Category
		 */

		//  Route::any('content_category/data', 'ContentCategoriesController@anyData');
		//  Route::any('content_category/report', 'ContentCategoriesController@report');
		//  Route::resource('content_category', 'ContentCategoriesController');
		//  Route::group(['prefix' => 'content_category', 'as' => 'content_category.'], function () {
		// 		 Route::get('create/{parent_id}', 'ContentCategoriesController@create');
		// 		 Route::post('status', 'ContentCategoriesController@setStatus');
		// 		 Route::post('priority', 'ContentCategoriesController@postPriority');
		// 		 Route::post('create', 'ContentCategoriesController@store');
		// 		 Route::post('{parent_id}', 'ContentCategoriesController@update');
		// 		 Route::put('status/{id}', 'ContentCategoriesController@setStatus');
		// });

		/**
		 * Admin Authorization
		 */
		Route::group(['prefix' => 'user_group', 'as' => 'user_group.'], function () {
			Route::post('data', 'UserGroupsController@getAjaxUserGroups')->name('data');
		});
		Route::resource('user_group', 'UserGroupsController');

		Route::get('user/report', 'UsersController@exportUser');
		Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
			Route::get('data', 'UsersController@getData')->name('data');
		});
		Route::resource('user', 'UsersController' , [ 'except' => ['show'] ]);

		/**
		 * Campaign
		 */
		//Route::get('product/dumpimage' , 'ProductsController@dumpImage');
		Route::any('campaign/report', 'CampaignController@exportCampaigns');
		Route::group(['prefix' => 'campaign'], function () {
			Route::get('data', 'CampaignController@getData');
			Route::get('{id}/product', 'CampaignController@getProducts');
			Route::get('{id}/product/add', 'CampaignController@addProducts');
			Route::post('{id}/product/add', 'CampaignController@addProductToCampaign');
			Route::post('{id}/product/priority', 'CampaignController@updatePriorityCampaign');
			Route::get('{id}/product/add/data', 'CampaignController@getProductsData');
			Route::delete('{id}/product/{campaign_product_ids}', 'CampaignController@deleteProducts');
			Route::post('status','CampaignController@updateStatus');
			Route::get('position/{position}', 'CampaignController@position');
		});
		Route::resource('campaign','CampaignController');

		/*
		 * Group Menu
		 */
		 Route::get('group_menu/report', 'GroupMenuController@exportGroupMenu');
		 Route::get('group_menu/{id}/{title}/report', 'GroupMenuController@exportGroupHilightMenu');
		 Route::group(['prefix' => 'group_menu'], function () {
			
			Route::get('data', 'GroupMenuController@getData');
			Route::put('status/{id}', 'GroupMenuController@updateGroupMenuStatus');
			Route::get('{id}/menu/add', 'GroupMenuController@addMenu');
			Route::get('{id}/content', 'GroupMenuController@content');
			Route::get('{id}/hilight', 'GroupMenuController@getHilightMenu');
			Route::post('{id}/menu/add', 'GroupMenuController@createHilightMenu');
			Route::get('{id}/menu/{hilight_id}/edit', 'GroupMenuController@editMenu');
			Route::put('{id}/menu/edit_hilight/{hilight_id}', 'GroupMenuController@updateHilightMenu');
			Route::post('priority', 'GroupMenuController@updateHilightMenuPriority');
			Route::put('menu/status/{id}', 'GroupMenuController@updateHilightMenuStatus');
			Route::put('{id}/menu/{hilight_id}', 'GroupMenuController@update');

		});
		Route::resource('group_menu','GroupMenuController');


        /**
         * Coupon
		 */
		/*
		Route::get('coupon/product', 'ProductsController@searchProducts');
		Route::group(['prefix' => 'coupon'], function () {
			Route::any('data', 'CouponsController@anyData');
			Route::any('{id}/usage/data', 'CouponsController@usageData');
			Route::any('{id}/report', 'CouponsController@reportHistory');
			Route::any('report', 'CouponsController@report');
			Route::get('{id}/usage', 'CouponsController@usage');

			Route::get('edit/{id}', 'CouponsController@edit');
			Route::delete('delete/{id}', 'CouponsController@destroy');
			Route::post('status', 'CouponsController@updateStatus');
		});
        Route::resource('coupon', 'CouponsController');
        */

        /**
         * E-POS
         */
        Route::group(['prefix' => 'epos', 'as' => 'EPOS.'], function () {
            //Order
            Route::get('order', 'EPOS\OrdersController@search')->name('order_search');// support search by ?order_number=xxx and can link to Pay@Store payment page with auto fill order number

            //Pay@Store Payment
            Route::get('order/paystore', 'EPOS\PayStoreController@search')->name('order_search.pay_at_store'); // support search by order number or tax_invoice_number or amount
            Route::put('order/paystore', 'EPOS\PayStoreController@save')->name('order_search.update');// for save update from AJAX call

            //Return Order
            Route::get('return_order', 'EPOS\ReturnOrdersController@search')->name('return_order_search'); // can link to Invoice

            //Invoice
            Route::get('invoice', 'EPOS\InvoicesController@search')->name('invoice_search');// support search by ?order_number=xxx
			Route::get('invoice/{invoice_number}', 'EPOS\InvoicesController@preview')->name('invoice_search.show');

			Route::get('invoice/pdf/{invoice_number}/{order_no}', 'EPOS\InvoicesController@pdf')->name('invoice_search.pdf');

            Route::get('invoice/{invoice_number}/{order_number}/{replace_invoice_number}/{payment_type}/{invoice_type}/{store_id}/replace', 'EPOS\InvoicesController@replace')->name('invoice_search.order');
            //Route::put('invoice/{invoice_number}/{order_number}/{replace_invoice_number}/replace', 'EPOS\InvoicesController@saveReplace')->name('invoice_search.update');
            Route::put('invoice/replace', 'EPOS\InvoicesController@saveReplace')->name('invoice_search.update');
            Route::post('invoice/print/counter', 'EPOS\InvoicesController@printCounter')->name('invoice_search.print');

            //Invoice generate
            Route::get('invoice_generate', 'EPOS\InvoicesController@generate')->name('invoice_generate');
            Route::post('invoice_generate_code', 'EPOS\InvoicesController@generateCode')->name('invoice_generate.code');
            Route::post('invoice_generate_oms', 'EPOS\InvoicesController@generateOms')->name('invoice_generate.oms');

            //Form Template
            Route::get('invoice/template/{type}', 'EPOS\FormTemplateController@invoice')->name('invoice_search.template');// support search by ?sale_order_number=xxx&return_order_number=xxx&invoice_number=xxx

            //Refund
            Route::get('refund', 'EPOS\RefundController@search')->name('refund_order_search');// support search by ?status=all&start_date=&end_date
            //Route::get('refund/export', 'EPOS\RefundController@export')->name('refund_order_export');// support search by ?status=all&start_date=&end_date
            Route::get('refund/{credit_number}/detail', 'EPOS\RefundController@detail')->name('refund_order_search.show'); // for view only
            Route::get('refund/{credit_number}/update', 'EPOS\RefundController@update')->name('refund_order_search.edit'); // for update only
            Route::put('refund/{credit_number}/update', 'EPOS\RefundController@updateDetail')->name('refund_order_search.update'); // for save update from AJAX call

		});

        /**
         * Report
         */
		Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            Route::get('product', 'ReportController@product')->name('product');
            Route::any('product/data', 'ReportController@anyDataProduct')->name('product.data');
            Route::any('product/print', 'ReportController@printProduct')->name('product.print');
			Route::get('order', 'ReportController@order')->name('order');
            Route::any('order/data', 'ReportController@anyDataOrder')->name('order.data');
			Route::any('order/print', 'ReportController@printOrder')->name('order.print');
			Route::get('order_status555', 'ReportController@orderStatus')->name('order_status');
			Route::any('order_status/data', 'ReportController@anyDataOrderStatus')->name('order_status.data');
			Route::any('order_status/print', 'ReportController@printOrderStatus')->name('order_status.print');
            Route::get('dailysale', 'ReportController@dailysale')->name('dailysale');
            Route::any('dailysale/data', 'ReportController@anyDataDailysale')->name('dailysale.data');
            Route::any('dailysale/print', 'ReportController@printDailysale')->name('dailysale.print');
            Route::get('treasury', 'ReportController@treasury')->name('treasury');
            Route::any('treasury/data', 'ReportController@anyDataTreasury')->name('treasury.data');
            Route::get('treasury/print', 'ReportController@printTreasury')->name('treasury.print');
            Route::get('replace', 'ReportController@replace')->name('replace');
            Route::any('replace/data', 'ReportController@anyDataReplace')->name('replace.data');
            Route::get('replace/print', 'ReportController@printReplace')->name('replace.print');
			Route::get('return_and_refund', 'ReportController@returnAndRefund')->name('return_and_refund');
			Route::any('return_and_refund/data', 'ReportController@anyDataReturnAndRefund')->name('return_and_refund.data');
			Route::get('return_and_refund/print', 'ReportController@printReturnAndRefund')->name('return_and_refund.print');
			Route::get('order_print', 'ReportController@orderPrint')->name('order_print');
			Route::any('order_print/data', 'ReportController@anyDataOrderPrint')->name('order_print.data');
			Route::any('order_print/print', 'ReportController@printOrderPrint')->name('order_print.print');
			Route::get('print', 'ReportController@orderPrint')->name('print');
			Route::get('coupon', 'ReportController@coupon');
			Route::any('coupon/data', 'ReportController@anyDataCoupon');
			Route::any('coupon/report', 'ReportController@printCoupon');
			Route::get('usage', 'ReportController@usage');
			Route::any('usage/data', 'ReportController@anyDataUsage');
			Route::any('usage/report', 'ReportController@printUsage');
			Route::any('invoice/', 'ReportController@invoice');
			Route::any('invoice/data', 'ReportController@anyDataInvoice');
		});

		/**
		 * Payment Method
		 */
		// Route::get('config/payment_method/report', 'ConfigController@report');
		Route::group(['prefix' => 'config'], function () {
		    Route::get('payment_method/data', 'ConfigController@paymentMethodDataTable')->name('payment_option.data');
      		Route::get('payment_method', 'ConfigController@paymentMethod')->name('payment_option.view');
			// Route::get('payment_method/{id}', 'ConfigController@managingItems')->name('payment_option.edit');
			// Route::put('payment_method/{id}/enableType', 'ConfigController@updateEnableType')->name('payment_option.edit');
			// Route::get('payment_method/{id}/data', 'ConfigController@installmentItemsData')->name('payment_option.data');
			// Route::get('payment_method/{id}/report', 'ConfigController@installmentItemsDataReport')->name('payment_option.edit');
			// Route::post('payment_method/{id}/productdata', 'ConfigController@installmentProductData')->name('payment_option.product_data');
			// Route::delete('payment_method/{id}', 'ConfigController@destroy')->name('payment_option.delete');
			// Route::delete('payment_method/{id}/{contentIds}', 'ConfigController@deleteInstallmentContent')->name('payment_option.delete');
			Route::put('payment_method/status_payment','ConfigController@updateStatus')->name('payment_option.update');
			Route::put('payment_method/status/{id}','ConfigController@updateStatusPayments');
			Route::post('payment_method/priority','ConfigController@updatePriorityPayments');
			// Route::get('create', 'ConfigController@create')->name('payment_option.create');
			// Route::post('create', 'ConfigController@store')->name('payment_option.store');
			// Route::put('create', 'ConfigController@update')->name('payment_option.update');
			// Route::put('{id}/edit', 'ConfigController@edit')->name('payment_option.edit');
			// Route::post('input_item', 'ConfigController@input_item')->name('payment_option.input_item');
			Route::get('payment_method/edit/{id}','ConfigController@editView')->name('payment_option.edit');
			Route::put('payment_method/edit/{id}','ConfigController@updatePaymentGateway')->name('payment_option.edit');
		});
		// Route::resource('config','ConfigController');

        Route::group(['prefix' => 'uploadfile', 'as' => 'uploadfile.'], function () {
            Route::post('images', 'UploadFileController@images')->name('upload_image');
        });

		/**
		 * Bank
		 */
		/*
		Route::get('bank/report', 'BankController@report');
		Route::group(['prefix' => 'bank'] ,function () {
			Route::get('data' , 'BankController@anyData');
			Route::put('status/{id}','BankController@updateStatus');
		});
		Route::resource('bank' , 'BankController'); 
		*/

		/**
		 * Stores
		 */
		/*
		Route::group(['prefix' => 'store'] ,function () {
			Route::post('address', 'StoreController@address');
			Route::get('data', 'StoreController@anyData');
			Route::get('view', 'StoreController@view');
			Route::get('report', 'StoreController@report');
			Route::get('{id}/edit','StoreController@edit');
			Route::get('{id}','StoreController@update');
			Route::put('status/{id}','StoreController@updateStatus');
			
		});		
		Route::resource('store', 'StoreController');
		*/
 
		/**
		 * Template Invoice
		 */
		Route::get('template/print', 'TemplateController@print');
		Route::get('template/preview', 'TemplateController@preview');

		/**
		 * Cron Member
		 */
		Route::group(['prefix' => 'cron/member'] ,function () {
			Route::get('data' , 'CronController@dataTableMembers');
			Route::put('count/{ids}','CronController@updateMemberCountById');
			Route::put('count/','CronController@updateMemberCount');
		});
		Route::resource('cron/member' , 'CronController@member'); 

		Route::group(['prefix' => 'maintenance', 'as' => 'maintenance.'] ,function () {
			Route::get('/' , 'MaintenanceController@index')->name('index');
			Route::put('/','MaintenanceController@update')->name('update');
		});


        /**
         * Email Subscription
         */
        Route::any('email_subscription/list','EmailSubscriptionController@anyData');
        Route::any('email_subscription/report','EmailSubscriptionController@report');
        Route::resource('email_subscription','EmailSubscriptionController@index');


		/**
         * Delivery Fee
         */
        Route::group(['prefix' => 'delivery_fee', 'as' => 'delivery_fee.'], function () {
            //Order
			Route::get('normal', 'DeliveryFeeController@normalFeeIndex')->name('normal.index');
			Route::get('normal/edit', 'DeliveryFeeController@normalFeeEdit')->name('normal.edit');
			Route::put('normal/edit', 'DeliveryFeeController@normalFeeEditSave')->name('normal.edit.save');
		});

		/**
         * Delivery Area
         */
        Route::group(['prefix' => 'delivery_area', 'as' => 'delivery_area.'], function () {
			Route::any('/', 'DeliveryAreaController@index');
			Route::any('/data', 'DeliveryAreaController@anyData');
			Route::put('/saveData', 'DeliveryAreaController@saveData')->name('update');
			Route::get('/district/{province_id}', 'DeliveryAreaController@getDistrict');
			Route::get('/sub_district/{distrcit_id}', 'DeliveryAreaController@getSubDistrict');
			Route::get('/sub_district_all/{province_id}', 'DeliveryAreaController@getSubDistrictAll');
		});
		Route::resource('delivery_area','DeliveryAreaController@index', ['except' => ['show', 'create', 'store']]);
		
		/**
         * Pickup Store
         */
		Route::get('pickupstore/data' , 'PickupStoreController@anyData');
		Route::put('pickupstore/saveDataEdit' , 'PickupStoreController@saveDataEdit')->name('pickupstore.update');
		Route::resource('pickupstore', 'PickupStoreController');
	});
});
