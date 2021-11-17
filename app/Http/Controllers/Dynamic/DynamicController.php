<?php

namespace App\Http\Controllers\Dynamic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dynamic\CollectionRequest;
use App\Http\Requests\Dynamic\DeleteCommentRequest;
use App\Http\Requests\Dynamic\DynamicRequest;
use App\Http\Requests\Dynamic\DynamicRequest1;
use App\Http\Requests\Dynamic\DynamicRequest2;
use App\Http\Requests\Dynamic\FabulousRequest;
use App\Http\Requests\Dynamic\MineTypeRequest;
use App\Http\Requests\Dynamic\OthersTypeRequest;
use App\Http\Requests\Dynamic\ReportRequest;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Dynamics;
use App\Models\Likes;
use App\Models\Tip;
use http\Env\Response;
use Illuminate\Http\Request;

class DynamicController extends Controller
{

    /**
     * 将用户发表的动态信息储存在动态表中
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish(DynamicRequest1 $request)
    {
        //获取用户id、动态标签、动态内容与图片或视频的url
        $user_id=$request['user_id'];
        $dynamics_content=$request['dynamics_content'];
        $dlabel_id=$request['dlabel_id'];
        $url=$request['url'];
        $res=Dynamics::establishphoto($user_id,$dynamics_content,$dlabel_id,$url);
        //$res为2，则内容与图片不能都为空
        if ($res==2){
          return  json_fail('内容与图片不能都为空!', null, 300);
        }
        return $res ?
            json_success('发表成功!', null, 200) :
            json_fail('发表失败!', null, 100);
    }

    /**
     * 将用户发表的评论信息储存在评论表中
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function comment(DynamicRequest2 $request)
    {
        //获取用户id、评论内容、动态id
        $user_id=$request['user_id'];
        $comment_content=$request['comment_content'];
        $dynamics_id=$request['id'];
        $res=Comment::establishphoto1($user_id,$comment_content,$dynamics_id);
        return $res ?
            json_success('评论成功!', null, 200) :
            json_fail('评论失败!', null, 100);
    }


    /**
     * 主页展示全部的动态
     * @zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wholeType(DynamicRequest $request)
    {
        //获取用户id  动态标签 动态发布时间
        $user_id=$request['user_id'];
        $dlabel_id=$request['dlabel_id'];
        $date=$request['date'];
        $res=Dynamics::establishphoto2($user_id,$dlabel_id,$date);
        return $res ?
            json_success('查询成功!', $res, 200) :
            json_fail('查询失败!', null, 100);
    }


    /**
     * 用户举报动态进行信息填写
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function report(ReportRequest $request)
    {
        //获取举报人id  该动态id 举报类型 举报理由
        $user_id=$request['user_id'];
        $tlabel_id=$request['tlabel_id'];
        $id=$request['id'];
        $tip_reason=$request['tip_reason'];
        $informant_name=$request['informant_name'];
        $res=Tip::establishphoto3($user_id,$tlabel_id,$id,$tip_reason,$informant_name);
        return $res ?
            json_success('举报成功!', null, 200) :
            json_fail('举报失败!', null, 100);
    }

    /**
     * 收藏动态和取消收藏
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function collection(CollectionRequest $request)
    {
        //获取用户id  动态id
        $user_id=$request['user_id'];
        $dynamics_id=$request['dynamics_id'];
        $res=Collection::establishphoto4($user_id,$dynamics_id);
        return $res ?
            json_success('操作成功!', null, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 取消收藏的动态
     * @author zqz
     * @param CollectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelCollection(CollectionRequest $request)
    {
        //获取用户id  动态id
        $user_id=$request['user_id'];
        $dynamics_id=$request['dynamics_id'];
        $res=Collection::establishphoto5($user_id,$dynamics_id);
        return $res ?
            json_success('取消成功!', null, 200) :
            json_fail('取消失败!', null, 100);
    }

    /**
     * 用户删除自己的动态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        //获取动态id
        $id=$request['id'];
        $res=Dynamics::establishphoto6($id);
        return $res ?
            json_success('删除成功!', null, 200) :
            json_fail('删除失败!', null, 100);
    }

    /**
     * 查看用户自己的收藏动态
     * @author zqz
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mineCollection(DynamicRequest  $request)
    {
        //获取用户id
        $user_id=$request['user_id'];
        $res=Collection::establishphoto7($user_id);
        return $res ?
            json_success('查询成功!', $res, 200) :
            json_fail('查询失败!', null, 100);
    }

    /**
     * 查看用户收藏动态中的动态详情
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectionDetails(DeleteCommentRequest $request)
    {
        //获取用户id  动态id
        $id=$request['id'];
        $user_id=$request['user_id'];
        $res=Dynamics::establishphoto8($id,$user_id);
        return $res ?
            json_success('查询成功!', $res, 200) :
            json_fail('查询失败!', null, 100);
    }

    /**
     * 用户查看自己的全部动态
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mineType(DynamicRequest $request)
    {
        //获取用户id  动态标签 动态发布日期
        $user_id=$request['user_id'];
        $dlabel_id=$request['dlabel_id'];
        $date=$request['date'];
        $res=Dynamics::establishphoto9($user_id,$dlabel_id,$date);
        return $res ?
            json_success('查询成功!', $res, 200) :
            json_fail('查询失败!', null, 100);
    }

    /**
     * 对动态进行点赞或取消点赞
     * @author zqz
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fabulous(FabulousRequest $request)
    {
        //获取用户id  动态id
        $user_id=$request['user_id'];
        $dynamics_id=$request['dynamics_id'];
        $res=Likes::establishphoto10($user_id,$dynamics_id);
        return $res ?
            json_success('操作成功!', null, 200) :
            json_fail('操作失败!', null, 100);
    }

    /**
     * 用户删除评论
     * @author zqz
     * @param DeleteCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteComment(DeleteCommentRequest $request)
    {
        $id=$request['id'];
        $res=Comment::establishphoto11($id);
        return $res ?
            json_success('删除成功!', null, 200) :
            json_fail('删除失败!', null, 100);
    }

    /**
     * 用户进入他人主页查看他人动态
     * @zqz
     * @param OthersTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function othersType(OthersTypeRequest $request)
    {
        $user_id_one=$request['user_id_one'];
        $user_id_two=$request['user_id_two'];
        $dlabel_id=$request['dlabel_id'];
        $date=$request['date'];
        $res=Dynamics::establishphoto12($user_id_one,$user_id_two,$dlabel_id,$date);
        return $res ?
            json_success('查询成功!', $res, 200) :
            json_fail('查询失败!', null, 100);
    }

}
