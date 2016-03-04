<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', 'AuthenticationController@getLogin');
Route::post('/', 'AuthenticationController@postLogin');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', 'AuthenticationController@getLogout');
    Route::get('/home', 'HomeController@index');
});

Route::group(['middleware' => 'admin', 'prefix' => '/adminHome'], function () {
    Route::get("/", 'AdminController@index');

    Route::group(['prefix' => '/techMgmt'], function () {
        Route::get("/", 'AdminController@techMgmt');
        Route::post("/new", 'AdminController@createUser');
        Route::post("edit", 'AdminController@editUser');
        Route::post("delete", 'AdminController@deleteUser');
    });

    Route::group(['prefix' => '/roleMgmt'], function () {
        Route::get("/", 'AdminController@roleMgmt');
        Route::post("/new", 'AdminController@createRole');
        Route::post("/edit", 'AdminController@editRole');
        Route::post("/delete", 'AdminController@deleteRole');
    });
});


Route::get('/test', function () {
    $uuid = \Webpatser\Uuid\Uuid::generate(4);
    return str_replace('-', '', $uuid);
});

