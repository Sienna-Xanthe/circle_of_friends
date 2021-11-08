<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dynamics extends Model
{
    protected $table = "dynamics";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    public function user_comment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Comment::class,'id');
    }

    public function getpublisher()
    {
        return $this->hasOne(User::class,'user_id','user_id');
    }

    public function getUrl(){
        return $this->hasMany(Url::class,'dynamics_id','id');
    }

    public function getTip(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tip::class,'id');
    }

//    public function dy_comment(){
//        return $this->hasMany(Comment::class,'dynamics_id','id');
//    }

//    /**
//     * 消息的展示
//     * @return false|mixed
//     */
//    public static function lyt_information($userId)
//    {
//        try {
//            $res = self::join('comment', 'comment.dynamics_id', 'dynamics.id')
//                ->join('user', 'user.user_id', 'dynamics.user_id')
//                ->join('url', 'url.dynamics_id', 'dynamics.id')
//                ->where('dynamics.user_id', '=', $userId)
//                ->select([
//                    'comment.comment_content as pcomment',
//                    'comment.user_id',
//                    'comment.created_at',
//                    'url_name',
//                    'user.user_nickname',
//                    'dynamics.dynamics_content as dcomment'
//                ])
//                ->get();
//
//
//            return $res ?
//                $res :
//                false;
//        } catch (\Exception $e) {
//            logError('搜索错误', [$e->getMessage()]);
//            return false;
//        }
//    }

//    /**
//     * 通过传上去的评论者的userId在进行查询
//     * @return false|mixed
//     */
//    public static function lyt_commentUserId($userId)
//    {
//        try {
//            $res = self::join('comment', 'comment.dynamics_id', 'dynamics.id')
//
//                ->where('dynamics.user_id', '=', $userId)
//                ->select([
//                    'comment.user_id',
//                ])
//                ->pluck('comment.user_id');
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
