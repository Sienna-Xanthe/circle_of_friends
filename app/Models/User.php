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

    //连接动态表，与该动态的用户信息进行绑定
    public function dynamics(){
        return $this->belongsTo(Dynamics::class,'user_id');
    }
    public function comment(){
        return $this->belongsTo(Comment::class,'user_id');

    }
    public function personality(){
        return $this->belongsTo(Personality::class,'user_id');

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
     * pxy
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
     * pxy
     */
    public static function informationforfirst($request)
    {
        try {
            $res1 = User::create(
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
            $res2 = Personality::create(
                [
                    'user_id'       => $request['user_id'],
                    'background_id'       => 4,
                    'flower_id'       => 2,
                ]
            );
            return $res1 && $res2 ?
                $res1 :
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
                    'user_qq',
                    'user_sign'
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
     * 管理员管理用户
     * @author zqz
     * @param $user_name
     * @return false
     */
    public static function establishphoto3($user_name)
    {
        try {
            //如果不传姓名，查询全部的用户
            if ($user_name==null){
                $res=self::select('user_id','user_name','user_nickname','user_phone','user_state1')->get();
            }else{
                //姓名进行选择查询
                $res=self::select('user_id','user_name','user_nickname','user_phone','user_state1')
                    ->where('user_name','like','%'.$user_name.'%')->get();
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
     * 管理员操作用户账号的禁用状态
     * @author zqz
     * @param $user_id
     * @param $user_state1
     * @return false
     */
    public static function establishphoto4($user_id,$user_state1)
    {
        try {
            $res=self::where('user_id',$user_id)->update(['user_state1'=>$user_state1]);

            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询错误', [$e->getMessage()]);

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
     * 管理员管理查看用户动态
     * @author zqz
     * @param $dlabel_id
     * @param $user_nickname
     * @return array|false
     */
    public static function establishphoto5($dlabel_id,$user_nickname)
    {
        try {
            //如果标签与昵称都为空 则查询全部
            if ($dlabel_id == null && $user_nickname == null){
                //连接用户信息、图片与动态三张表
                $res = Dynamics::with('user','url')->get();
            } elseif ($dlabel_id != null && $user_nickname == null){
                //连接用户信息、图片与动态三张表，根据符合动态标签查询动态
                $res = Dynamics::with('user','url')->where('dlabel_id',$dlabel_id)->get();
            }elseif ($dlabel_id == null && $user_nickname != null){
                $a=self::where('user_nickname','like','%'.$user_nickname.'%')->pluck('user_id');
                //连接用户信息、图片与动态三张表，根据符合用户昵称查询动态
                $res = Dynamics::with('user','url')
                    ->whereIn('user_id',$a)->get();
            }elseif ($dlabel_id != null && $user_nickname != null){
                $a=self::where('user_nickname','like','%'.$user_nickname.'%')->pluck('user_id');
                //连接用户信息、图片与动态三张表，根据符合用户昵称和动态标签查询动态
                $res = Dynamics::with('user','url')->where('dlabel_id',$dlabel_id)
                    ->whereIn('user_id',$a)->get();
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

/**
     * 管理员删除动态
     * @author zqz
     * @param $id
     * @return false
     */
    public static function establishphoto6($id)
    {
        try {
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
     * 登录时获取用户基本信息、评论的新消息数、个性化设置
     * @param $user_id
     * @return array|false
     */
    public static function getinfo($user_id,$avatar){
        try {

            $updateimage = self::where('user_id',$user_id)
                ->update([
                    'user_image' => $avatar
                ]);

            $res['user_nickname'] = self::where('user_id',$user_id)
                ->select([
                    'user_nickname'
                ])
                ->value('user_nickname');
            $res['user_sign'] = self::where('user_id',$user_id)
                ->select([
                    'user_sign'
                ])
                ->value('user_sign');
            $res['background_id'] = Personality::where('user_id',$user_id)
                ->select([
                    'background_id'
                ])
                ->value('background_id');
            $res['flower_id'] = Personality::where('user_id',$user_id)
                ->select([
                    'flower_id'
                ])
                ->value('flower_id');

            $dy_list = Dynamics::where('user_id',$user_id)->pluck('id');

            $res['news'] = Comment::select('*')
            ->where('comment_state',1)
                ->whereIn('dynamics_id',$dy_list)
                ->count();
            ;
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('查询', [$e->getMessage()]);
            return false;
        }
    }


}
