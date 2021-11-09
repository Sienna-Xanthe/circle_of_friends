<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserInfo\UserRegisteredRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    private $accessKey = 'dingyeg1sljjmk0vppsl';
    private $appsecret = 'F2LPWO1jZHCJBSZY8M8RwjbYERjkV7EBh8-NzlC_nhZcFtvxcv7dmHn3PXTZLjRm';
    private $access_token = '9efd7396409e35d3bb5775ed47fe578e';

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userlogin(Request $request){
        $code = $request['code'];
        $state = $request['state'];
//        echo "code: " . $code . "\n" ."state:". $state."\n";
        $this->gettoken();
        $unionid = $this->back($code,$state);//unionid
//        echo "unionid: ".$unionid."\n";
        $userid = $this->getbyunionid($unionid);
        if($userid === false){
            return
                json_fail('请先加入组织!登录失败!!!', null, 100);
        }
//        echo "userid: ".$userid."\n";
        $detail = $this->getall($userid);
        $avatar = $detail['result']['avatar'];//avatar
        $name = $detail['result']['name'];//name
//        echo "avatar: ".$avatar."\n";
//        echo "name: ".$name."\n";
        $userinfo['user_id'] = $unionid;//
        $userinfo['user_image'] = $avatar;
        $userinfo['user_name'] = $name;
        $logincount = user::logincheck($unionid);
        if($logincount == 0){
            $userinfo['isfirst'] = 1;
            return
                json_success('请先填写个人信息', $userinfo, 200);
        }
        else if($logincount == 1){
            $userinfo['isfirst'] = 0;
            $is_disable = User::is_disable($unionid);
            if($is_disable == 1)
            {
                $userinfo = User::getinfo($unionid);
                $userinfo['user_id'] = $unionid;//
                $userinfo['user_image'] = $avatar;
                $userinfo['user_name'] = $name;
                $userinfo['isfirst'] = 0;

                return json_success('登录成功!', $userinfo, 200);
            }
            else if($is_disable == 0)
            {
                return json_success('该账户已被禁用!登录失败!', null, 100);
            }
        }
        return
            json_fail('登录失败!', null, 100);
    }

    /**
     * 第一次登录填个人资料
     * @param UserRegisteredRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registered(UserRegisteredRequest $request){
        return User::informationforfirst($request) ?
            json_success('存储成功!', null, 200) :
            json_success('存储失败!', null, 100);
    }



    public function scancode()
    {
        //echo "test";
        return redirect('https://oapi.dingtalk.com/connect/qrconnect?appid=dingyeg1sljjmk0vppsl&response_type=code&scope=snsapi_login&state=STATE&redirect_uri=http://friend.pxy.fit/api/user/login');
    }
    /**
     * 获取access_token
     */
    public function gettoken(){
        $url = 'https://api.dingtalk.com/v1.0/oauth2/accessToken';
        $data = [ 'appKey' => $this->accessKey ,
            'appSecret' => $this->appsecret
        ];
        $token = $this->curl_json($url,$data);
        $res       = json_decode($token, true);
        $this->access_token = $res['accessToken'];
//        echo "------------------access_token: \n";
//        echo $this->access_token;
    }

    /**
     * 服务端通过临时授权码获取授权用户的个人信息获取unionid
     * @param $code
     * @param $state
     * @return mixed
     */
    public function back($code,$state)
    {
        if(!$code or !$state) {
            $this->error('参数缺失');
        }
        if($state > time()) {
            $this->error('参数异常');
        }
        //$accessKey = 'dingyeg1sljjmk0vppsl';
        $timestamp = $this->mTime();
        $signature = $this->signature($timestamp);
        $url       = 'https://oapi.dingtalk.com/sns/getuserinfo_bycode?accessKey=' . $this->accessKey . '&timestamp=' . $timestamp . '&signature=' . $signature;
        $data      = [ 'tmp_auth_code' => $code ];
        $userInfo  = $this->curl_json($url, $data);
        $res       = json_decode($userInfo, true);

        if(isset($res['errcode']) and $res['errcode'] == 0) {
            //登录成功
            //echo "----第一层登录成功！----";
            $unionid = $res['user_info']['unionid'];
            return $unionid;
        }
    }

    /**
     * 通过unionid获取userid
     * @param $unionid
     */
    public function getbyunionid($unionid){
        $url = 'https://oapi.dingtalk.com/topapi/user/getbyunionid?access_token='.$this->access_token;
        $data      = [ 'unionid' => $unionid ];
        $user = $this->curl_json($url,$data);
        $res       = json_decode($user, true);
        //var_dump($res);
        if(isset($res['errcode']) and $res['errcode'] == 0) {
            $userid = $res['result']['userid'];
            return $userid;
        }
        else{
            return false;
        }

    }

    /**
     * 根据userid获取用户详情
     */
    public function getall($userid){
        $url = 'https://oapi.dingtalk.com/topapi/v2/user/get?access_token='.$this->access_token;
        $data      = [ 'userid' => $userid ];
        $all = $this->curl_json($url,$data);
        $res       = json_decode($all, true);
        return $res;
    }

    /**
     * 计算签名
     * @param $timestamp
     * @return string
     */
    public function signature($timestamp): string
    {
        // 根据timestamp, appSecret计算签名值
        $s                   = hash_hmac('sha256', $timestamp, $this->appsecret, true);
        $signature           = base64_encode($s);
        $urlEncode_signature = urlencode($signature);
        return $urlEncode_signature;
    }

    /**
     * 毫秒级时间戳
     * @return float
     */
    public function mTime(): float
    {
        list($s1, $s2) = explode(' ', microtime());
        $mTime = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
        return $mTime;
    }

    /**
     * post请求传入url
     * @param $url
     * @param null $postFields
     * @return bool|string
     */
    public function curl_json($url, $postFields = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "dingtalk-sdk-php");
        //https 请求
        if(strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        $header = [ "Content-Type: application/json; charset=utf-8", "Content-Length:" . strlen(json_encode($postFields)) ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));
        $reponse = curl_exec($ch);
        if(curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if(200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }
        curl_close($ch);
        return $reponse;
    }
}
