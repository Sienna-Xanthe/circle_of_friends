<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $table = "likes";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];


    /**
     * @author zqz
     * @param $user_id
     * @param $dynamics_id
     * @return false
     */
    public static function establishphoto10($user_id,$dynamics_id)
    {
        try {
            //将用户id  动态id存入动态点赞表中 likes
            //判断该动态是否点赞
            $a=self::where('user_id',$user_id)->where('dynamics_id',$dynamics_id)->count();
            if ($a>0){
                //如果已点赞就取消点赞
                $res=self::where('user_id',$user_id)->where('dynamics_id',$dynamics_id)->select('id')->delete();
            }else{
                //如果未点赞就进行点赞
                $res=Likes::create([
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

}
