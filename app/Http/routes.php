<?php

// ====================
// 画面系
// ====================

// ルート
Route::get('/', function() {
    return redirect()->to('index');
});
Route::get('login/', function() {
    return redirect()->to('auth/login');
});
Route::get('logout/', function() {
    return redirect()->to('auth/logout');
});

/* 一般 */
Route::get('index/', 'View\IndexController@index');

// 画面
Route::get('index', 'View\IndexController@index');
Route::get('place', 'View\PlaceController@index');
Route::get('plan', 'View\PlanController@index');
Route::get('mail', 'View\MailController@index');

// 認証画面
Route::group(['prefix' => 'auth'], function() {
    Route::get('login', 'Auth\AuthController@getLogin');
    Route::post('login', 'Auth\AuthController@postLogin');
    Route::get('logout', 'Auth\AuthController@getLogout');
    Route::get('register', 'Auth\AuthController@getRegister');
    Route::post('register', 'Auth\AuthController@postRegister');
});

// 一覧
Route::group(['prefix' => 'api/index'], function() {
    Route::get('lists', 'Api\IndexController@lists');
    Route::get('edit', 'Api\IndexController@edit');
    Route::post('save', 'Api\IndexController@save');
    Route::get('user', 'Api\IndexController@user');
    Route::post('submit', 'Api\IndexController@submit');
    Route::post('delete', 'Api\IndexController@delete');
    Route::get('map', 'Api\IndexController@map');
});

// 会場
Route::group(['prefix' => 'api/place'], function() {
    Route::get('lists', 'Api\PlaceController@lists');
    Route::get('edit', 'Api\PlaceController@edit');
    Route::post('save', 'Api\PlaceController@save');
    Route::post('delete', 'Api\PlaceController@delete');
});

// 予定
Route::group(['prefix' => 'api/plan'], function() {
    Route::get('lists', 'Api\PlanController@lists');
    Route::get('edit', 'Api\PlanController@edit');
    Route::post('save', 'Api\PlanController@save');
    Route::post('delete', 'Api\PlanController@delete');
});

// メール
Route::group(['prefix' => 'api/mail'], function() {
    Route::get('list', 'Api\MailController@lists');
    Route::get('detail', 'Api\MailController@detail');
    Route::post('send', 'Api\MailController@send');
    Route::get('user', 'Api\MailController@user');
});
