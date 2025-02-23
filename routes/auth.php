<?php

// Routes dont require token
Route::group(['prefix' => 'v2/auth', 'middleware' => ['app_language']], function () {

    // Admin Route to force login [Not used in frontend]
    Route::post('admin/user/login', 'Api\V2\AuthController@forceLogin');

    //Password routes
    Route::group(['prefix' => 'password'], function () {

        //forget-password request Api
        Route::post('forget_request', 'Api\V2\PasswordResetController@forgetRequest');

        //forget-password confirm Api
        Route::post('confirm_reset', 'Api\V2\PasswordResetController@confirmReset');

    });

    //Resend verification code Api
    Route::post('resend_code', 'Api\V2\AuthController@resendCode');

    //Confirm verification code Api
    Route::post('confirm_code', 'Api\V2\AuthController@confirmCode');


});

// Routes  require token
Route::group(['prefix' => 'v2/auth', 'middleware' => ['app_language', 'auth:sanctum']], function () {

    //User Routes Apis
    Route::group(['prefix' => 'user'], function () {

        //Get user details Api
        Route::get('/', 'Api\V2\AuthController@user');

        //Set user walkthrough flag
        Route::post('walkthrough', 'Api\V2\AuthController@walkthrough');

        //Delete user (soft delete)
        Route::post('destroy', 'Api\V2\AuthController@deleteAccount');
    });

    //Logout user Api
    Route::get('logout', 'Api\V2\AuthController@logout');

    //Change password Api
    Route::post('password/update', 'Api\V2\AuthController@passwordUpdate');

});
