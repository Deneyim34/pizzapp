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

Route::post('/login','AuthController@Login');
Route::post('/register','AuthController@Register');
Route::get('/district','DistrictController@index');
Route::get('/city','CityController@index');
Route::get('/country','CountryController@index');

/*Route::get('/',function(){

});*/

Route::group(['prefix' => 'admin','middleware' => 'scope:admin'], function()
{
    Route::get('authCheck', function () {
    return request()->user();
    })->middleware('auth:api');

    Route::post('logout','AuthController@Logout')->middleware('auth:api');
    Route::apiResource('order','OrderController')->middleware('auth:api');
    Route::apiResource('product','ProductController')->middleware('auth:api');
    Route::apiResource('user','UserController')->middleware('auth:api');
    Route::apiResource('status','StatusController')->middleware('auth:api');
    Route::apiResource('product-size','ProductSizeController')->middleware('auth:api');
    Route::apiResource('district','DistrictController')->middleware('auth:api');
    Route::apiResource('city','CityController')->middleware('auth:api');
    Route::apiResource('country','CountryController')->middleware('auth:api');
});
//

Route::group(['middleware' => 'scope:customer,admin'], function()
{
    Route::get('/authCheck', function () {
    return request()->user();
    })->middleware('auth:api');
    Route::post('/logout','AuthController@Logout')->middleware('auth:api');
    Route::get('/order','OrderController@index')->middleware('auth:api');
    Route::post('/order','OrderController@store')->middleware('auth:api');
    Route::get('/user/{id}','UserController@show')->middleware('auth:api');
    Route::post('/user','UserController@store')->middleware('auth:api');
    Route::put('/user/{id}','UserController@update')->middleware('auth:api');
    Route::get('/product','ProductController@index')->middleware('auth:api');
    Route::get('/product/{id}','ProductController@show')->middleware('auth:api');
    Route::get('/status','StatusController@index')->middleware('auth:api');
    Route::get('/product-size','ProductSizeController@index')->middleware('auth:api');
});


