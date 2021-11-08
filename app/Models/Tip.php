<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    protected $table = "tip";
    public $timestamps = true;
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getDynamics(){
        return $this->hasOne(Dynamics::class, 'id', 'dynamics_id')
            ->with('getpublisher','getUrl');
    }

    /**
     * 查看被举报人具体的动态信息以及举报理由
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipDetails()
    {
        try {
            $res = self::with('getDynamics')->get();

            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }






    /**
     * 显示所有举报信息
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipByAll()
    {
        try {
            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
                ->join('user', 'user.user_id', 'tip.user_id')
                ->select([
                    'tip.id as tid',
                    'tip.user_id as jb',
                    'dynamics.user_id as bjb',
                    'tlabel.tlabel_name',
                    'tip.created_at',
                    'tip_state'
                ])->paginate(5);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 根据举报状态和时间展示信息
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipByNoLabel($time, $state)
    {
        try {
            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
                ->join('user', 'user.user_id', 'tip.user_id')
                ->whereDate('tip.created_at', '=', $time)
                ->where('tip_state', '=', $state)
                ->select([
                    'tip.id as tid',
                    'tip.user_id as jb',
                    'dynamics.user_id as bjb',
                    'tlabel.tlabel_name',
                    'tip.created_at',
                    'tip_state'
                ])->paginate(5);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 根据举报标签和状态展示信息
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipByNoTime($tLabel, $state)
    {
        try {
            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
                ->join('user', 'user.user_id', 'tip.user_id')
                ->where('tlabel.id', '=', $tLabel)
                ->where('tip_state', '=', $state)
                ->select([
                    'tip.id as tid',
                    'tip.user_id as jb',
                    'dynamics.user_id as bjb',
                    'tlabel.tlabel_name',
                    'tip.created_at',
                    'tip_state'
                ])->paginate(5);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 根据举报标签和时间展示信息
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipByNoState($time, $tLabel)
    {
        try {
            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
                ->join('user', 'user.user_id', 'tip.user_id')
                ->where('tlabel.id', '=', $tLabel)
                ->whereDate('tip.created_at', '=', $time)
                ->select([
                    'tip.id as tid',
                    'tip.user_id as jb',
                    'dynamics.user_id as bjb',
                    'tlabel.tlabel_name',
                    'tip.created_at',
                    'tip_state'
                ])->paginate(5);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 根据举报标签和时间展示信息
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipByAll1($time,$tLabel,$state)
    {
        try {
            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
                ->join('user', 'user.user_id', 'tip.user_id')
                ->where('tlabel.id', '=', $tLabel)
                ->whereDate('tip.created_at', '=', $time)
                ->where('tip_state', '=', $state)
                ->select([
                    'tip.id as tid',
                    'tip.user_id as jb',
                    'dynamics.user_id as bjb',
                    'tlabel.tlabel_name',
                    'tip.created_at',
                    'tip_state'
                ])->paginate(5);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }


    /**
     * 根据举报标签展示信息
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipByLabel($tLabel)
    {
        try {
            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
                ->join('user', 'user.user_id', 'tip.user_id')
                ->where('tlabel.id', '=', $tLabel)
                ->select([
                    'tip.id as tid',
                    'tip.user_id as jb',
                    'dynamics.user_id as bjb',
                    'tlabel.tlabel_name',
                    'tip.created_at',
                    'tip_state'
                ])->paginate(5);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 根据举报时间展示信息
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipByTime($time)
    {
        try {
            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
                ->join('user', 'user.user_id', 'tip.user_id')
                ->whereDate('tip.created_at', '=', $time)
                ->select([
                    'tip.id as tid',
                    'tip.user_id as jb',
                    'dynamics.user_id as bjb',
                    'tlabel.tlabel_name',
                    'tip.created_at',
                    'tip_state'
                ])->paginate(5);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }

    /**
     * 根据举报状态展示信息
     * @param $tLabel
     * @return false
     */
    public static function lyt_selectTipByState($state)
    {
        try {
            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
                ->join('user', 'user.user_id', 'tip.user_id')
                ->where('tip_state', '=', $state)
                ->select([
                    'tip.id as tid',
                    'tip.user_id as jb',
                    'dynamics.user_id as bjb',
                    'tlabel.tlabel_name',
                    'tip.created_at',
                    'tip_state'
                ])->paginate(5);
            return $res ?
                $res :
                false;
        } catch (\Exception $e) {
            logError('搜索错误', [$e->getMessage()]);
            return false;
        }
    }


//    /**
//     * 查看被举报人具体的动态信息以及举报理由
//     * @param $tLabel
//     * @return false
//     */
//    public static function lyt_selectTipDetails($tid)
//    {
//        try {
//            $res = self::join('tlabel', 'tlabel.id', 'tip.tlabel_id')
//                ->join('dynamics', 'dynamics.id', 'tip.dynamics_id')
//                ->join('user', 'user.user_id', 'tip.user_id')
//                ->where('tip.id', '=', $tid)
//                ->select([
//
//                    'dynamics.dynamics_content as content',
//                    'dynamics.id as did',
//                    'dynamics.dlabel_id as dbq',
//
//                    'tlabel.tlabel_name as tbq',
//                    'tip_reason',
//                    'dynamics.user_id as duid',
//
//                    'dynamics.created_at as dtime',
//
//                    'tip_state'
//
//
//                ])->get();
//            return $res ?
//                $res :
//                false;
//        } catch (\Exception $e) {
//            logError('搜索错误', [$e->getMessage()]);
//            return false;
//        }
//    }

    /**
     * 驳回举报
     * @param $tLabel
     * @return false
     */
    public static function lyt_failedTip($tid)
    {
        try {
            $res = self::where('tip.id', '=', $tid)
                ->update([
                    'tip_state' => 2
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
     * 删除举报其他
     * @param $tLabel
     * @return false
     */
    public static function lyt_successTipByOther($tid)
    {
        try {
            $did    = self::join('dynamics', 'dynamics.id', 'dynamics_id')
                ->where('tip.id', '=', $tid)
                ->value('dynamics.id');

            $res[0] = Url::join('dynamics', 'dynamics.id', 'dynamics_id')
                ->where('dynamics_id', '=', $did)
                ->delete();

            $res[1] = Collection::join('dynamics', 'dynamics.id', 'dynamics_id')
                ->where('dynamics_id', '=', $did)
                ->delete();

            $res[2] = Likes::join('dynamics', 'dynamics.id', 'dynamics_id')
                ->where('dynamics_id', '=', $did)
                ->delete();

            $res[3] = Comment::join('dynamics', 'dynamics.id', 'dynamics_id')
                ->where('dynamics_id', '=', $did)
                ->delete();

            $res[4] = Dynamics::where('id', '=', $did)
                ->delete();

            $res[5] = Tip::where('tip.id', '=', $tid)
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
