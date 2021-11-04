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

/**
 * admin登录
 */
Route::prefix('admin')->group(function () {
    Route::post('login', 'Login\AdminLoginController@login'); //admin登录
    Route::post('logout', 'Login\AdminLoginController@logout'); //admin注销
    Route::post('registered', 'Login\AdminLoginController@registered'); //admin注册
});//--pxy
/**
 * user登录
 */
Route::prefix('user')->group(function () {
    Route::get('login', 'Login\UserLoginController@userlogin'); //user登录
    Route::get('scancode', 'Login\UserLoginController@scancode'); //user扫码
    Route::post('registered', 'Login\UserLoginController@registered'); //user注册
});//--pxy

Route::post('upload', 'Upload\UploadController@upload'); //将上传的图片或视频转换为url//--zqz

