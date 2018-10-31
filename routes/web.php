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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('product')->group(function () {
    Route::get('list', 'ProductController@listProduct')->name('product_list');
    Route::get('create', 'ProductController@showCreateProduct')->name('product_create_show');
    Route::get('{product_id}/edit', 'ProductController@editProduct')->name('product_edit');
    Route::post('create', 'ProductController@createProduct')->name('product_create');
    Route::post('update/{product_id}', 'ProductController@updateProduct')->name('product_update');
    Route::post('searchall', 'ProductController@searchProducts')->name('product_search');
    Route::post('addtrace/{product_id}', 'ProductController@addTrace')->name('product_add_trace');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
