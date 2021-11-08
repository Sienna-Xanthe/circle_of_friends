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

/**
 * 黑名单
 */
Route::prefix('black')->group(function () {
    Route::post('showAllWhiteUser', 'UserInfo\UserInfoController@showAllWhiteUser'); //显示所有白名单用户
    Route::post('addBlackList', 'UserInfo\UserInfoController@addBlackList'); //添加黑名单用户
    Route::post('showAllBlackUser', 'UserInfo\UserInfoController@showAllBlackUser'); //显示所有黑名单用户
    Route::post('showAllWUser', 'UserInfo\UserInfoController@showAllWUser'); //显示所有黑名单用户
    Route::post('deleteBlackList', 'UserInfo\UserInfoController@deleteBlackList'); //删除黑名单用户
});//--lyt

/**
 * 个人信息
 */
Route::prefix('info')->group(function () {
    Route::post('showUserInfo', 'UserInfo\UserInfoController@showUserInfo'); //显示个人信息
    Route::post('updateUserInfo', 'UserInfo\UserInfoController@updateUserInfo'); //更新用户信息
    Route::post('addSign', 'UserInfo\UserInfoController@addSign'); //添加签名
});//--lyt

/**
 * 评论了我的动态的消息
 */
Route::prefix('comment')->group(function () {
    Route::post('showCommentInfo', 'UserInfo\UserInfoController@showCommentInfo'); //评论了我的动态的消息

});//--lyt

/**
 * 举报信息
 */
Route::prefix('tip')->group(function () {
    Route::post('showTipInformationByDetail', 'Admin\TipController@showTipInformationByDetail'); //举报信息
    Route::post('showTipInformationByMany', 'Admin\TipController@showTipInformationByMany'); //举报信息
    Route::post('updatefailedTip', 'Admin\TipController@updatefailedTip'); //更新
    Route::post('deleteSuccessTip', 'Admin\TipController@deleteSuccessTip'); //删除
});//--lyt


/**
 * 个性化
 */
Route::prefix('gxh')->group(function () {
    Route::post('insertBackground', 'UserInfo\UserInfoController@insertBackground'); //添加背景
});//--lyt



