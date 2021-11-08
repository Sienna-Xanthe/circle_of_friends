<?php


namespace App\Http\Controllers\UserInfo;


use App\Http\Controllers\Controller;
use App\Http\Requests\UserInfo\addBlackListRequest;
use App\Http\Requests\UserInfo\addSignRequest;
use App\Http\Requests\UserInfo\deleteBlackListRequest;
use App\Http\Requests\UserInfo\insertBackgroundRequest;
use App\Http\Requests\UserInfo\showAllBlackUserRequest;
use App\Http\Requests\UserInfo\showAllWhiteUserRequest;
use App\Http\Requests\UserInfo\showAllWUserRequest;
use App\Http\Requests\UserInfo\showCommentInfoRequest;
use App\Http\Requests\UserInfo\showUserInfoRequest;
use App\Http\Requests\UserInfo\updateUserInfoRequest;
use App\Models\Blacklist;
use App\Models\Comment;
use App\Models\Personality;
use App\Models\User;

class UserInfoController extends Controller
{
    /**
     * 显示用户信息
     * @param  showUserInfoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showUserInfo(showUserInfoRequest $request)
    {
        $userId = $request['userId'];
        $res    = User::lyt_selectUserInfo($userId);
        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 添加个性签名
     * @param  addSignRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSign(addSignRequest $request)
    {
        $userId   = $request['userId'];
        $userSign = $request['userSign'];
        $res      = User::lyt_addSign($userId, $userSign);
        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 更新个人信息
     * @param  updateUserInfoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserInfo(updateUserInfoRequest $request)
    {
        $userId       = $request['userId'];
        $userNickname = $request['userNickname'];
        $userPhone    = $request['userPhone'];
        $userSex      = $request['userSex'];
        $userBirthday = $request['userBirthday'];
        $userQq       = $request['userQq'];

        $res = User::lyt_updateUserInfo($userId, $userNickname, $userPhone, $userSex, $userBirthday, $userQq);

        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 显示所有白名单用户
     * @param  showAllWhiteUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllWhiteUser(showAllWhiteUserRequest $request)
    {
        $userId   = $request['userId'];
        $nickName = $request['nickName'];

        if ($nickName == null) {
            $res = User::lyt_selectAllWhiteUser($userId);
        } else {
            $res = User::lyt_selectWhiteUserByNickName($userId, $nickName);
        }

        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 显示所有黑名单用户
     * @param  showAllBlackUserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllBlackUser(showAllBlackUserRequest $request)
    {
        $userId = $request['userId'];
//        $res   = Blacklist::lyt_selectAllBlackUser($userId);

        $res = Blacklist::test($userId);
        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 添加为黑名单
     * @param  addBlackListRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addBlackList(addBlackListRequest $request)
    {
        //userId是当前登陆者，userIds黑名单者
        $userId  = $request['userId'];
        $userIds = $request['userIds'];
        $res     = Blacklist::lyt_addBlackList($userIds, $userId);
        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 移除黑名单
     * @param  deleteBlackListRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBlackList(deleteBlackListRequest $request)
    {

        $userId  = $request['userId'];
        $userIds = $request['userIds'];
        $res     = Blacklist::lyt_deleteBlackList($userId, $userIds);
        return $res ?
            json_success('删除成功!', $res, 200) :
            json_fail('删除失败!', null, 100);
    }

    /**
     * 添加为背景
     * @return \Illuminate\Http\JsonResponse
     */
    public function insertBackground(insertBackgroundRequest $request)
    {
        $userId     = $request['userId'];
        $background = $request['background'];
        $flower     = $request['flower'];
        if ($background != null) {
            $res = Personality::lyt_insertBackground($userId, $background);
        } elseif ($flower != null) {
            $res = Personality::lyt_insertFlower($userId, $flower);
        }

        return $res ?
            json_success('添加成功!', $res, 200) :
            json_fail('添加失败!', null, 100);
    }


    /**
     * 显示评论我的动态的消息
     * @param  showCommentInfoRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showCommentInfo(showCommentInfoRequest $request)
    {

        $userId = $request['userId'];

        $res= Comment::lyt_commentUserId($userId);

        Comment::lyt_information($userId);
        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }


//    /**
//     * 显示所有白名单用户
//     * @param  showAllWUserRequest  $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function showAllWUser(showAllWUserRequest $request)
//    {
//        $userId = $request['userId'];
//        echo $userId;
////        $res    = Blacklist::lyt_selectAllWUser($userId);
//
//
//        return $res ?
//            json_success('查找成功!', $res, 200) :
//            json_fail('查找失败!', null, 100);
//    }

}
