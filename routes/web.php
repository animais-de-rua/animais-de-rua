<?php

use App\Enums\FormsEnum;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Auth
Auth::routes();

// Welcome
Route::get('/', [PageController::class, 'index'])
    ->name('home');

// Donation shortcut
Route::get('doar', fn () => Redirect::to('/donation', 301));

// Store shortcuts
Route::get('{store}', fn () => Redirect::to('https://shop.animaisderua.org/', 301))
    ->where(['store' => '^(store|shop|loja)$']);

// Forms
Route::get('form/{slug}', [FormController::class, 'formView'])->where('slug', '[a-z]{2,12}')->name('form');
Route::post('form/{slug}', [FormController::class, 'formSubmit'])->where('slug', implode('|', FormsEnum::values()));
Route::post('newsletter', [PageController::class, 'subscribeNewsletter'])->name('newsletter');

// Pages
Route::get('animals/{option}/{id}', [PageController::class, 'animalsView']);
Route::get('blank', [PageController::class, 'blank']);

Route::get('{page}/{subs?}', [PageController::class, 'index'])
    ->middleware('web')
    ->where(['page' => '^((?!admin).)|[^/]*$', 'subs' => '.*']);