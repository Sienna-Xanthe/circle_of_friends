<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = "user";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    /**
     * 查询是否为第一次登录
     * @param $unionid
     * @return false
     */
    public static function logincheck($unionid){
        try{
            $count = User::select('user_id')
                ->where('user_id',$unionid)
                ->count();
            return $count;
        }catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }
    /**
     * 查询账户是否禁用
     * @param $unionid
     * @return false
     */
    public static function is_disable($unionid){
        try{
            $user_state1 = User::select('user_id','user_state1')
                ->where('user_id',$unionid)
                ->value('user_state1');
            return $user_state1;
        }catch (\Exception $e) {
            logError("账号查询失败！", [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 第一次登录填写个人资料
     * @param $request
     */
    public static function informationforfirst($request){
        try {
            $res = User::create(
                [
                    'user_id' => $request['user_id'],
                    'user_image' => $request['user_image'],
                    'user_nickname' => $request['user_nickname'],
                    'user_name' => $request['user_name'],
                    'user_sex' => $request['user_sex'],
                    'user_birthday' => $request['user_birthday'],
                    'user_phone' => $request['user_phone'],
                    'user_qq' => $request['user_qq'],
                    'user_state1' => 1
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

}
