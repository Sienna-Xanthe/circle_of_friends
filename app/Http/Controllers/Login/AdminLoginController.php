<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\AdminLoginRequest;
use App\Models\Login;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    /**
     * 登录
     * @param Request $loginRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AdminLoginRequest $loginRequest)
    {

        try {
            $credentials = self::credentials($loginRequest);
            if (!$token = auth('api')->attempt($credentials)) {
                return json_fail(100, '账号或者密码错误!', null);
            }

            return self::respondWithToken($token, '登录成功!');
        } catch (\Exception $e) {

            echo $e->getMessage();
            return json_fail(500, '登录失败!', null, 500);
        }
    }
    /**
     * 注销登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
        } catch (\Exception $e) {

        }
        return auth()->check() ?
            json_fail('注销登录失败!',null, 100 ) :
            json_success('注销登录成功!',null,  200);
    }
    /**
     * 注册
     * @param Request $registeredRequest
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function registered(AdminLoginRequest $registeredRequest)
    {
        return Login::createUser(self::userHandle($registeredRequest)) ?
            json_success('注册成功!',null,200  ) :
            json_success('注册失败!',null,100  ) ;

    }
    protected function userHandle($request)
    {
        $registeredInfo = $request->except('password_confirmation');
        $registeredInfo['password'] = bcrypt($registeredInfo['password']);//密码
        $registeredInfo['username'] = $registeredInfo['username'];//用户名
        return $registeredInfo;
    }


    protected function credentials($request)
    {
        return ['username' => $request['username'], 'password' => $request['password']];
    }
    protected function respondWithToken($token, $msg)
    {
        //$data = auth('api')->user();
        return json_success( $msg, array(
            'token' => $token,
            //设置权限  'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ),200);
    }
}
