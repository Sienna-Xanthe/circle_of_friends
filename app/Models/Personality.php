<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personality extends Model
{
    public function getuser(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }


    protected $table = "personality";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    /**
     * 展示个性化
     * @param $userId
     * @param $background
     * @return false
     */
    public static function lyt_showGxh($userId)
    {
        try {

            $res = self::with('getuser')
                ->join('background', 'background.id', 'background_id')
                ->join('flower', 'flower.id', 'flower_id')
                ->where('user_id', '=', $userId)
//                ->select([
////                    'background.background_url',
////                    'flower.flower_url',
////                    'personality_image'
//                ])
//            ->
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
     * 个性化
     * @param $userId
     * @param $background
     * @return false
     */
    public static function lyt_insertBackground($userId, $background)
    {
        try {
            $reslute1 = self::where('personality.user_id', '=', $userId)
                ->exists();

            if (!$reslute1) {
                $res = self::join('user', 'user.user_id', 'personality.user_id')
                    ->where('personality.user_id', '=', $userId)
                    ->insert([
                        'background_id' => $background,
                        'user_id'       => $userId
                    ]);
            } elseif ($reslute1) {
                $res = self::where('personality.user_id', $userId)
                    ->update([
                        'background_id'       => $background,
                        'personality.user_id' => $userId
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
     * 自定义图片
     * @param $userId
     * @param $background
     * @return false
     */
    public static function lyt_insertZdyBackground($userId, $personalityImage)
    {
        try {
            $reslute1 = self::where('personality.user_id', '=', $userId)
                ->exists();

            if (!$reslute1) {
                $res = self::join('user', 'user.user_id', 'personality.user_id')
                    ->where('personality.user_id', '=', $userId)
                    ->insert([
                        'personality_image' => $personalityImage,
                        'user_id'           => $userId
                    ]);
            } elseif ($reslute1) {
                $res = self::where('personality.user_id', $userId)
                    ->update([
                        'personality_image'   => $personalityImage,
                        'personality.user_id' => $userId
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
     * 个性化
     * @param $userId
     * @param $background
     * @return false
     */
    public static function lyt_insertFlower($userId, $flower)
    {
        try {
            $reslute1 = self::where('personality.user_id', '=', $userId)
                ->exists();

            if (!$reslute1) {
                $res = self::join('user', 'user.user_id', 'personality.user_id')
                    ->where('personality.user_id', '=', $userId)
                    ->insert([
                        'flower_id' => $flower,
                        'user_id'   => $userId
                    ]);
            } elseif ($reslute1) {
                $res = self::where('personality.user_id', $userId)
                    ->update([
                        'flower_id' => $flower,
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


}
