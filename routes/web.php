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
    return view('users.landingpage');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['web', 'auth', 'roles']], function () {
    Route::group(['roles' => 'user'], function () {
        Route::get('/user/{path}', 'App\Http\Controllers\UserBiasaController@index')->where('path', '[A-Za-z]+');
        Route::resource('user', App\Http\Controllers\UserBiasaController::class);
        Route::get('/idols/all','App\Http\Controllers\IdolController@index');
        Route::get('/concerts/all','App\Http\Controllers\ConcertController@index');
        Route::get('/concerts/view/{id}','App\Http\Controllers\ConcertController@show');
        Route::get('/concerts/all/{id}','App\Http\Controllers\ConcertController@concertById');
        Route::resource('ticket', App\Http\Controllers\TicketController::class);
        Route::get('/mytickets', 'App\Http\Controllers\TicketController@myTicket');
        Route::get('/mytickets/invoice', 'App\Http\Controllers\TicketController@exportTicket');
    });
    Route::group(['roles' => 'admin'], function () {
        Route::get('/admin/{path}', 'App\Http\Controllers\AdminController@index')->where('path', '[A-Za-z]+');
        Route::resource('admin', App\Http\Controllers\AdminController::class);
        Route::resource('users', App\Http\Controllers\UserManagementController::class);
        Route::resource('roles', App\Http\Controllers\RoleController::class);
        Route::resource('idols', App\Http\Controllers\IdolController::class);
        Route::resource('concerts', App\Http\Controllers\ConcertController::class);
        Route::post('/idols/multiple-delete/{id}','App\Http\Controllers\IdolController@multipleDestroy');
        Route::post('/concerts/multiple-delete/{id}','App\Http\Controllers\ConcertController@multipleDestroy');

    });
});
