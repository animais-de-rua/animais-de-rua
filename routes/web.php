<?php

use App\Http\Controllers\NewsletterController;

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

// Donation
Route::get('doar', function () {
    return Redirect::to('/donation', 301);
});

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
    Route::crud('user', 'UserCrudController');
    Route::crud('adoption', 'AdoptionCrudController');
    Route::crud('adopter', 'AdopterCrudController');
    // Route::crud('animal', 'AnimalCrudController');
    Route::crud('appointment', 'AppointmentCrudController');
    Route::crud('campaign', 'CampaignCrudController');
    Route::crud('donation', 'DonationCrudController');
    Route::crud('godfather', 'GodfatherCrudController');
    Route::crud('headquarter', 'HeadquarterCrudController');
    Route::crud('process', 'ProcessCrudController');
    Route::crud('territory', 'TerritoryCrudController');
    Route::crud('vet', 'VetCrudController');
    Route::crud('treatment', 'TreatmentCrudController');
    Route::crud('treatmenttype', 'TreatmentTypeCrudController');
    Route::crud('friend-card-modality', 'FriendCardModalityCrudController');
    Route::crud('partner', 'PartnerCrudController');
    Route::crud('partner-category', 'PartnerCategoryCrudController');
    Route::crud('protocol', 'ProtocolCrudController');
    Route::crud('protocol-request', 'ProtocolRequestCrudController');
    Route::crud('sponsor', 'SponsorCrudController');
    Route::crud('fat', 'FatCrudController');

    // Store
    Route::crud('store/products', 'StoreProductCrudController');
    Route::crud('store/orders', 'StoreOrderCrudController');
    // Route::crud('store/shipments', 'StoreShipmentCrudController');
    Route::crud('store/user/stock', 'StoreStockCrudController');
    Route::crud('store/user/transaction', 'StoreTransactionCrudController');
    Route::crud('store/supplier', 'SupplierCrudController');
    Route::crud('store/voucher', 'VoucherCrudController');

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
Route::post('form/{slug}', 'FormController@form_submit')->where('slug', 'volunteer|contact|apply|training|godfather|petsitting');

// Pages
Route::get('animals/{option}/{id}', 'PageController@animalsView');
Route::get('blank', 'PageController@blank');

Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);

// Newsletter
Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe']);
// Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);
// Route::get('/newsletter/check', [NewsletterController::class, 'checkSubscription']);
