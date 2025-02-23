<?php

// Routes Don't require token
Route::group(['prefix' => 'v2', 'middleware' => ['app_language']], function () {

    // Policies Routes
    Route::group(['prefix' => 'policies'], function () {
        Route::get('seller', 'Api\V2\PolicyController@sellerPolicy')->name('policies.seller');
        Route::get('buyer', 'Api\V2\PolicyController@buyerPolicy')->name('policies.buyer');
        Route::get('charity', 'Api\V2\PolicyController@charityPolicy')->name('policies.charity');
        Route::get('terms', 'Api\V2\PolicyController@termsPolicy')->name('policies.terms');
        Route::get('imprint', 'Api\V2\PolicyController@imprintPolicy')->name('policies.imprint');
        Route::get('privacy', 'Api\V2\PolicyController@privacyPolicy')->name('policies.privacy');
    });


    // Products Routes
    Route::group(['prefix' => 'products'], function () {

        // Get best-selling products Api
        Route::get('best-seller', 'Api\V2\ProductController@bestSeller');

        // Get best sustainable products Api
        Route::get('best-sustainable', 'Api\V2\ProductController@bestSustainable');

        // Get new arrival products Api
        Route::get('new-arrival', 'Api\V2\ProductController@newarrival');

        // Get low price products Api
        Route::get('low-price', 'Api\V2\ProductController@lowprice');

        // Most shared products Api
        Route::get('most-shared', 'Api\V2\ProductController@mostshared');


        // Get best related products Api
        Route::get('related/{id}', 'Api\V2\ProductController@related');

        // Products search Api
        Route::get('search', 'Api\V2\ProductController@search');

        // Products details Api
        Route::get('{slug}', 'Api\V2\ProductController@show');
    });

    // Countries/states Routes
    Route::group(['prefix' => 'config'], function () {

        //Get all available countries
        Route::get('countries', 'Api\V2\AddressController@getCountries');

        //Get all states in a country
        Route::get('states/{id}', 'Api\V2\AddressController@getStates');

        //Get all cites in a state
        Route::get('cites/{id}', 'Api\V2\AddressController@getCities');
    });

    // Get all Brands (vendors) Api
    Route::get('brands', 'Api\V2\ShopController@index');

    //Send Contact form Api
    Route::post('contact/send', 'Api\V2\HomeController@contactPost');

    //Get search suggestions Api
    Route::get('get-search-suggestions', 'Api\V2\SearchSuggestionController@getList');

    //Get all banners Api
    Route::get('banners', 'Api\V2\BannerController@index');

    //Get home ads Api
    Route::get('ads', 'Api\V2\BannerController@ads');

    //Get all parent-categories Api
    Route::get('categories', 'Api\V2\CategoryController@index');

    //Get all sub-categories by parent id Api
    Route::get('sub-categories/{id}', 'Api\V2\SubCategoryController@index');

    //Get all sustainabilities icons Api
    Route::get('sustainabilities', 'Api\V2\SustainabilityController@index');

    //Get all charities Api
    Route::get('charities', 'Api\V2\CharityController@index');

    //Subscribe to Newsletter Api
    Route::post('newsletter/subscribe', 'Api\V2\HomeController@subscribe');

    //Stripe webhook
    Route::any('stripe/webhook', 'Api\V2\StripeController@webhook');

    //Paypal webhook
    Route::any('paypal/webhook', 'Api\V2\PaypalController@webhook');

    //social login
    Route::get('/social-login/redirect/{provider}', 'Auth\LoginController@redirectToProvider')->name('social.login');
    Route::get('/social-login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');
});


// Routes require token
Route::group(['prefix' => 'v2', 'middleware' => ['app_language', 'auth:sanctum']], function () {

    // Notifications Apis
    Route::group(['prefix' => 'notifications'], function () {

        //Get all notifications for user Api
        Route::get('/', 'Api\V2\AuthController@userNotification');

        //Set all notifications to read Api
        Route::post('read', 'Api\V2\AuthController@userNotificationRead');
    });

    //Support Messages Apis
    Route::group(['prefix' => 'messages'], function () {
        //Get all messages for user Api
        Route::get('/', 'Api\V2\MessagesController@index');

        //Get message details Api
        Route::get('{id}', 'Api\V2\MessagesController@show');

        //Create message Api
        Route::post('save', 'Api\V2\MessagesController@store');

        //Delete message Api
        Route::post('/remove/{id}', 'Api\V2\MessagesController@destroy');
    });

    //Create url to upload to AWS S3 Api
    Route::get('/aws/create/signedurl', 'Api\V2\UploadController@CreateSignedUrl');
});


// 404 Fallback Route.

Route::fallback(
    function () {
        return response()->json(
            [
                'data' => [],
                'success' => false,
                'status' => 404,
                'message' => 'Invalid Route'
            ],
            404
        );
    }
);
