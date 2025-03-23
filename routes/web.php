<?php

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
Route::get('/', [PageController::class, 'index'])->name('home');

// Pages
Route::get('{page}/{subs?}', [PageController::class, 'index'])->middleware('web')
    ->where(['page' => '^((?!admin).)|[^/]*$', 'subs' => '.*']);
