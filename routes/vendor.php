<?php

//Routes Don't require token
Route::group(['prefix' => 'v2/auth', 'middleware' => ['app_language']], function () {

    //Vendor Login Routes
    Route::post('vendor_login', 'Api\V2\AuthController@vendorLogin');

    //Vendor Step 1 Sign Up Routes
    Route::post('vendor_signup/step1', 'Api\V2\AuthController@vendorSignup');
});

//Routes require token
Route::group(['prefix' => 'v2', 'middleware' => ['app_language', 'auth:sanctum']], function () {

    // Vendor Step 2 Sign Up Routes
    Route::post('/auth/vendor_signup/step2', 'Api\V2\AuthController@vendorSignupTwo');
    Route::get('sustainabilities/requests', 'Api\V2\SustainabilityController@vendor');

    // Vendor Profile Update Routes
    Route::post('profile/vendor_update', 'Api\V2\ProfileController@vendor_update');

    //Vendor Portal Routes
    Route::group(['prefix' => 'vendor'], function () {

        // Vendor Dashboard Routes
        Route::get('dashboard', 'Api\V2\VendorController@dashboard');

        // Vendor Products Routes
        Route::group(['prefix' => 'products'], function () {

            //Get vendor ranked products
            Route::get('/', 'Api\V2\VendorController@vendorRankedProducts');

            //Get products descriptions
            Route::get('/descriptions', 'Api\V2\VendorProductController@vendorProductDesc');

            //Get vendor unranked products
            Route::get('unranked/all', 'Api\V2\VendorController@vendorUnrankedProducts');

            //Get vendor product details
            Route::get('details/{slug}', 'Api\V2\ProductController@VendorProductShow');

            //Add new vendor product
            Route::post('save', 'Api\V2\VendorProductController@store');

            //Update vendor product
            Route::post('update', 'Api\V2\VendorProductController@update');

            //Delete vendor product (Soft delete)
            Route::post('destroy', 'Api\V2\VendorProductController@destroy');

            // Vendor Products Reviews Routes
            Route::group(['prefix' => 'reviews'], function () {

                //Get all reviews on vendor
                Route::get('/', 'Api\V2\VendorController@vendorReviews');

                //import reviews details
                Route::get('/import', 'Api\V2\ReviewController@importReviews');

                //Get reviews details
                Route::get('{id}', 'Api\V2\VendorController@reviewDetails');
            });

            // Vendor Sustainability Verification Routes
            Route::group(['prefix' => 'verification'], function () {
                Route::post('/', 'Api\V2\SustainabilityRequestController@store');
                Route::post('/remove', 'Api\V2\SustainabilityRequestController@deAttachIcons');
                Route::get('/', 'Api\V2\SustainabilityController@ProductsVerifications');
                Route::get('{id}', 'Api\V2\SustainabilityController@ProductsVerificationDetails');
            });
        });


        // Vendor Products Sync Routes
        Route::group(['prefix' => 'bulk-product-upload'], function () {

            //Sync shopify Api
            Route::post('shopify', 'Api\V2\ShopifySyncController@sync');

            //Sync csv Api
            Route::post('csv', 'Api\V2\ProductBulkUploadController@bulk_upload');

            //Sync xml Api
            Route::post('xml', 'Api\V2\ProductBulkUploadController@bulk_upload_xml');

            //Sync xml auto Api
            Route::post('xml/auto', 'Api\V2\ProductBulkUploadController@bulk_upload_auto_xml');

            //Sync woocomerce Api
            Route::post('woocomerce', 'Api\V2\WoocomerceSyncController@sync');

            //Sync shopware Api
            Route::post('shopware', 'Api\V2\ProductBulkUploadController@shopware');
        });


        // Vendor Commission History Routes
        Route::group(['prefix' => 'commission_history'], function () {

            //Get all vendor commission history
            Route::get('/', 'Api\V2\VendorController@commission_history');

            //download commission history invoice
            Route::get('{order_code}/download', 'Api\V2\InvoiceController@vendor_invoice_download')->name('api.vendor.invoice.download');
        });


        // Vendor Orders History Routes
        Route::group(['prefix' => 'orders'], function () {

            //Get all vendor orders
            Route::get('/', 'Api\V2\VendorController@vendorOrders');

            //Get order details
            Route::get('{order_code}', 'Api\V2\VendorController@orderDetails');

            //Update tracking code for an order
            Route::post('{order_code}/update_tracking_code', 'Api\V2\VendorController@updateTrackingCode');

            //Download order invoice
            Route::get('{order_code}/download', 'Api\V2\InvoiceController@vendor_invoice_download')->name('api.invoice.download');
        });


        // Vendor Collections Routes
        Route::group(['prefix' => 'collections'], function () {

            // Get all vendor collections
            Route::get('/', 'Api\V2\CollectionController@index');

            // Add new vendor collection
            Route::post('/', 'Api\V2\CollectionController@store');

            // Delete vendor collection
            Route::post('remove', 'Api\V2\CollectionController@destroy');

            //Attach products to collection
            Route::post('attach', 'Api\V2\CollectionController@attachProduct');

            //De-attach products from collection
            Route::post('deattach', 'Api\V2\CollectionController@detachedProduct');

            //Update vendor collection
            Route::post('update', 'Api\V2\CollectionController@update');

            //Get vendor collection details
            Route::get('{id}', 'Api\V2\CollectionController@view');
        });

        // Vendor Help Routes
        Route::get('help', 'Api\V2\HelpController@vendor_help');

        //bulk edit products
        Route::post('bulk-product-edit', 'Api\V2\VendorProductController@bulkEdit');
        Route::post('bulk-description-edit', 'Api\V2\VendorProductController@bulkDescEdit');



        // Vendor Ranking Routes [Not used in frontend]
        Route::get('score/update', 'Api\V2\SustainabilityRankingController@UpdateScore');
        Route::get('level/update', 'Api\V2\SustainabilityRankingController@UpdateLevel');
        Route::get('adminReset', 'Api\V2\SustainabilityRequestController@adminReset');
    });
});
