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
Route::get('/', 								'HomeController@welcome')->name('welcome');
Route::get('/terms-of-service', 				'HomeController@termsOfService')->name('terms');
Route::get('/privacy-policy', 					'HomeController@privacyPolicy')->name('privacy');


// Auth
Auth::routes();

Route::get('login/facebook', 					'Auth\LoginController@facebookLogin');
Route::get('login/facebook/callback', 			'Auth\LoginController@facebookCallback');

Route::get('login/google', 						'Auth\LoginController@googleLogin');
Route::get('login/google/callback', 			'Auth\LoginController@googleCallback');


// Auth middleware
Route::group(['middleware' => ['auth']], function() {
	Route::get('/dashboard', 					'HomeController@index')->name('dashboard');
});


// Admin
Route::group(['prefix' => config('backpack.base.route_prefix'), 'middleware' => ['admin'], 'namespace' => 'Admin'], function() {

	// Terminal
	Route::get('/terminal', 					'UserCrudController@terminal')->name('terminal');
	Route::post('/terminal/run', 				'UserCrudController@terminal_run')->name('terminal_run');

	// CRUD
	CRUD::resource('user', 						'UserCrudController');
	CRUD::resource('donation', 					'DonationCrudController');
	CRUD::resource('godfather', 				'GodfatherCrudController');
	CRUD::resource('headquarter', 				'HeadquarterCrudController');
	CRUD::resource('process', 					'ProcessCrudController');
	CRUD::resource('territory', 				'TerritoryCrudController');
	CRUD::resource('vet', 						'VetCrudController');
	CRUD::resource('treatment', 				'TreatmentCrudController');
	CRUD::resource('treatmenttype', 			'TreatmentTypeCrudController');

	// API
	Route::get('godfather/ajax/filter', 				'APICrudController@godfatherFilter');
	Route::get('godfather/ajax/search', 				'APICrudController@godfatherSearch');

	Route::get('headquarter/ajax/filter', 				'APICrudController@headquarterFilter');
	Route::get('headquarter/ajax/search', 				'APICrudController@headquarterSearch');
	
	Route::get('process/ajax/filter', 					'APICrudController@processFilter');
	Route::get('process/ajax/search', 					'APICrudController@processSearch');
	
	Route::get('vet/ajax/filter', 						'APICrudController@vetFilter');
	Route::get('vet/ajax/search', 						'APICrudController@vetSearch');

	Route::get('territory/ajax/filter/{level?}', 		'APICrudController@territoryFilter');
	Route::get('territory/ajax/search/{level?}', 		'APICrudController@territorySearch');

	Route::post('dropzone/{column}/{entity}', 			'CrudController@handleDropzoneUploadRaw');
	Route::post('dropzone/{column}/{entity}/remove', 	'CrudController@handleDropzoneRemoveRaw');
});

// Pages
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);