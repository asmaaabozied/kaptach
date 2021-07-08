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

Route::get('/', function () {
    return view('auth.adminLogin');
});

//Auth::routes();
Route::namespace('Auth')->group(function () {
    Route::get('login', 'AdminLoginController@showLoginForm');
    Route::get('logout', 'AdminLoginController@logout')->name('logout');
    Route::post('login', 'AdminLoginController@login')->name('login');
});
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth:admin']], function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::resource('roles', 'RolesController');
    });
    Route::resource('/languages', 'LanguagesController');
    /*push notifications*/
    Route::resource('notifications', 'NotificationsController');
    Route::post('notifications/mark-as-read', 'NotificationsController@markAsRead')->name('notifications.mark-as-read');
    //
    Route::get('transfers/store', 'StoreController@index')->name('store.index');
    Route::get('transfers/transfers-offered-forSale', 'StoreController@transfersOfferedForSale');
    Route::post('transfers/offer-for-sale', 'StoreController@offerForSale')->name('transfers.offerForSale');
    Route::post('undo/offer-for-sale/transfer', 'StoreController@undoOfferForSale')->name('transfers.undoOfferForSale');
    Route::get('transfer/cancel/{id}/offer-for-sale/', 'StoreController@cancelOfferForSale')->name('transfers.cancelOfferForSale');
    Route::post('store/transfer/{id}/buy', 'StoreController@buy');
    Route::get('transfer/{id}/search-for-exchange', 'ExchangesController@searchForExchange')->name('transfers.search_for_exchange');
    Route::get('get/{exchange_id}/transfers', 'ExchangesController@getTransferByCompanyId');
    Route::post('exchanges/{id}/store', 'ExchangesController@store')->name('exchanges.store');
    Route::post('apply/{exchange_id}/offer', 'ExchangesController@applyOffer')->name('apply.offer');
    Route::get('exchanges/{id}/show', 'ExchangesController@show')->name('exchanges.show');
    Route::get('offer/{id}/accepted', 'ExchangesController@offerAccepted')->name('offer.accepted');
    Route::get('offer/{id}/rejected', 'ExchangesController@offerRejected')->name('offer.rejected');
    Route::get('get/{id}/offers', 'ExchangesController@getOffers')->name('get.offers');
    Route::get('exchange/{id}/info', 'ExchangesController@getExchangeInfo')->name('exchange.info');

    Route::resource('admins', 'AdminsController');
    Route::get('admins/{id}/changeStatus', 'AdminsController@changeStatus')->name('admins.changeStatus');
    Route::delete('admins/{id}/soft-delete', 'AdminsController@softDelete')->name('admins.soft_delete');
    //Admin Profile
    Route::get('admin/profile', 'AdminsController@profile')->name('profile');
    Route::put('admin/profile_update', 'AdminsController@profile_update')->name('profile_update');
    //Change Password
    Route::get('admin/{id}/reset-password', 'AdminsController@resetPassword')->name('admins.reset_password');
    Route::get('admin/change_password', 'AdminsController@change_password')->name('change_password');//view
    Route::put('admin/update_password', 'AdminsController@update_password')->name('update_password');//update
    //Settings
    Route::get('settings', 'SettingsController@company_settings')->name('settings');
    Route::get('clients/settings', 'SettingsController@client_settings')->name('client_settings');
    Route::put('/settings/update', 'SettingsController@settings_update')->name('settings_update');
    //hosts
    Route::resource('hosts', 'HostsController');
    Route::get('hosts/{id}/change-status', 'HostsController@changeStatus')->name('hosts.change_status');
    Route::delete('hosts/{id}/soft-delete', 'HostsController@softDelete')->name('hosts.soft_delete');
    Route::get('hosts/{id}/reset-password', 'HostsController@resetPassword')->name('hosts.reset_password');
    Route::put('hosts/{id}/update-password', 'HostsController@updateResetPassword')->name('hosts.update_password');
    Route::get('hosts/{id}/add-host', 'HostsController@addHost')->name('hosts.add_host');

    //driver
    Route::resource('drivers', 'DriversController');
    Route::delete('drivers/{id}/soft-delete', 'DriversController@softDelete')->name('drivers.soft_delete');

    Route::get('drivers/{id}/schedule', 'DriversController@schedule')->name('drivers.schedule');
    Route::post('drivers/{id}/create-schedule', 'DriversController@createSchedule')->name('drivers.create_schedule');
    Route::get('drivers/{shiftId}/edit-schedule', 'DriversController@editSchedule')->name('drivers.edit_schedule');
    Route::put('drivers/{shiftId}/update-schedule', 'DriversController@updateSchedule')->name('drivers.update_schedule');
    Route::delete('drivers/{shiftId}/soft-delete-schedule', 'DriversController@softDeleteSchedule')->name('drivers.soft_delete_schedule');
    Route::get('drivers/{shiftId}/change-driver', 'DriversController@changeDriver')->name('drivers.change_driver');
    Route::get('drivers/{id}/change-status', 'DriversController@changeStatus')->name('drivers.change_status');
    Route::post('drivers/getDriverShift', 'DriversController@getDriverShift')->name('drivers.get_driver_shift');
    Route::get('drivers/{id}/reset-password', 'DriversController@resetPassword')->name('drivers.reset_password');
    Route::put('drivers/{id}/update-password', 'DriversController@updateResetPassword')->name('drivers.update_password');
    //shifts
    Route::resource('shifts', 'ShiftsController');

    Route::resource('companies', 'CompaniesController');
    Route::get('companies/{id}/changeStatus', 'CompaniesController@changeStatus')->name('companies.changeStatus');
    Route::delete('companies/{id}/soft-delete', 'CompaniesController@softDelete')->name('companies.soft_delete');
    Route::get('/getAll', 'CompaniesController@getAllCompanies');
    Route::get('companies/{slug}/drivers', 'CompaniesController@drivers')->name('companies.drivers');

    Route::resource('my-clients', 'ClientsController');
    Route::get('my-clients/{id}/changeStatus', 'ClientsController@changeStatus')->name('my-clients.changeStatus');
    Route::get('my-clients/{slug}/info', 'ClientsController@info')->name('my-clients.info');
    Route::delete('my-clients/{id}/soft-delete', 'ClientsController@softDelete')->name('my-clients.soft_delete');
    Route::get('my-clients/{id}/add-price', 'ClientsController@addPrice')->name('my-clients.add_price');
    Route::post('my-clients/{id}/store-price', 'ClientsController@storePrice')->name('my-clients.store_price');
    Route::get('my-clients/{id}/add-payment', 'ClientsController@addPayment')->name('my-clients.add_payment');
    Route::post('my-clients/{id}/store-payment', 'ClientsController@storePayment')->name('my-clients.store_payment');
    Route::get('my-clients/{slug}/admins', 'ClientsController@Admins')->name('my-clients.admins');
    Route::get('my-clients/{slug}/add-admins', 'ClientsController@addAdmins')->name('my-clients.add-admins');
    Route::get('my-clients/{id}/add-invoice', 'ClientsController@addInvoice')->name('my-clients.add_invoice');
    Route::post('my-clients/{id}/store-invoice', 'ClientsController@storeInvoice')->name('my-clients.store_invoice');

    Route::resource('stations', 'StationsController');
    Route::get('stations/{id}/changeStatus', 'StationsController@changeStatus')->name('stations.changeStatus');
    Route::delete('stations/{id}/soft-delete', 'StationsController@softDelete')->name('stations.soft_delete');

    Route::resource('cars', 'CarsController');
    Route::get('cars/{id}/changeStatus', 'CarsController@changeStatus')->name('cars.changeStatus');
    Route::delete('cars/{id}/soft-delete', 'CarsController@softDelete')->name('cars.soft_delete');

    Route::resource('carmodels', 'CarModelsController');
    Route::get('get-car-model', 'CarModelsController@getCarModelByID')->name('carmodels.getCarModelByID');
    Route::delete('carmodels/{id}/soft-delete', 'CarModelsController@softDelete')->name('carmodels.soft_delete');

    //REPORTS
    Route::post('reports/load-data', 'ReportsController@loadData')->name('annual_report.load_data');
    Route::post('reports/load-client-balance', 'ReportsController@loadClientBalance')->name('client_report.load_client_balance');
    Route::get('reports/annual', 'ReportsController@annual')->name('annual_report');
    Route::get('/annual_report_print', 'ReportsController@annual_print');
    Route::get('reports/{id}/payments/{year}/show/{month}', 'ReportsController@payments')->name('payments_report');
    Route::get('reports/{id}/invoices/{year}/show/{month}', 'ReportsController@invoices')->name('invoices-report');
    Route::get('reports/{id}/transportation/{year}/show/{month}', 'ReportsController@transportation')->name('transportation_report');
    Route::get('reports/clients-balance', 'ReportsController@clients_balance')->name('clients_balance_report');
    Route::get('reports/charts', 'ReportsController@charts')->name('charts_report');
