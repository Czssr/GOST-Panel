<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::namespace('Api\Admin')->prefix('admin')->middleware('api.setJWTGuard:admin')->group(function() {
    // 用户登录
    Route::post('login', 'LoginController@login');

    Route::middleware('api.refreshToken')->group(function() {
        // 当前用户信息
        Route::get('info', 'LoginController@info');
        // 用户退出
        Route::post('logout', 'LoginController@logout');
        // 修改当前登陆管理员密码
        Route::post('resetPassword', 'LoginController@resetPassword');


        // 用户
        Route::resource('user', 'UserController');
        // 节点
        Route::resource('node', 'NodeController');
        // 审计
        Route::resource('audit', 'AuditController');
    });
});


Route::namespace('Api\User')->prefix('user')->middleware('api.setJWTGuard:user')->group(function() {
    // 用户登录
    Route::post('login', 'LoginController@login');

    Route::middleware('api.refreshToken')->group(function() {
        // 当前用户信息
        Route::get('info', 'LoginController@info');
        // 用户退出
        Route::post('logout', 'LoginController@logout');
    });
});
