<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

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

//连接用户表，获取基本信息
    public function user(){
        return $this->hasMany(User::class,'user_id','user_id');
    }
//连接图片表，获取用户上传图片或视频的url
    public function url(){
        return $this->hasMany(Url::class,'dynamics_id','id');
    }
    //连接评论表，获取动态评论
    public function comment(){
        return $this->hasMany(Comment::class,'dynamics_id','id')->with('commentator');
    }
    //连接点赞表，获取点赞人数
    public function like(){
        return $this->hasMany(Likes::class,'user_id','user_id');
    }
    //连接收藏表，获取收藏的用户
    public function collection(){
        return $this->hasMany(Collection::class,'user_id','user_id');
    }




    /**
     * 将用户发表的动态信息储存起来
     * @param $user_id
     * @param $dynamics_content
     * @param $dlabel_id
     * @param $url
     * @return false|int
     */
    public static function establishphoto($user_id,$dynamics_content,$dlabel_id,$url)
    {
        try {
            if ($dynamics_content==null && $url==null ){
                //如果内容与图片都为空 返回2
                return 2;
            }else{
                //将id、标签、内容储存在动态表中
                $res['a']=Dynamics::create(
                    [
                        'user_id'                => $user_id,
                        'dynamics_content'       => $dynamics_content,
                        'dlabel_id'              => $dlabel_id,

                    ]
                );
                //拿到动态表当前的id
                $a=self::select('id')->where('user_id',$user_id)->get();
                $b=$a[count($a)-1]->id;
                //将图片储存在图片表中
                for ($i=0;$i<count($url);$i++){
                    $res[$i]=Url::create(
                        [
                            'dynamics_id'           => $b,
                            'url_name'              => $url[$i],
                        ]
                    );
                }

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
     * 主页展示全部的动态
     * @author zqz
     * @param $user_id
     * @param $dlabel_id
     * @param $date
     * @return array|false
     */
    public static function establishphoto2($user_id,$dlabel_id,$date)
    {
        try {
            //查出该用户黑名单的user_id
            $a=Blacklist::where('user_id',$user_id)->pluck('blacklist_id');
            //如果不传参，则查询所有的动态
            if ($dlabel_id==null && $date==null){
                //查询该用户不包括黑名单的动态id
                $b=self::whereNotIn('user_id',$a)->pluck('id');
                foreach ($b as $value){
                    //获取该动态的评论数量
                    $res['b'][]=Comment::select('id')->where('dynamics_id',$value)->count();
                    //获取该动态的点赞数量
                    $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
                    //判断该条动态是否点赞
                    $res['d'][]=Likes::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                    //判断该条动态是否收藏
                    $res['e'][]=Collection::where('user_id',$user_id)->where('dynamics_id',$value)->exists();

                }
                $res['a'] = self::with('user','url','comment')
                    ->whereNotIn('user_id',$a)->get();


                //查询符合要求的动态id
//                $a=self::pluck('id');
//                foreach ($a as $value){
//
//                    //获取该动态的点赞数量
//                    $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
//                }
                //根据动态标签查看动态
            }elseif ($dlabel_id!=null && $date==null){

                //查询该用户不包括黑名单的动态id
                $b=self::whereNotIn('user_id',$a)->where('dlabel_id',$dlabel_id)->pluck('id');
                foreach ($b as $value){
                    //获取该动态的评论数量
                    $res['b'][]=Comment::select('id')->where('dynamics_id',$value)->count();
                    //获取该动态的点赞数量
                    $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
                    //判断该条动态是否点赞
                    $res['d'][]=Likes::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                    //判断该条动态是否收藏
                    $res['e'][]=Collection::where('user_id',$user_id)->where('dynamics_id',$value)->exists();

                }
                $res['a'] = self::with('user','url','comment')
                    ->whereNotIn('user_id',$a)->where('dlabel_id',$dlabel_id)->get();

//                //查询符合要求的动态id
//                $a=self::where('dlabel_id',$dlabel_id)->pluck('id');
//                foreach ($a as $value){
//                    //获取该动态的头像、昵称、发表时间、签名、动态内容
//                    $res['a'][]=self::join('user','user.user_id','=','dynamics.user_id')
//                        ->select('dynamics.id','user_image','user_nickname','user_sign','dynamics.created_at','dlabel_id','dynamics_content')
//                        ->where('dynamics.id',$value)
//                        ->get();
//                    //获取该动态上传的图片或视频url
//                    $res['b'][]=self::join('url','url.dynamics_id','=','dynamics.id')
//                        ->select('url_name')->where('dynamics.id',$value)->get();
//                    //获取该动态的评论
//                    $res['c'][]=Comment::select('comment_content')->where('dynamics_id',$value)->get();
//                    //获取该动态的评论数量
//                    $res['d'][]=Comment::select('id')->where('dynamics_id',$value)->count();
//                    //获取该动态的点赞数量
//                    $res['e'][]=Likes::select('id')->where('dynamics_id',$value)->count();
//                }
                //根据日期查询所有的动态
            }elseif ($dlabel_id == null && $date != null){
                //查询该用户不包括黑名单的动态id
                $b=self::whereNotIn('user_id',$a)->where('created_at','like','%'.$date.'%')->pluck('id');
                foreach ($b as $value){
                    //获取该动态的评论数量
                    $res['b'][]=Comment::select('id')->where('dynamics_id',$value)->count();
                    //获取该动态的点赞数量
                    $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
                    //判断该条动态是否点赞
                    $res['d'][]=Likes::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                    //判断该条动态是否收藏
                    $res['e'][]=Collection::where('user_id',$user_id)->where('dynamics_id',$value)->exists();

                }

                $res['a'] = self::with('user','url','comment')
                    ->whereNotIn('user_id',$a)->whereIn('id',$b)->get();
//                foreach ($a as $value){
//                    //获取该动态的头像、昵称、发表时间、签名、动态内容
//                    $res['a'][]=self::join('user','user.user_id','=','dynamics.user_id')
//                        ->select('dynamics.id','user_image','user_nickname','user_sign','dynamics.created_at','dlabel_id','dynamics_content')
//                        ->where('dynamics.id',$value)
//                        ->get();
//                    //获取该动态上传的图片或视频url
//                    $res['b'][]=self::join('url','url.dynamics_id','=','dynamics.id')
//                        ->select('url_name')->where('dynamics.id',$value)->get();
//                    //获取该动态的评论
//                    $res['c'][]=Comment::select('comment_content')->where('dynamics_id',$value)->get();
//                    //获取该动态的评论数量
//                    $res['d'][]=Comment::select('id')->where('dynamics_id',$value)->count();
//                    //获取该动态的点赞数量
//                    $res['e'][]=Likes::select('id')->where('dynamics_id',$value)->count();
//                }
                //根据动态标签与日期查询动态
            }elseif ($dlabel_id != null && $date != null){

                //查询该用户不包括黑名单的动态id
                $b=self::whereNotIn('user_id',$a)->where('created_at','like','%'.$date.'%')
                    ->where('dlabel_id',$dlabel_id)->pluck('id');

                foreach ($b as $value){
                    //获取该动态的评论数量
                    $res['b'][]=Comment::select('id')->where('dynamics_id',$value)->count();
                    //获取该动态的点赞数量
                    $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
                    //判断该条动态是否点赞
                    $res['d'][]=Likes::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                    //判断该条动态是否收藏
                    $res['e'][]=Collection::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                }
                $res['a'] = self::with('user','url','comment')
                    ->whereNotIn('user_id',$a)->whereIn('id',$b)->get();
//                foreach ($a as $value){
//                    //获取该动态的头像、昵称、发表时间、签名、动态内容
//                    $res['a'][]=self::join('user','user.user_id','=','dynamics.user_id')
//                        ->select('dynamics.id','user_image','user_nickname','user_sign','dynamics.created_at','dlabel_id','dynamics_content')
//                        ->where('dynamics.id',$value)
//                        ->get();
//                    //获取该动态上传的图片或视频url
//                    $res['b'][]=self::join('url','url.dynamics_id','=','dynamics.id')
//                        ->select('url_name')->where('dynamics.id',$value)->get();
//                    //获取该动态的评论
//                    $res['c'][]=Comment::select('comment_content')->where('dynamics_id',$value)->get();
//                    //获取该动态的评论数量
//                    $res['d'][]=Comment::select('id')->where('dynamics_id',$value)->count();
//                    //获取该动态的点赞数量
//                    $res['e'][]=Likes::select('id')->where('dynamics_id',$value)->count();
//                }
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
     * 用户删除自己的动态
     * @author zqz
     * @param $id
     * @return false
     */
    public static function establishphoto6($id)
    {
        try {
//            //删除该动态
//            $res = Dynamics::where('id',$id)->delete();
            //删除动态中相关的图片与视频
            $a=Dynamics::join('url','url.dynamics_id','=','dynamics.id')
                ->where('url.dynamics_id',$id)->pluck('url.id');

            if (count($a)>0){
                foreach ($a as $value){
                    Url::where('id',$value)->delete();
                }
            }
            //删除动态中相关的点赞信息
            $b=Dynamics::join('likes','likes.dynamics_id','=','dynamics.id')
                ->where('likes.dynamics_id',$id)->pluck('likes.id');
            if (count($b)>0){
                foreach ($b as $value){
                    Likes::where('id',$value)->delete();
                }
            }

            //删除动态收藏
            $c=Dynamics::join('collection','collection.dynamics_id','=','dynamics.id')
                ->where('collection.dynamics_id',$id)->pluck('collection.id');
            if (count($c)>0){
                foreach ($c as $value){
                    Collection::where('id',$value)->delete();
                }
            }

            //删除动态下的相应评论
            $d=Dynamics::join('comment','comment.dynamics_id','=','dynamics.id')
                ->where('comment.dynamics_id',$id)->pluck('comment.id');
            if (count($d)>0){
                foreach ($d as $value){
                    Comment::where('id',$value)->delete();
                }
            }

            //删除动态的举报信息
            $e=Dynamics::join('tip','tip.dynamics_id','=','dynamics.id')
                ->where('tip.dynamics_id',$id)->pluck('tip.id');
            if (count($e)>0){
                foreach ($e as $value){
                    Tip::where('id',$value)->delete();
                }
            }
            //删除该动态
            $res = Dynamics::where('id',$id)->delete();

            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 查看用户收藏动态中的动态详情
     * @author zqz
     * @param $id
     * @return array|false
     */
    public static function establishphoto8($id,$user_id)
    {
        try {

                    //获取该动态的评论数量
                    $res['b']=Comment::select('id')->where('dynamics_id',$id)->count();
                    //获取该动态的点赞数量
                    $res['c']=Likes::select('id')->where('dynamics_id',$id)->count();
                    //判断该条动态是否点赞
                    $res['d']=Likes::where('user_id',$user_id)->where('dynamics_id',$id)->exists();
                    //判断该条动态是否收藏
                    $res['e']=Collection::where('user_id',$user_id)->where('dynamics_id',$id)->exists();
                    //查询该条动态的基本信息
                    $res['a'] = self::with('user','url','comment')
                      ->where('id',$id)->get();

//          //获取该动态的头像、昵称、发表时间、签名、动态内容
//            $res['a']=self::join('user','user.user_id','=','dynamics.user_id')
//                ->select('user_image','user_nickname','user_sign','dynamics.created_at','dlabel_id','dynamics_content')
//                ->where('dynamics.id',$id)
//                ->get();
//            //获取该动态上传的图片或视频url
//            $res['b']=self::join('url','url.dynamics_id','=','dynamics.id')
//                ->select('url_name')->where('dynamics.id',$id)->get();
//            //获取该动态的评论
//            $res['c']=Comment::select('comment_content')->where('dynamics_id',$id)->get();
//            //获取该动态的评论数量
//            $res['d']=Comment::select('id')->where('dynamics_id',$id)->count();
//            //获取该动态的点赞数量
//            $res['e']=Likes::select('id')->where('dynamics_id',$id)->count();
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 用户查看自己的全部动态
     * @param $user_id
     * @param $dlabel_id
     * @param $date
     * @return array|false
     */
    public static function establishphoto9($user_id,$dlabel_id,$date)
    {
        try {

                //如果不传参，则查询所有的动态
                if ($dlabel_id==null && $date==null){

                    //查询该用户发的动态id
                    $b=self::where('user_id',$user_id)->pluck('id');
                    foreach ($b as $value){
                        //获取该动态的评论数量
                        $res['b'][]=Comment::select('id')->where('dynamics_id',$value)->count();
                        //获取该动态的点赞数量
                        $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
                        //判断该条动态是否点赞
                        $res['d'][]=Likes::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                        //判断该条动态是否收藏
                        $res['e'][]=Collection::where('user_id',$user_id)->where('dynamics_id',$value)->exists();

                    }
                    $res['a'] = self::with('user','url','comment')
                        ->whereIn('id',$b)->get();


//                //查询符合要求的动态id
//                $a=self::where('user_id',$user_id)->pluck('id');
//                foreach ($a as $value){
//                    //获取该动态的头像、昵称、发表时间、签名、动态内容
//                    $res['a'][]=self::join('user','user.user_id','=','dynamics.user_id')
//                        ->select('dynamics.id','user_image','user_nickname','user_sign','dynamics.created_at','dlabel_id','dynamics_content')
//                        ->where('dynamics.id',$value)
//                        ->get();
//                    //获取该动态上传的图片或视频url
//                    $res['b'][]=self::join('url','url.dynamics_id','=','dynamics.id')
//                        ->select('url_name')->where('dynamics.id',$value)->get();
//                    //获取该动态的评论
//                    $res['c'][]=Comment::select('comment_content')->where('dynamics_id',$value)->get();
//                    //获取该动态的评论数量
//                    $res['d'][]=Comment::select('id')->where('dynamics_id',$value)->count();
//                    //获取该动态的点赞数量
//                    $res['e'][]=Likes::select('id')->where('dynamics_id',$value)->count();

                //根据动态标签查看动态
            }elseif ($dlabel_id!=null && $date==null){
                //查询该用户发的动态id
                    $b=self::where('user_id',$user_id)->where('dlabel_id',$dlabel_id)->pluck('id');
                    foreach ($b as $value){
                        //获取该动态的评论数量
                        $res['b'][]=Comment::select('id')->where('dynamics_id',$value)->count();
                        //获取该动态的点赞数量
                        $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
                        //判断该条动态是否点赞
                        $res['d'][]=Likes::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                        //判断该条动态是否收藏
                        $res['e'][]=Collection::where('user_id',$user_id)->where('dynamics_id',$value)->exists();

                    }
                    $res['a'] = self::with('user','url','comment')
                        ->whereIn('id',$b)->get();
//                //查询符合要求的动态id
//                $a=self::where('dlabel_id',$dlabel_id)->where('user_id',$user_id)->pluck('id');
//                foreach ($a as $value){
//                    //获取该动态的头像、昵称、发表时间、签名、动态内容
//                    $res['a'][]=self::join('user','user.user_id','=','dynamics.user_id')
//                        ->select('dynamics.id','user_image','user_nickname','user_sign','dynamics.created_at','dlabel_id','dynamics_content')
//                        ->where('dynamics.id',$value)
//                        ->get();
//                    //获取该动态上传的图片或视频url
//                    $res['b'][]=self::join('url','url.dynamics_id','=','dynamics.id')
//                        ->select('url_name')->where('dynamics.id',$value)->get();
//                    //获取该动态的评论
//                    $res['c'][]=Comment::select('comment_content')->where('dynamics_id',$value)->get();
//                    //获取该动态的评论数量
//                    $res['d'][]=Comment::select('id')->where('dynamics_id',$value)->count();
//                    //获取该动态的点赞数量
//                    $res['e'][]=Likes::select('id')->where('dynamics_id',$value)->count();
//                }
                //根据日期查询所有的动态
            }elseif ($dlabel_id == null && $date != null){

                //查询该用户发的动态id
                    $b=self::where('user_id',$user_id)->where('created_at','like','%'.$dlabel_id.'%')->pluck('id');
                    foreach ($b as $value){
                        //获取该动态的评论数量
                        $res['b'][]=Comment::select('id')->where('dynamics_id',$value)->count();
                        //获取该动态的点赞数量
                        $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
                        //判断该条动态是否点赞
                        $res['d'][]=Likes::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                        //判断该条动态是否收藏
                        $res['e'][]=Collection::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                    }
                    $res['a'] = self::with('user','url','comment')
                        ->whereIn('id',$b)->get();
                //                foreach ($a as $value){
//                    //获取该动态的头像、昵称、发表时间、签名、动态内容
//                    $res['a'][]=self::join('user','user.user_id','=','dynamics.user_id')
//                        ->select('dynamics.id','user_image','user_nickname','user_sign','dynamics.created_at','dlabel_id','dynamics_content')
//                        ->where('dynamics.id',$value)
//                        ->get();
//                    //获取该动态上传的图片或视频url
//                    $res['b'][]=self::join('url','url.dynamics_id','=','dynamics.id')
//                        ->select('url_name')->where('dynamics.id',$value)->get();
//                    //获取该动态的评论
//                    $res['c'][]=Comment::select('comment_content')->where('dynamics_id',$value)->get();
//                    //获取该动态的评论数量
//                    $res['d'][]=Comment::select('id')->where('dynamics_id',$value)->count();
//                    //获取该动态的点赞数量
//                    $res['e'][]=Likes::select('id')->where('dynamics_id',$value)->count();
//                }
                //根据动态标签与日期查询动态
            }elseif ($dlabel_id != null && $date != null){
                    //查询该用户发的动态id
                    $b=self::where('user_id',$user_id)->where('dlabel_id',$dlabel_id)
                        ->where('created_at','like','%'.$dlabel_id.'%')->pluck('id');
                    foreach ($b as $value){
                        //获取该动态的评论数量
                        $res['b'][]=Comment::select('id')->where('dynamics_id',$value)->count();
                        //获取该动态的点赞数量
                        $res['c'][]=Likes::select('id')->where('dynamics_id',$value)->count();
                        //判断该条动态是否点赞
                        $res['d'][]=Likes::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                        //判断该条动态是否收藏
                        $res['e'][]=Collection::where('user_id',$user_id)->where('dynamics_id',$value)->exists();
                    }
                    $res['a'] = self::with('user','url','comment')
                        ->whereIn('id',$b)->get();

                //                foreach ($a as $value){
//                    //获取该动态的头像、昵称、发表时间、签名、动态内容
//                    $res['a'][]=self::join('user','user.user_id','=','dynamics.user_id')
//                        ->select('dynamics.id','user_image','user_nickname','user_sign','dynamics.created_at','dlabel_id','dynamics_content')
//                        ->where('dynamics.id',$value)
//                        ->get();
//                    //获取该动态上传的图片或视频url
//                    $res['b'][]=self::join('url','url.dynamics_id','=','dynamics.id')
//                        ->select('url_name')->where('dynamics.id',$value)->get();
//                    //获取该动态的评论
//                    $res['c'][]=Comment::select('comment_content')->where('dynamics_id',$value)->get();
//                    //获取该动态的评论数量
//                    $res['d'][]=Comment::select('id')->where('dynamics_id',$value)->count();
//                    //获取该动态的点赞数量
//                    $res['e'][]=Likes::select('id')->where('dynamics_id',$value)->count();
//                }
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
