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

// Admin
Route::group(['prefix' => config('backpack.base.route_prefix'), 'middleware' => ['admin'], 'namespace' => 'Admin'], function() {
	Route::get('/dashboard', 					'DashboardController@dashboard')->name('dashboard');

	// Terminal
	Route::get('/terminal', 					'UserCrudController@terminal')->name('terminal');
	Route::post('/terminal/run', 				'UserCrudController@terminal_run')->name('terminal_run');

	// CRUD
	CRUD::resource('user', 						'UserCrudController');
	CRUD::resource('adoption', 					'AdoptionCrudController');
	CRUD::resource('animal', 					'AnimalCrudController');
	CRUD::resource('appointment', 				'AppointmentCrudController');
	CRUD::resource('donation', 					'DonationCrudController');
	CRUD::resource('godfather', 				'GodfatherCrudController');
	CRUD::resource('headquarter', 				'HeadquarterCrudController');
	CRUD::resource('process', 					'ProcessCrudController');
	CRUD::resource('territory', 				'TerritoryCrudController');
	CRUD::resource('vet', 						'VetCrudController');
	CRUD::resource('treatment', 				'TreatmentCrudController');
	CRUD::resource('treatmenttype', 			'TreatmentTypeCrudController');

	// API
	Route::get('user/ajax/filter/{role?}', 				'APICrudController@userFilter');
	Route::get('user/ajax/search/{role?}', 				'APICrudController@userSearch');

	Route::get('adoption/ajax/filter/', 				'APICrudController@adoptionFilter');
	Route::get('adoption/ajax/search/', 				'APICrudController@adoptionSearch');

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

// Language
Route::any('lang/{locale}', function ($locale) {
	Session::put('locale', $locale);
	return redirect(url()->previous());
})->where('locale', '[a-z]{2}(-[A-Z]{2})?')->name('lang');

// Pages
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);