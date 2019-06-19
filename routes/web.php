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
Route::get('/index',"CouponController@index");
Route::resource('posts', 'Post\PostController');
Route::get('/lists', 'CouponController@lists');
Route::get('/curl', 'CouponController@curl');

Route::post('/curl2', 'CouponController@curl2');
Route::get('/curl2', 'CouponController@curl2');

Route::get('/curl3', 'CouponController@curl3');
Route::post('/text', 'CouponController@text');
Route::post('/text', 'CouponController@text');

Route::get('/md5', 'CouponController@md5');

Route::get('/pass', 'CouponController@pass');

Route::get('/asymm2', 'CouponController@asymm2');

Route::get('/priv', 'CouponController@priv');
Route::post('/syntony', 'CouponController@syntony');

//支付
Route::get('/aliyun', 'CouponController@aliyun');
Route::get('/coupon/pay', 'CouponController@pay');

//注册
Route::post('/reg', 'RegController@reg');
Route::get('/reg', 'RegController@reg');

//登陆
Route::post('/login', 'RegController@login');
Route::get('/login', 'RegController@login');
//登陆跳转
Route::get('/locat', 'RegController@locat');


Route::post('/server', 'RegController@server');
Route::get('/server', 'RegController@server');

Route::get('/send', 'RegController@send');
Route::post('/send', 'RegController@send');