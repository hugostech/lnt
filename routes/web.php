<?php

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

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('product')->group(function () {
    Route::get('list', 'ProductController@listProduct')->name('product_list');
    Route::get('create', 'ProductController@showCreateProduct')->name('product_create_show');
    Route::post('create', 'ProductController@createProduct')->name('product_create');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
