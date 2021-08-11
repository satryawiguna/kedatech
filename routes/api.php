<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', 'Api\RegisterController@actionRegister');

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'Api\AuthController@actionLogin');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/register', 'Api\AuthController@actionRegister');
        Route::post('/logout', 'Api\AuthController@actionLogout');
    });
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'profile'], function () {
    Route::put('/update/me', 'Api\UserController@actionProfileUpdate');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'customer'], function () {
    Route::get('/', 'Api\UserController@actionCustomerAll');
    Route::delete('/delete/{id}', 'Api\UserController@actionCustomerDestroy');
    Route::delete('/deletes', 'Api\UserController@actionCustomersDestroy');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'chat'], function () {
    Route::post('/send', 'Api\MessageController@actionChatSend');
    Route::get('/me', 'Api\MessageController@actionChatMe');
    Route::get('/', 'Api\MessageController@actionChatAll');
});

Route::group(['middleware' => 'auth:api', 'prefix' => 'feedback'], function () {
    Route::get('/', 'Api\FeedbackController@actionFeedbackAll');
    Route::post('/send', 'Api\FeedbackController@actionFeedbackSend');
});
