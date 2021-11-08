<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\deleteSuccessTipRequest;
use App\Http\Requests\Admin\showTipInformationByDetailRequest;
use App\Http\Requests\Admin\showTipInformationByManyRequest;
use App\Http\Requests\Admin\updatefailedTipRequest;
use App\Models\Tip;
use Illuminate\Http\Request;

class TipController extends Controller
{
    /**
     * 举报详情
     * @param  showTipInformationByDetailRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTipInformationByDetail(showTipInformationByDetailRequest $request)
    {
        $tid = $request['tid'];
        $res = Tip::lyt_selectTipDetails();
        return $res ?

            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 驳回
     * @param  updatefailedTipRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatefailedTip(updatefailedTipRequest $request)
    {
        $tid = $request['tid'];
        $res = Tip::lyt_failedTip($tid);
        return $res ?
            json_success('更新成功!', $res, 200) :
            json_fail('更新失败!', null, 100);
    }

    /**
     * 通过
     * @param  deleteSuccessTipRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSuccessTip(deleteSuccessTipRequest $request)
    {
        $tid = $request['tid'];
        $res = Tip::lyt_successTipByOther($tid);

        return $res ?

            json_success('删除成功!', null, 200) :
            json_fail('删除失败!', null, 100);
    }

    /**
     * 举报显示
     * @param  showTipInformationByManyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showTipInformationByMany(showTipInformationByManyRequest $request)
    {
        //获取举报标签，举报时间，审批状态
        $tLabel = $request['tLabel'];
        $time   = $request['time'];
        $state  = $request['state'];
        $page   = $request['page'];


        if ($tLabel == 0 && $time == null && $state == 0) {
            $res = Tip::lyt_selectTipByAll();
        } elseif ($tLabel == 0 && $time == null) {
            $res = Tip::lyt_selectTipByState($state);
        } elseif ($time == null && $state == 0) {
            $res = Tip::lyt_selectTipByLabel($tLabel);
        } elseif ($tLabel == 0 && $state == 0) {
            $res = Tip::lyt_selectTipByTime($time);
        } elseif ($tLabel == 0) {
            $res = Tip::lyt_selectTipByNoLabel($time, $state);
        } elseif ($time == null) {
            $res = Tip::lyt_selectTipByNoTime($tLabel, $state);
        } elseif ($state == 0) {
            $res = Tip::lyt_selectTipByNoState($time, $tLabel);
        }elseif ($tLabel != 0 && $time != null && $state != 0){
           $res=Tip::lyt_selectTipByAll1($time,$tLabel,$state);
        }

        return $res ?

            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }
}
