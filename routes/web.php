<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['web', 'auth', 'roles']],function(){
    Route::group(['roles'=>'user'],function(){
        Route::resource('user', App\Http\Controllers\UserBiasaController::class);
    });

    Route::group(['roles'=>'admin'],function(){
        Route::get('/admin/{path}', 'App\Http\Controllers\AdminController@index')->where('path', '[A-Za-z]+');
        Route::resource('admin', App\Http\Controllers\AdminController::class);
        Route::resource('users', App\Http\Controllers\UserManagementController::class);
        Route::resource('roles', App\Http\Controllers\RoleController::class);
    });
});
