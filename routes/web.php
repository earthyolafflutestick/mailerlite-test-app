<?php

use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\SubScriberController;
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

Route::get('/', function() {
    return view('home');
});

Route::prefix('apikeys')->group(function () {
    Route::get('/create', [ApiKeyController::class, 'create'])->name('create');
    Route::post('/store', [ApiKeyController::class, 'store'])->name('store');
});

Route::resource('subscribers', SubScriberController::class)->except(['show']);
