<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "user";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    public function user_comment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Comment::class,'user_id');
    }
    public function getdynamics(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Dynamics::class,'user_id');
    }
    public function getTip(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tip::class,'user_id');
    }

    /**
     * 查询是否为第一次登录
     * @param $unionid
     * @return false
     */
    public static function logincheck($unionid)
    {
        try {
            $count = User::select('user_id')
                ->where('user_id', $unionid)
                ->count();
            return $count;
        } catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 查询账户是否禁用
     * @param $unionid
     * @return false
     */
    public static function is_disable($unionid)
    {
        try {
            $user_state1 = User::select('user_id', 'user_state1')
                ->where('user_id', $unionid)
                ->value('user_state1');
            return $user_state1;
        } catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 第一次登录填写个人资料
     * @param $request
     */
    public static function informationforfirst($request)
    {
        try {
            $res = User::create(
                [
                    'user_id'       => $request['user_id'],
                    'user_image'    => $request['user_image'],
                    'user_nickname' => $request['user_nickname'],
                    'user_name'     => $request['user_name'],
                    'user_sex'      => $request['user_sex'],
                    'user_birthday' => $request['user_birthday'],
                    'user_phone'    => $request['user_phone'],
                    'user_qq'       => $request['user_qq'],
                    'user_state1'   => 1
                ]
            );
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('存储个人信息失败！', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 查询用户信息
     */
    public static function lyt_selectUserInfo($userId)
    {
        try {
            $res = self::where('user_id', '=', $userId)
                ->select([
                    'user_id',
                    'user_image',
                    'user_nickname',
                    'user_phone',
                    'user_sex',
                    'user_birthday',
                    'user_qq'
                ])
                ->get();
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 添加个性签名
     */
    public static function lyt_addSign($UserId, $userSign)
    {
        try {
            $res = self::where('user_id', '=', $UserId)
                ->update([
                    'user_sign' => $userSign
                ]);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 更改用户信息
     */
    public static function lyt_updateUserInfo($userId, $userNickname, $userPhone, $userSex, $userBirthday, $userQq)
    {
        try {
            $res = self::where('user_id', '=', $userId)
                ->update([

                    'user_nickname' => $userNickname,
                    'user_phone'    => $userPhone,
                    'user_sex'      => $userSex,
                    'user_birthday' => $userBirthday,
                    'user_qq'       => $userQq

                ]);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 查询所有白名单用户
     */
    public static function lyt_selectAllWhiteUser($userId)
    {
        try {
            $a=Blacklist::where('user_id',$userId)->pluck('blacklist_id');

            $res = self::where('user.user_id','<>',$userId)
                ->whereNOtIn('user.user_id',$a)
                ->select([
                    'user.user_id',
                    'user.user_image',
                    'user.user_nickname'
                ])
                ->get();
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 根据名称显示白名单用户
     */
    public static function lyt_selectWhiteUserByNickName($userId, $nickName)
    {
        try {
            $a=Blacklist::where('user_id',$userId)->pluck('blacklist_id');

            $res = self::where('user.user_id', '<>', $userId)
                ->whereNOtIn('user.user_id',$a)
//                ->where('user_nickname', '=', '$nickName')
                ->Where('user_nickname', 'like', '%'.$nickName.'%')
                ->
                select([
                    'user_id',
                    'user_image',
                    'user_nickname',
                ])
                ->get();
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

}
