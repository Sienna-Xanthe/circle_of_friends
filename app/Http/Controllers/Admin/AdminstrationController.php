<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeleteDynamicRequest;
use App\Http\Requests\Admin\DisableRequest;
use App\Models\Dynamics;
use App\Models\User;
use Illuminate\Http\Request;

class AdminstrationController extends Controller
{

    /**
     * 管理员管理用户
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userAdministration(Request $request)
    {
        //获取用户名字
        $user_name=$request['user_name'];
        $res=User::establishphoto3($user_name);
        return $res ?
            json_success('查询成功!', $res, 200) :
            json_fail('查询失败!', null, 100);
    }

    /**
     * 管理员操作用户账号的禁用状态
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable(DisableRequest $request)
    {
        //获取用户id和修改的状态
        $user_id=$request['user_id'];
        $user_state1=$request['user_state1'];
        $res=User::establishphoto4($user_id,$user_state1);
        return $res ?
            json_success('操作成功!', null, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 管理员管理查看用户动态
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dynamicAdministration(Request $request)
    {
        //获取动态标签和用户昵称
        $dlabel_id=$request['dlabel_id'];
        $user_nickname=$request['user_nickname'];
        $res=User::establishphoto5($dlabel_id,$user_nickname);
        return $res ?
            json_success('操作成功!', $res, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 管理员删除动态
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteDynamic(DeleteDynamicRequest $request)
    {
        //获取动态id
        $id=$request['id'];
        $res=User::establishphoto6($id);
        return $res ?
            json_success('删除成功!', null, 200) :
            json_fail('删除失败!', null, 100);
    }

}
