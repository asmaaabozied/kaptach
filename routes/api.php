<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('login', 'EmployerAuthController@login');
    Route::post('register', 'EmployerAuthController@register');
    Route::post('password/forgot', 'EmployerAuthController@forgetPassword');
    Route::get('password/reset', 'EmployerAuthController@resetPassword')->name('password/reset');
    Route::post('store_password/reset', 'EmployerAuthController@storePassword')->name('store-password');
    Route::post('checkUsername', 'EmployerAuthController@checkUsername');
    Route::get('companies', 'CompaniesController@index');
    Route::get('countries', 'CountriesController@index');
    Route::post('companies/create', 'CompaniesController@generateCompany');
    Route::middleware('auth:api')->group(function () {
        Route::get('companies/find/{id}/hosts', 'CompaniesController@FindHosts');
        Route::get('cars/{id}/check', 'CarsController@atLeastOneCarIsExist');

        Route::get('getEmployer', 'EmployersController@index');
        Route::post('editProfile', 'EmployersController@editProfile');
        Route::post('uploadProfileImage', 'EmployersController@uploadProfileImage');
        Route::get('logout', 'EmployersController@logout');
        //Transfers
        Route::get('transfers', 'TransfersController@index');
        Route::post('transfers/create', 'TransfersController@create');
        Route::get('transfers/for-sale', 'StoreController@forSale');
        Route::get('transfers/sold', 'StoreController@sold');
        Route::get('transfers/offer/{id}/for-sale', 'StoreController@offerForSale');
        Route::get('transfers/undo/offer/{id}/for-sale', 'StoreController@undoOfferForSale');
        Route::get('transfers/{id}/buy', 'StoreController@buy');
        Route::get('transfers/history', 'TransfersController@history');
        Route::get('transfers/show/{id}', 'TransfersController@show');
        Route::post('transfers/cancel/{id}', 'TransfersController@cancel');
        Route::post('transfers/start/{id}', 'TransfersController@start');
        Route::post('transfers/end/{id}', 'TransfersController@end');
        Route::get('transfers/driver-acceptance/{id}', 'TransfersController@driverAcceptance');
        Route::post('clients/get-transfer-price', 'ClientsController@getTransferPrice');
        Route::get('call/driver/{id}', 'TransfersController@callDriver');
        Route::get('guest/received/{id}', 'TransfersController@guestReceived');
        Route::get('driver/replied/{id}', 'TransfersController@driverReplied');
        Route::get('guest/delivered/{id}', 'TransfersController@guestDelivered');
        Route::get('driver/replied/{id}', 'TransfersController@driverReplied');

        Route::post('notifications/send', 'NotificationsController@send');

        Route::post('devices/', 'DevicesController@store')->name('devices.store');
        Route::delete('devices/{token}', 'DevicesController@destroy')->name('devices.destroy');
        //airports
        Route::get('airports', 'AirportsController@index');
        Route::get('carModels', 'CarModelsController@index');
        Route::get('stations', 'StationsController@index');
        Route::get('clients', 'ClientsController@index');
        Route::post('clients/create', 'ClientsController@create');

    });
});
Route::group(['namespace' => 'Api\V2', 'prefix' => 'V2'], function () {
    Route::post('admin/login', 'AdminAuthController@login');
    Route::post('admin/devices/', 'DevicesController@adminStore')->name('admin_devices.store');
    Route::middleware('auth:api')->group(function () {
        //exchange
        Route::post('exchanges/{id}/store', 'ExchangesController@store')->name('exchanges.store');
        Route::get('undo/{id}/exchange', 'ExchangesController@undoExchange')->name('exchanges.undo');
        Route::get('find/exchanges/{exchange_id}/matching', 'ExchangesController@findMatching')->name('find.matching');
        //offers
        Route::get('get/{id}/offers', 'OffersController@getOffers');
        Route::post('apply/{id}/offer', 'OffersController@applyOffer');
        Route::get('undo/apply/{id}/offer', 'OffersController@undoApplyOffer');
        Route::get('accept/{id}/offer', 'OffersController@offerAccepted');
        Route::get('reject/{id}/offer', 'OffersController@offerRejected');
        //Transfers
        Route::get('transfers', 'TransfersController@index');
        Route::get('transfers/history', 'TransfersController@history');
    });
});

