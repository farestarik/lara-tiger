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


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'auth', 'localeViewPath']
    ], function(){


        Route::prefix("dashboard")->name("dashboard.")->group(function(){

            // Redirects Routes

            Route::get('500', function(){
                abort(500);
            });

            // Dashboard Routes

            Route::get('/', "DashboardController@index")->name('index');


            // Admins Routes

            Route::resource('admins', 'AdminController');



            // Profile Routes

            Route::get('profile', 'ProfileController@index')->name("profile.index");

            Route::get('profile/{profile}', 'ProfileController@index');

            Route::put('profile/{profile?}', 'ProfileController@update')->name('profile.update');

            // Settings Routes

            Route::get('settings', 'SettingsController@index')->name('settings.index');
            Route::put('settings/{setting}', 'SettingsController@update')->name('settings.update');

        });

});



Route::get('/', function(){
    if(auth()->check()){
        return redirect("/dashboard");
    }else{
        return redirect()->route('login');
    }
});
