<?php


/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


//Auth Route
Auth::routes(['verify' => true, 'register' => false]);

//Home Page
Route::get('/', 'HomeController@index')->name('home');

//Logout
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

//Reports
Route::get('/commission-log', 'ReportController@commissionHistory')->name('commission-log.index');

//Coupon Form
Route::post('/coupon/get_form', 'CouponController@get_coupon_form')->name('coupon.get_coupon_form');
Route::post('/coupon/get_form_edit', 'CouponController@get_coupon_form_edit')->name('coupon.get_coupon_form_edit');

//Language route
Route::post('/language', 'LanguageController@changeLanguage')->name('language.change');

//Currency route
Route::post('/currency', 'CurrencyController@changeCurrency')->name('currency.change');

//Download invoices
Route::get('invoice/{order_id}', 'InvoiceController@invoice_download')->name('invoice.download');
Route::get('vendor/invoice/{order_id}', 'InvoiceController@vendor_invoice_download')->name('vendor.invoice.download');

//Product view
Route::get('/product/{slug}', 'HomeController@product')->name('product');

//Unsubscribe url
Route::get('/newsletter/unsubscribe', 'HomeController@unsubscribe')->name('newsletter.unsubscribe');
