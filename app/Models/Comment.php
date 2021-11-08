<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = "comment";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];


    public function getuser(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function getdynamics()
    {
        return $this->hasOne(Dynamics::class, 'id', 'dynamics_id')->with('getpublisher', 'getUrl');
    }

    public static function lyt_commentUserId($userId)
    {
        $pd = Dynamics::where('dynamics.user_id', '=', $userId)
            ->pluck('id');

//        if ($pd == $userId) {
            $res = self::with('getuser','getdynamics')
                ->whereIn('dynamics_id',$pd)
                ->get();
//        }
            return $res;

    }

//    public function lyt_changeCommentState($userId)
//    {
//        try {
//
//            $res = self::join('dynamics', 'dynamics.id', 'dynamics_id')
//                ->where('dynamics.user_id', '=', $userId)
//                ->update([
//                    'comment_state' => 1
//                ]);
//            return $res ?
//                $res :
//                false;
//        } catch (\Exception $e) {
//            logError('搜索错误', [$e->getMessage()]);
//            return false;
//        }
//    }


    /**
     * 消息的展示
     * @return false|mixed
     */
    public static function lyt_information($userId)
    {
        try {
            $res = self::join('dynamics', 'dynamics.id', 'dynamics_id')
                ->where('dynamics.user_id', '=', $userId)
                ->update([
                    'comment_state' => 0
                ]);
          return $res ?
                $res :
                false;
        } catch (\Exception $e) {
           logError('搜索错误', [$e->getMessage()]);
           return false;
        }
    }

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


//    /**
//     * 通过传上去的评论者的userId在进行查询
//     * @return false|mixed
//     */
//    public static function lyt_commentUserId($userId)
//    {
//
//        try {
//            $res = self::join('user', 'user.user_id', 'comment.user_id')
//                ->join('dynamics', 'dynamics.id', 'dynamics_id')
//                ->where('dynamics.user_id', '=', $userId)
//                ->select([
//                    'user.user_id as cid',
//                    'user.user_image as cheader',
//                    'user.user_nickname as cnickname',
//                    'user.user_sign as csign',
//                    'comment.comment_content as ccomment',
//                    'comment.created_at as ctime',
//                    'dynamics.dynamics_content as dcomment',
//                    'dynamics.user_id as duid',
//
//
//                ])
//                ->get();
////            foreach ($res as $cc) {
////                $cs                = $cc->comment()->value('user_id ');
////                $cc->user_id = $cs;
////            }
//
//            return $res ?
//                $res :
//                false;
//        } catch (\Exception $e) {
//            logError('搜索错误', [$e->getMessage()]);
//            return false;
//        }
//    }


}
