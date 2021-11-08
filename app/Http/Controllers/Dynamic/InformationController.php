<?php


namespace App\Http\Controllers\Dynamic;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * 消息的展示
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showInformation(Request $request)
    {
        $userId = $request['userId'];

//        return $res ?
//            json_success('操作成功!', $res, 200) :
//            json_fail('操作失败!', null, 100);
    }
}
