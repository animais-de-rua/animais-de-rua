<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

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

    // Website apis
    Route::any('token', fn () => ['token' => csrf_token()])->name('token');
    Route::get('animals/adoption/{district}/{specie}', [PageController::class, 'getAnimalsAdoption']);
    Route::get('animals/godfather/{district}/{specie}', [PageController::class, 'getAnimalsGodfather']);

    // Public API
    Route::get('/token', [APIController::class, 'getToken']);
    Route::get('/stats', [APIController::class, 'getStats']);
    Route::get('/headquarters', [APIController::class, 'getHeadquarters']);
    Route::get('/campaigns', [APIController::class, 'getCampaigns']);
    Route::get('/products', [APIController::class, 'getProducts']);
    Route::get('/sponsors', [APIController::class, 'getSponsors']);
    Route::get('/partners', [APIController::class, 'getPartners']);
    Route::get('/friend-card', [APIController::class, 'getFriendCard']);
    Route::get('/help', [APIController::class, 'getHelp']);
});
