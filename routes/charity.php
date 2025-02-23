<?php


//Routes Don't require a token
Route::group(
    ['prefix' => 'v2/auth', 'middleware' => ['app_language']], function () {

    //Charity Login Routes
    Route::post('charity_login', 'Api\V2\AuthController@charityLogin');

    //Charity Step 1 Sign Up Routes
    Route::post('charity_signup/step1', 'Api\V2\AuthController@charitySignup');


});

//Routes require a token
Route::group(['prefix' => 'v2', 'middleware' => ['app_language', 'auth:sanctum']], function () {

    // Charity Step 2 Sign Up Routes
    Route::post('/auth/charity_signup/step2', 'Api\V2\AuthController@charitySignupTwo');

    // Charity Profile Update Routes

    Route::post('profile/charity_update', 'Api\V2\ProfileController@charity_update');


    //Charity Portal Routes
    Route::group(['prefix' => 'charity'], function () {

        // Charity Dashboard Routes
        Route::get('dashboard', 'Api\V2\CharityController@dashboard');

        // Charity Invoices Routes
        Route::group(['prefix' => 'invoices'], function () {

            //Get all Charity invoices
            Route::get('/', 'Api\V2\CharityController@charity_invoices_index');

            //Add new Charity invoices
            Route::post('store', 'Api\V2\CharityController@charity_invoices_store');

        });

        // Charity Help Routes
        Route::get('help', 'Api\V2\HelpController@charity_help');


    });


});
