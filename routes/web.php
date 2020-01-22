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

// トップページ
Route::get('/', function () {
    return view('welcome');
});

// Laravelによるtaskインスタンスの操作
Route::resource('tasks', 'TasksController');

// FrontendのAjaxによるtaskインスタンスの操作
Route::post('ajax/tasks', 'TasksController@ajaxCRUD');
Route::get('ajax/tasks', 'TasksController@ajaxIndex');

// ユーザー登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');
