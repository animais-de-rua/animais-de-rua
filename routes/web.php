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
	Route::get('/terminal', 					'\App\Http\Controllers\UserController@terminal')->name('terminal');
	Route::post('/terminal/run', 				'\App\Http\Controllers\UserController@terminal_run')->name('terminal_run');

	// API
	Route::get('/territory/list/{level?}', 		'TerritoryCrudController@ajax_list');
	Route::get('/territory/search', 			'TerritoryCrudController@ajax_search');
	Route::post('/dropzone/{column}/{entity}', 			'CrudController@handleDropzoneUploadRaw');
	Route::post('/dropzone/{column}/{entity}/remove', 	'CrudController@handleDropzoneRemoveRaw');

	// CRUD
	CRUD::resource('headquarter', 				'HeadquarterCrudController');
	CRUD::resource('territory', 				'TerritoryCrudController');
	CRUD::resource('process', 					'ProcessCrudController');
});

// Pages
Route::get('{page}/{subs?}', ['uses' => 'PageController@index'])
    ->where(['page' => '^((?!admin).)*$', 'subs' => '.*']);