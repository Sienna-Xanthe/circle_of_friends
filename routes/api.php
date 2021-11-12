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
Route::middleware('refreshToken')->prefix('tip')->group(function () {//
    Route::post('showTipInformationByDetail', 'Admin\TipController@showTipInformationByDetail'); //举报信息
    Route::post('showTipInformationByMany', 'Admin\TipController@showTipInformationByMany'); //举报信息
    Route::post('updatefailedTip', 'Admin\TipController@updatefailedTip'); //更新
    Route::post('deleteSuccessTip', 'Admin\TipController@deleteSuccessTip'); //删除
    Route::get('xlkTipLabel', 'Admin\TipController@xlkTipLabel'); //从下拉框中获取标签
});//--lyt


/**
 * 个性化
 */
Route::prefix('gxh')->group(function () {
    Route::post('insertBackground', 'UserInfo\UserInfoController@insertBackground'); //添加背景
    Route::post('showGxh', 'UserInfo\UserInfoController@showGxh'); //添加背景
});//--lyt



Route::prefix('dynamic')->group(function () {
    Route::post('publish', 'Dynamic\DynamicController@publish');//将用户发表的动态信息储存在动态表中
    Route::post('comment', 'Dynamic\DynamicController@comment');//将用户发表的评论信息储存在评论表中
    Route::get('whole_type', 'Dynamic\DynamicController@wholeType');//用户主页展示全部的动态
    Route::get('mine_type', 'Dynamic\DynamicController@mineType');//用户查看自己全部的动态
    Route::post('report', 'Dynamic\DynamicController@report');//用户举报动态进行信息填写
    Route::post('collection', 'Dynamic\DynamicController@collection');//收藏动态
    Route::post('cancel_collection', 'Dynamic\DynamicController@cancelCollection');//取消收藏的动态
    Route::post('delete', 'Dynamic\DynamicController@delete');//用户删除自己的动态
    Route::get('mine_collection', 'Dynamic\DynamicController@mineCollection');//点击我的收藏，查看用户收藏的动态
    Route::get('collection_details', 'Dynamic\DynamicController@collectionDetails');//查看用户收藏动态中的动态详情
    Route::post('fabulous', 'Dynamic\DynamicController@fabulous');//对动态进行点赞或取消点赞
    Route::post('delete_comment', 'Dynamic\DynamicController@deleteComment');//用户删除评论
});//--zqz


Route::middleware('refreshToken')->prefix('admin')->group(function () {
    Route::get('user_administration', 'Admin\AdminstrationController@userAdministration');//管理员管理用户
    Route::post('disable', 'Admin\AdminstrationController@disable');//管理员操作用户账号的禁用状态
    Route::get('dynamic_administration', 'Admin\AdminstrationController@dynamicAdministration');//管理员管理查看用户动态
    Route::post('delete_dynamic', 'Admin\AdminstrationController@deleteDynamic');//管理员删除动态

});//--zqz


