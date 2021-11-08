<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    protected $table = "blacklist";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getuser(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }


    public static function test($userId){


        $res = self::with('getuser')
            ->where('user_id',$userId)
            ->get();
        return $res;
    }

//    /**
//     * 查询所有白名单用户
//     */
//    public static function lyt_selectAllWUser($userId)
//    {
//        try {
////            $a=self::where('user_id',$userId)->pluck('blacklist_id');
//            $a=Blacklist::where('user_id',$userId)->pluck('blacklist_id');
//
//
//                $res = self::join('user', 'user.user_id', 'blacklist.user_id')
//                    ->where('blacklist.user_id','=',$userId)
//                    ->whereNOtIn('blacklist.blacklist_id',$a)
//                    ->get();
//
//            return $res ?
//                $res :
//                false;
//        } catch (\Exception $e) {
//            logError('搜索错误', [$e->getMessage()]);
//            return false;
//        }
//    }

    /**
     * 查询所有黑名单用户
     */
    public static function lyt_selectAllBlackUser($userId)
    {
        try {
            $res = self::join('user', 'user.user_id', 'blacklist.user_id')
                ->where('blacklist.user_id', '=', $userId)
                ->select([
                    'blacklist_id'
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
     * 添加用户到黑名单
     * @return false|mixed
     */
    public static function lyt_addBlackList($userIds, $userId)
    {
        try {

            $pd = self::where('blacklist.user_id','=',$userId)
                ->where('blacklist.blacklist_id','=', $userIds)
                ->exists();

            if (!$pd){
                $res = self::join('user', 'user.user_id', 'blacklist.user_id')
//                ->where('blacklist.user_id', '<>', $userId)
                   ->insert([
                        'blacklist.user_id' => $userId,
                        'blacklist_id'      => $userIds
                    ]);
            }
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 移除黑名单用户
     */
    public static function lyt_deleteBlackList($userId, $userIds)
    {
        try {
            $res = self::join('user', 'user.user_id', 'blacklist.user_id')
                ->where('blacklist.user_id', '=', $userId)
                ->where('blacklist.blacklist_id', '=', $userIds)
                ->delete();
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }


}
