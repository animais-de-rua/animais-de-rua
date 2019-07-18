<?php

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

Route::group(['middleware' => ['api']], function () {

    Route::get('/token', 'APIController@getToken');

    Route::get('/stats', 'APIController@getStats');

    Route::get('/headquarters', 'APIController@getHeadquarters');

    Route::get('/campaigns', 'APIController@getCampaigns');

    Route::get('/products', 'APIController@getProducts');

    Route::get('/sponsors', 'APIController@getSponsors');

    Route::get('/partners', 'APIController@getPartners');

    Route::get('/friend-card', 'APIController@getFriendCard');

    Route::get('/help', 'APIController@getHelp');

});
