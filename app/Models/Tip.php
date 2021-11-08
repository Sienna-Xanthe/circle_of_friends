<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $table = "tip";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    /**
     * 用户举报动态进行信息填写
     * @author zqz
     * @param $user_id
     * @param $tlabel_id
     * @param $id
     * @param $tip_reason
     * @return false
     */
    public static function establishphoto3($user_id,$tlabel_id,$id,$tip_reason)
    {
//        将获取举报人id  该动态id 举报类型 举报理由存入举报表中(tip)
        try {

                $res=Tip::create([
                    'user_id'           => $user_id,
                    'tlabel_id'         => $tlabel_id,
                    'dynamics_id'       => $id,
                    'tip_reason'        => $tip_reason,
                ]);


            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);
            return false;
        }
    }
}