//
    Route::post('devices', 'DevicesController@store')->name('devices.store');
    Route::resource('guests', 'GuestsController');
    Route::post('guests/get', 'GuestsController@GetGuestsAjax')->name('guests.get_guests_ajax');
    Route::get('guests/{id}/viewModalWithData', 'GuestsController@viewModalWithData')->name('guests.view_modal_with_data');
    Route::get('guests/{id}/add_transfer/{airport_id}/{type}', 'GuestsController@addTransfer')->name('guests.add_transfer');
    Route::post('guests/{id}/transfer-store', 'GuestsController@transferStore')->name('guests.transfer_store');
    Route::get('guests/{id}/transfers', 'GuestsController@transfers')->name('guests.transfers');
    Route::post('guests/check/identity-number', 'GuestsController@checkIdentityNumber')->name('guests.check_identity_number');
    Route::get('gmap/locations', 'GmapLocationsController@getLocations')->name('gmap.locations');
});
//for companies
Route::group(['namespace' => 'Companies', 'middleware' => 'auth:admin'], function () {

    Route::resource('dashboard', 'DashboardController');
    Route::resource('airports', 'AirportsController');
    Route::delete('airports/{id}/soft-delete', 'AirportsController@softDelete')->name('airports.soft_delete');

    Route::resource('shuttles', 'ShuttlesController');
    Route::get('shuttles/{id}/reservation', 'ShuttlesController@reservation')->name('shuttles.reservation');
    Route::put('shuttles/{id}/storeReservation', 'ShuttlesController@storeReservation')->name('shuttles.store_reservation');
    Route::get('shuttles/{id}/schedule/{type}', 'ShuttlesController@schedule')->name('shuttles.schedule');
    Route::get('shuttles/{id}/create/{type}', 'ShuttlesController@create')->name('shuttles.create');
    Route::delete('shuttles/{id}/soft-delete', 'ShuttlesController@softDelete')->name('shuttles.soft_delete');


    Route::resource('transfers', 'TransfersController', ['except' => 'show']);
    Route::get('transfers/{id}/add/{type}', 'TransfersController@add')->name('transfers.add');
    Route::delete('transfers/{id}/soft-delete', 'TransfersController@softDelete')->name('transfers.soft_delete');
    Route::get('/transfers/approve/{id}', 'TransfersController@approve')->name('transfers.approve');

    Route::get('/transfers/show/{id}', 'TransfersController@show')->name('transfers.show');

    Route::post('/transfers/transfer_price', 'TransfersController@transfer_price')->name('transfers.transfer_price');
    Route::get('/transfers/{id}/showTicket', 'TransfersController@showTicket')->name('transfers.showTicket');
    Route::get('/transfers/{id}/downloadPDF', 'TransfersController@downloadPDF')->name('transfers.downloadPDF');
    Route::get('/transfers/{id}/show-cancel-transfer', 'TransfersController@showCancelTransfer')->name('transfers.show_cancel');
    Route::put('/transfers/{id}/cancel-transfer', 'TransfersController@cancelTransfer')->name('transfers.cancel');
    Route::get('/transfers/{id}/start-transfer', 'TransfersController@start')->name('transfers.start');
    Route::get('/transfers/{id}/reset-transfer', 'TransfersController@reset')->name('transfers.reset');
    Route::put('/transfers/{id}/end-transfer', 'TransfersController@end')->name('transfers.end');


    Route::get('transfers/{id}/get', 'TransfersController@getTransferById')->name('transfers.getter');
    Route::get('transfers/{id}/offer-for-sale', 'TransfersController@offerForSale')->name('transfers.offer_for_sale');
    Route::get('transfers/{id}/viewModalForDuplicate', 'TransfersController@viewModalForDuplicate')->name('transfers.viewModalForDuplicate');
    Route::get('transfers/{id}/duplicate-transfer/{airport_id}/{type}', 'TransfersController@duplicateTransfer')->name('transfers.duplicate_transfer');
    Route::post('transfers/{id}/store-duplicate-transfer/{airport_id}/{type}', 'TransfersController@storeDuplicateTransfer')->name('transfers.store_duplicate_transfer');
    //shuttle price
    Route::resource('shuttles-price', 'ShuttlesPriceController');
    Route::delete('shuttles-price/{id}/soft-delete', 'ShuttlesPriceController@softDelete')->name('shuttles-price.soft_delete');
    Route::post('shuttle-price/check', 'ShuttlesPriceController@getShuttlePriceList')->name('shuttle-price.check');
    Route::resource('transfers-price', 'TransfersPriceController');
    Route::delete('transfers-price/{id}/soft-delete', 'TransfersPriceController@softDelete')->name('transfers-price.soft_delete');

    Route::resource('tours-price', 'ToursPriceController');
    Route::delete('tours-price/{id}/soft-delete', 'ToursPriceController@softDelete')->name('tours-price.soft_delete');


    Route::resource('payments', 'PaymentsController');
    Route::delete('payments/{id}/soft-delete', 'PaymentsController@softDelete')->name('payments.soft_delete');

    Route::resource('invoices', 'InvoicesController');
    Route::get('invoices/downloadPDF/{id}', 'InvoicesController@downloadPDF')->name('invoices.downloadPDF');
    Route::delete('invoices/{id}/soft-delete', 'InvoicesController@softDelete')->name('invoices.soft_delete');


    Route::get('/prices-list', 'DashboardController@pricesList')->name('prices-list');

    //SETTINGS
    /*Route::get('companies/{id}/edit', 'CompaniesController@edit')->name('profile-edit');
    Route::put('companies/{id}/update', 'CompaniesController@update')->name('profile-update');*/
});

