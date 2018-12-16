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

// Auth
Auth::routes();

Route::get('login/facebook', 'Auth\LoginController@facebookLogin');
Route::get('login/facebook/callback', 'Auth\LoginController@facebookCallback');

Route::get('login/google', 'Auth\LoginController@googleLogin');
Route::get('login/google/callback', 'Auth\LoginController@googleCallback');

// Admin
Route::group(['prefix' => config('backpack.base.route_prefix'), 'middleware' => ['admin'], 'namespace' => 'Admin'], function () {
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');

    // Terminal
    Route::get('/terminal', 'UserCrudController@terminal')->name('terminal');
    Route::post('/terminal/run', 'UserCrudController@terminal_run')->name('terminal_run');

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

    // API
    Route::get('{entity}/ajax/{action}/{extra?}', 'APICrudController@ajax');

    Route::post('dropzone/{column}/{entity}/{thumb}/{size}/{quality}', 'CrudController@handleDropzoneUploadRaw');
    Route::post('dropzone/{column}/{entity}/remove', 'CrudController@handleDropzoneRemoveRaw');

    // View as
    Route::any('/view-as-role/{role}', 'ViewAsController@view_as_role')->name('view-as-role');
    Route::any('/view-as-permission/{permission}/{state}', 'ViewAsController@view_as_permission')->name('view-as-permission');
    Route::any('/view-as-headquarter/{headquarter}/{state}', 'ViewAsController@view_as_headquarter')->name('view-as-headquarter');
});

// API
Route::group(['prefix' => 'api', 'middleware' => ['web']], function () {
    Route::get('animals/adoption/{district}/{specie}', 'PageController@getAnimalsAdoption');
    Route::get('animals/godfather/{district}/{specie}', 'PageController@getAnimalsGodfather');
});

// Language
Route::any('lang/{locale}', function ($locale) {
    Session::put('locale', $locale);
    return redirect(url()->previous());
})->where('locale', '[a-z]{2}(-[A-Z]{2})?')->name('lang');

// Pages
Route::get('animals/{option}/{id}', 'PageController@animalsView');
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);
