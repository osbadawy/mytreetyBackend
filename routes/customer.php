<?php


// Routes dont require token
Route::group(['prefix' => 'v2', 'middleware' => ['app_language']], function () {

    // Customer help page Api
    Route::get('customer/help', 'Api\V2\HelpController@customer_help');

    // Customer auth routes
    Route::group(['prefix' => '/auth'], function () {

        //Customer login Api
        Route::post('login', 'Api\V2\AuthController@login');

        //Customer Register Api
        Route::post('signup', 'Api\V2\AuthController@signup');
    });
});


// Routes require token
Route::group(['prefix' => 'v2', 'middleware' => ['app_language', 'auth:sanctum']], function () {

    //Customer dashboard routes
    Route::group(['prefix' => '/auth'], function () {

        // Customer Orders Apis
        Route::group(['prefix' => '/orders'], function () {

            //Get customer orders Api
            Route::get('/', 'Api\V2\PurchaseHistoryController@index');

            //Get order details Api
            Route::get('{code}', 'Api\V2\PurchaseHistoryController@details');

            //Download order invoice
            Route::get('{order_code}/download', 'Api\V2\InvoiceController@invoice_download')->name('api.customer.invoice.download');
        });

        //  Gift Cards
        Route::group(['prefix' => '/giftcards'], function () {

            // Get list of all redeemed giftcards Api
            Route::get('/', 'Api\V2\GiftCardController@index');

            // Get list of all sent giftcards Api
            Route::get('/sent', 'Api\V2\GiftCardController@sent');

            // Redeem giftcards Api
            Route::post('redeem', 'Api\V2\GiftCardController@RedeemCode');
        });


        // Referral dashboard
        Route::group(['prefix' => '/referral'], function () {

            // Get  referral dashboard
            Route::get('/', 'Api\V2\ReferralController@index');

            // Get list of all redeemed referral Api
            Route::get('/all', 'Api\V2\ReferralController@all');

            // Get list of top referral Api
            Route::get('/top', 'Api\V2\ReferralController@top');
        });


        // Send Refund request Api
        Route::group(['prefix' => '/refund-request'], function () {
            Route::post('send', 'Api\V2\RefundRequestController@send');
        });

        // User Apis
        Route::group(['prefix' => '/user'], function () {

            // Customer update details Apis
            Route::post('update', 'Api\V2\ProfileController@update')->middleware('auth:sanctum');

            // Customer Address Apis
            Route::group(['prefix' => 'shipping'], function () {

                // Get all Customer addresses
                Route::get('address', 'Api\V2\AddressController@addresses');

                // Create Customer address
                Route::post('create', 'Api\V2\AddressController@createShippingAddress');

                // Update Customer address
                Route::post('update/{id}', 'Api\V2\AddressController@updateShippingAddress');

                // Set default Customer address
                Route::post('make_default/{id}', 'Api\V2\AddressController@makeShippingAddressDefault');

                // Delete Customer address
                Route::post('delete/{id}', 'Api\V2\AddressController@deleteShippingAddress');
            });
        });

        // Reviews Apis
        Route::group(['prefix' => '/reviews'], function () {

            // Submit a product review
            Route::post('submit', 'Api\V2\ReviewController@submit');

            // delete a product review
            Route::post('delete/{id}', 'Api\V2\ReviewController@delete');
        });

        //Customer Reviews Apis
        Route::group(['prefix' => '/customer_reviews'], function () {

            //Get active customer reviews
            Route::get('/', 'Api\V2\ReviewController@customerReviews');

            //Get pending customer reviews
            Route::get('pending', 'Api\V2\ReviewController@customerPendingReviews');
        });
    });


    //Cart Apis
    Route::group(['prefix' => 'cart'], function () {

        //Get products from cart
        Route::get('/', 'Api\V2\CartController@getList');

        //Add products to cart
        Route::post('/', 'Api\V2\CartController@add');

        //Remove product from cart
        Route::post('remove/{id}', 'Api\V2\CartController@destroy');

        //Change quantity of products from cart
        Route::post('change-quantity', 'Api\V2\CartController@changeQuantity');
    });


    //Wishlist Apis
    Route::group(['prefix' => 'wishlist'], function () {

        //Get products from wishlist
        Route::get('/', 'Api\V2\WishlistController@index');

        //Add products to wishlist
        Route::post('/', 'Api\V2\WishlistController@add');

        //Remove product from wishlist
        Route::post('/remove/{id}', 'Api\V2\WishlistController@remove');
    });

    // Make Order Api
    Route::post('order/store', 'Api\V2\OrderController@store');

    //Apply Coupon Api
    Route::post('coupon-apply', 'Api\V2\CouponController@apply_coupon_code');

    //Remove Coupon Api
    Route::post('coupon-remove', 'Api\V2\CouponController@remove_coupon_code');

    //Apply points Api
    Route::post('points-apply', 'Api\V2\CouponController@apply_points');

    //Create Gift card
    Route::post('giftcards/store', 'Api\V2\GiftCardController@store');

    // Cart Summary Api
    Route::get('cart-summary', 'Api\V2\CartController@summary');
});
