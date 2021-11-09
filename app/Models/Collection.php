<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = "collection";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    /**
     * 收藏动态
     * @author zqz
     * @param $user_id
     * @param $dynamics_id
     * @return false
     */
    public static function establishphoto4($user_id,$dynamics_id)
    {
        try {
            //将用户id  动态id存入动态收藏表中 collection
            //判断该动态是否被用户收藏
            $a=self::where('user_id',$user_id)->where('dynamics_id',$dynamics_id)->count();
            if ($a>0){
                //如果存在就取消收藏
                $res=self::where('user_id',$user_id)->where('dynamics_id',$dynamics_id)->select('id')->delete();
            }else{
                //如果不存在就收藏
                $res=Collection::create([
                    'user_id'           => $user_id,
                    'dynamics_id'       => $dynamics_id,
                ]);
            }
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 取消收藏的动态
     * @author zqz
     * @param $user_id
     * @param $dynamics_id
     * @return false
     */
    public static function establishphoto5($user_id,$dynamics_id)
    {
        try {
            //将用户id  动态id从动态收藏表中collection 删除
         $res=self::where('user_id',$user_id)->where('dynamics_id',$dynamics_id)->delete();
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 查看用户自己的收藏动态
     * @author zqz
     * @param $user_id
     * @return array|false
     */
    public static function establishphoto7($user_id)
    {
        try {
            //查找自己收藏中动态id
            $a=Collection::where('user_id',$user_id)->pluck('dynamics_id');

                $res  = Dynamics::with('user')
                    ->whereIn('id',$a)->get();


            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);
            return false;
        }
    }


}
