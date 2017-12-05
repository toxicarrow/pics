<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/23
 * Time: 13:12
 */

namespace app\user\controller;
use think\Controller;
use think\Request;
use app\common\Util;
use app\user\model\service\UserService;
use think\Session;

class userController extends Controller
{

    public function register(Request $request){
        $phone = $request->param("phone");
        $pwd = $request->param("password");
        $name = $request->param("name");
        $msg=array();
        if($phone==""||$pwd==""||$name==""){
            $msg['msg']= "请输入内容！";
            return json($msg);
        }
        $verify = VerifyController::verify($phone,$request->param("code"));
        if($verify==false){
            $msg['msg']= "手机号或验证码错误";
            return json($msg);
        }
        else if($verify['code']==200){
            //密码加盐
            $salt = Util::getRandChar(10);
            $safePwd = sha1(sha1($pwd).$salt);

            $result = UserService::addUser($name, $phone, $safePwd,$salt);
            $msg['code']= 200;
            $msg['msg']= "注册成功！";
            return json($msg);
        }
        else{
            $msg['msg']= "手机号或验证码错误";
            return json($msg);
        }
    }

    public function login(Request $request){
        //是否验证码登录
        $msg=array();
        $isVerifyWay = $request->param('isVerify');
        //return $isVerifyWay;
        $phone = $request->param("phone");
        $user = UserService::getUserByPhone($phone);
        if($user==false){
            return "手机号错误！";
        }
        if($isVerifyWay=='true'){
            $verify = VerifyController::verify($phone,$request->param("code"));
            //验证码验证成功,成功登录
            if($verify==false){
                $msg['msg']= "验证码错误或失效";
            }
            else if($verify['code']==200){
                Session::set('user_name',$user->name);
                Session::set('user_name',$user->id);
                $msg['msg']= "注册成功";
            }
            else{
                $msg['msg']= "验证失败";
            }
            return json($msg);
        }
        else{
            $pwd = $request->param("password");
            $salt = $user->salt;
            $rightPwd = $user->password;
            $currentPwd = sha1(sha1($pwd).$salt);
            $msg=array();
            if($currentPwd==$rightPwd){
                Session::set('user_name',$user->name);
                Session::set('user_id',$user->id);
                $msg['msg']='登录成功';
                $msg['id']=$user->id;
                $msg['name']=$user->name;
            }
            else{
                $msg['msg']='用户名或密码错误';
            }
            return json($msg);

        }
    }

    public function test(Request $request){
        $name = $request->param("name");
        return $request->session('user_name');
    }

    public function logout(Request $request){
        if(Session::has('user_name')){
            Session::delete('user_name');
            Session::set('user_id');
            return $this->fetch();
        }
        else{
            return "账号未登录";
        }

    }

}

