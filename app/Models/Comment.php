<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];



    //连接动态表，与该动态的评论绑定
    public function dynamics(){
        return $this->belongsTo(Dynamics::class,'dynamics_id');
    }
    //连接用户表，获取评论动态的人
    public function commentator(){
        return $this->hasOne(User::class,'user_id','user_id');
    }


    /**
     * 将用户发表的评论信息储存在评论表中
     * @param $user_id
     * @param $comment_content
     * @param $dynamics_id
     * @param $url
     * @return array|false|int
     */
    public static function establishphoto1($user_id,$comment_content,$dynamics_id)
    {
        try {

                //将id、标签、内容储存在动态表中
                $res=Comment::create(
                    [
                        'user_id'                => $user_id,
                        'comment_content'        => $comment_content,
                        'dynamics_id'            => $dynamics_id,
                        'comment_state'          => '1',
                    ]
                );


            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 用户删除评论
     * @author zqz
     * @param $id
     * @return false
     */
    public static function establishphoto11($id)
    {
        try {
            //查询想要删除的评论进行删除
            $res=self::where('id',$id)->delete();
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);
            return false;
        }
    }

}
