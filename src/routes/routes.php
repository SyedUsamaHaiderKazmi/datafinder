<?php

use Illuminate\Support\Facades\Route;
use SUHK\DataFinder\App\Http\Controllers\DataSearchController;

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

Route::post('liveSearchTableRender', [DataSearchController::class, 'liveSearchTableRender'])->name('liveSearchTableRender');
