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
Route::post('users/update_avatar','UsersController@updateAvatar')->name('users.update_avatar');

Route::resource('users','UsersController',['only' => ['show','update','edit']]);
Route::get('users/{user}/votes','UsersController@votes')->name('users.votes');
Route::get('users/{user}/edit_avatar','UsersController@avatar')->name('users.edit_avatar');
Route::get('users/{user}/edit_password','UsersController@passwordForm')->name('users.edit_password');
Route::put('users/{user}/update_password','UsersController@updatePassword')->name('users.update_password');


// topics
Route::resource('topics','TopicsController',['except' => ['show']]);
//common
Route::get('emojis','TopicsController@emojis')->name('topics.emojis');

Route::post('topics/{topic}/vote','TopicsController@vote')->name('topics.vote');

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


Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('founder');