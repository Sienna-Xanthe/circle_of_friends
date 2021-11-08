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


Route::prefix('admin')->group(function () {
    Route::get('user_administration', 'Admin\AdminstrationController@userAdministration');//管理员管理用户
    Route::post('disable', 'Admin\AdminstrationController@disable');//管理员操作用户账号的禁用状态
    Route::get('dynamic_administration', 'Admin\AdminstrationController@dynamicAdministration');//管理员管理查看用户动态
    Route::post('delete_dynamic', 'Admin\AdminstrationController@deleteDynamic');//管理员删除动态

});//--zqz

