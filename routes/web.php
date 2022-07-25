<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

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


Route::get("install", function(){
    Artisan::call('ms');
});



Auth::routes();

Route::get("/logout", function(){
    auth()->logout();
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