//for clients
Route::group(['prefix' => 'clients'], function () {
    Route::get('request/{slug}/transfers', 'ExternalController@externalTransferRequest');
    Route::get('booking/{slug}/transfers', 'ExternalController@externalBookingTransfer')->name('clients.booking');
});

Route::group(['namespace' => 'Clients', 'prefix' => 'clients', 'middleware' => 'auth:admin'], function () {
    Route::resource('dashboard', 'DashboardController');
    Route::get('/dashboard/changePosition/{type}/{id}', 'DashboardController@changePosition');//type airport or hotel 

    Route::resource('transfers', 'TransfersController', ['as' => 'clients', 'except' => 'externalTransferRequest']);
    Route::get('transfers/{id}/add/{type}', 'TransfersController@add')->name('clients.transfers.add');
    Route::delete('transfers/{id}/soft-delete', 'TransfersController@softDelete')->name('clients.transfers.soft_delete');
    Route::post('/transfers/transfer_price', 'TransfersController@transfer_price')->name('clients.transfer_price');
    Route::get('/transfers/{id}/show-cancel-transfer', 'TransfersController@showCancelTransfer')->name('clients.transfers.show_cancel');
    Route::put('/transfers/{id}/cancel-transfer', 'TransfersController@cancelTransfer')->name('clients.transfers.cancel');

    Route::resource('shuttles', 'ShuttlesController', ['as' => 'clients']);
    Route::get('shuttles/{id}/schedule/{type}', 'ShuttlesController@schedule')->name('clients.shuttles.schedule');
    Route::get('shuttles/{id}/reservation', 'ShuttlesController@reservation')->name('clients.shuttles.reservation');
    Route::put('shuttles/{id}/storeReservation', 'ShuttlesController@storeReservation')->name('clients.shuttles.store_reservation');

    Route::get('prices', 'PriceController@index')->name('prices.index');
    Route::get('shuttle-price', 'PriceController@shuttle')->name('price.shuttle');
    Route::get('transfer-price', 'PriceController@transfer')->name('price.transfer');
    Route::get('tours-price', 'PriceController@tours')->name('price.tours');

    Route::resource('invoices', 'InvoicesController', ['as' => 'clients']);
    Route::get('invoices/downloadPDF/{id}', 'InvoicesController@downloadPDF')->name('clients.invoices.downloadPDF');

    Route::resource('payments', 'PaymentsController', ['as' => 'clients']);
});

