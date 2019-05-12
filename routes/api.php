<?php

use Illuminate\Http\Request;

// Auth API Route
Route::post('login', 'AuthController@loginAPI');
Route::post('register', 'AuthController@registerAPI');

// User API Route
Route::get('user', 'UserController@getUserAPI');
Route::get('user/show', 'UserController@getUserProfileAPI')->middleware('auth:api');
Route::get('user/{id}', 'UserController@getUserProfileByIdAPI')->middleware('auth:api');
Route::put('user/update', 'UserController@updateUserProfileAPI')->middleware('auth:api');
Route::delete('user/destroy', 'UserController@destroyUserProfileAPI')->middleware('auth:api');

// Post API Route
Route::get('post', 'PostController@getPostAPI');
Route::post('post', 'PostController@createPostAPI')->middleware('auth:api');
Route::put('post/{post}', 'PostController@updatePostAPI')->middleware('auth:api');
Route::delete('post/{post}', 'PostController@deletePostAPI')->middleware('auth:api');
