<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. Theseedi
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/','TopicsController@index')->name('root');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/oauth/github', 'Auth\LoginController@redirectToProvider')->name('github.provider');
Route::get('/oauth/github/callback', 'Auth\LoginController@handleProviderCallback')->name('github.callback');


// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

//users
Route::resource('users','UsersController',['only' => ['show','update','edit']]);

// topics
Route::resource('topics','TopicsController',['except' => ['show']]);

Route::get('topics/{topic}/{slug?}','TopicsController@show')->name('topics.show');

Route::post('upload_image','TopicsController@uploadImage')->name('topics.upload_image');

Route::resource('categories','CategoriesController',['only' => ['show']]);

//replies
Route::resource('replies','RepliesController',['only' => ['store','destroy'] ]);

// notification
Route::group(['middleware' => ['auth','web']],function (){
    Route::resource('notifications','NotificationsController',['only' => ['index']]);
});

Route::get('permission-denied','PagesController@permissionDenied')->name('permission-denied');