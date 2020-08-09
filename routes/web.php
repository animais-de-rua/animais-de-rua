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

// Home
Route::get('/', 'PageController@index', 'home');

// Old routes
Route::get('{lang}/{subs?}', function () {
    return Redirect::to('/', 301);
})->where(['lang' => '^(en|pt)$', 'subs' => '.*']);

// Old store routes
Route::get('{store}', function () {
    return Redirect::to('https://shop.animaisderua.org/', 301);
})->where(['store' => '^(store|shop|loja)$']);

// Auth
Auth::routes();

Route::get('login/facebook', 'Auth\LoginController@facebookLogin');
Route::get('login/facebook/callback', 'Auth\LoginController@facebookCallback');

Route::get('login/google', 'Auth\LoginController@googleLogin');
Route::get('login/google/callback', 'Auth\LoginController@googleCallback');

Route::post('/login', 'PageController@login');
Route::get('/logout', 'PageController@logout');

// Admin
Route::group(['prefix' => config('backpack.base.route_prefix'), 'middleware' => ['admin'], 'namespace' => 'Admin'], function () {
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');

    // Reports
    Route::get('/reports', 'ReportController@report')->name('reports');
    Route::post('/reports/{type}/{action}', 'ReportController@action');

    // Terminal
    Route::get('/terminal', 'UserCrudController@terminal')->name('terminal');
    Route::post('/terminal/run', 'UserCrudController@terminal_run')->name('terminal_run');
    Route::get('/symlink', 'UserCrudController@symlink')->name('symlink');
    Route::post('/symlink/run', 'UserCrudController@symlink_run')->name('symlink_run');

    // CRUD
    CRUD::resource('user', 'UserCrudController');
    CRUD::resource('adoption', 'AdoptionCrudController');
    CRUD::resource('adopter', 'AdopterCrudController');
    CRUD::resource('animal', 'AnimalCrudController');
    CRUD::resource('appointment', 'AppointmentCrudController');
    CRUD::resource('campaign', 'CampaignCrudController');
    CRUD::resource('donation', 'DonationCrudController');
    CRUD::resource('godfather', 'GodfatherCrudController');
    CRUD::resource('headquarter', 'HeadquarterCrudController');
    CRUD::resource('process', 'ProcessCrudController');
    CRUD::resource('territory', 'TerritoryCrudController');
    CRUD::resource('vet', 'VetCrudController');
    CRUD::resource('treatment', 'TreatmentCrudController');
    CRUD::resource('treatmenttype', 'TreatmentTypeCrudController');
    CRUD::resource('friend-card-modality', 'FriendCardModalityCrudController');
    CRUD::resource('partner', 'PartnerCrudController');
    CRUD::resource('partner-category', 'PartnerCategoryCrudController');
    CRUD::resource('protocol', 'ProtocolCrudController');
    CRUD::resource('protocol-request', 'ProtocolRequestCrudController');
    CRUD::resource('sponsor', 'SponsorCrudController');
    CRUD::resource('fat', 'FatCrudController');

    // Store
    CRUD::resource('store/products', 'StoreProductCrudController');
    CRUD::resource('store/orders', 'StoreOrderCrudController');
    CRUD::resource('store/shipments', 'StoreShipmentCrudController');
    CRUD::resource('store/user/stock', 'StoreStockCrudController');
    CRUD::resource('store/user/transaction', 'StoreTransactionCrudController');
    CRUD::resource('store/supplier', 'SupplierCrudController');
    CRUD::resource('store/voucher', 'VoucherCrudController');

    // API
    Route::get('{entity}/ajax/{action}/{arg1?}/{arg2?}', 'APICrudController@ajax');
    Route::post('api/appointment/approve', 'APICrudController@approveAppointment');
    Route::post('api/treatment/approve', 'APICrudController@approveTreatment');
    Route::post('api/process/contact', 'APICrudController@toggleProcessContacted');

    Route::post('dropzone/{column}/{entity}/{thumb}/{size}/{quality}', 'CrudController@handleDropzoneUploadRaw');
    Route::post('dropzone/{column}/{entity}/remove', 'CrudController@handleDropzoneRemoveRaw');

    // Cache
    Route::post('cache/flush', function () {\Cache::flush();});
    Route::post('cache/config', function () {Artisan::call('config:cache');});
    Route::post('cache/config/clear', function () {Artisan::call('config:clear');});
    Route::post('cache/route', function () {Artisan::call('route:cache');});
    Route::post('cache/route/clear', function () {Artisan::call('route:clear');});
    Route::post('cache/view', function () {Artisan::call('view:cache');});
    Route::post('cache/view/clear', function () {Artisan::call('view:clear');});
    Route::post('maintenance/up', function () {Artisan::call('up');});
    Route::post('maintenance/down', function () {Artisan::call('down', ['--allow' => $_SERVER['REMOTE_ADDR']]);});

    Route::post('cache/update-products', function () {\Cache::forget('products');});

    // View as
    Route::any('/view-as-role/{role}', 'ViewAsController@view_as_role')->name('view-as-role');
    Route::any('/view-as-permission/{permission}/{state}', 'ViewAsController@view_as_permission')->name('view-as-permission');
    Route::any('/view-as-headquarter/{headquarter}/{state}', 'ViewAsController@view_as_headquarter')->name('view-as-headquarter');
});

// View as clear all
Route::any('/view-as-clear', 'Admin\ViewAsController@view_as_clear')->name('view-as-clear');

// API
Route::group(['prefix' => 'api', 'middleware' => ['web']], function () {
    Route::any('token', function () {return ['token' => csrf_token()];})->name('token');

    Route::get('animals/adoption/{district}/{specie}', 'PageController@getAnimalsAdoption');
    Route::get('animals/godfather/{district}/{specie}', 'PageController@getAnimalsGodfather');
    // Route::get('prestashop/products', 'PrestaShopController@getProducts');
});

// Language
Route::any('lang/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect(url()->previous());
})->where('locale', '[a-z]{2}(-[A-Z]{2})?')->name('lang');

// Forms
Route::get('form/{slug}', 'FormController@form_view')->where('slug', '[a-z]{2,12}')->name('form');
Route::post('form/{slug}', 'FormController@form_submit')->where('slug', 'volunteer|contact|apply|training|godfather');
Route::post('newsletter', 'PageController@subscribeNewsletter');

// Pages
Route::get('animals/{option}/{id}', 'PageController@animalsView');
Route::get('blank', 'PageController@blank');

Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);
