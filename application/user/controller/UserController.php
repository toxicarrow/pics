<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/23
 * Time: 13:12
 */

namespace app\user\controller;
use app\user\model\User;
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
//        $msg['msg']=$verify;
//        return json($msg);
        if($verify==false){
            $msg['msg']= "手机号或验证码错误";
            return json($msg);
        }
        else if($verify['code']==200){
            //密码加盐
            $salt = Util::getRandChar(10);
            $safePwd = sha1(sha1($pwd).$salt);

            $user=new User;
            $user->name=$name;
            $user->phone=$phone;
            $user->salt=$salt;
            $user->password=$safePwd;
            $user->profilePhoto='/static/img/userDefault.jpg';
            $user->bgImg='http://www.igallery.com/static/img/userbg01.jpg';
            $user->time=date('Y-m-d');
            $user->save();

            $msg['code']= 200;
            $msg['msg']= "注册成功！";
            $user=UserService::getUserByPhone($phone);
            $userId=$user['id'];
            $msg['id']=$userId;
            $msg['name']=$name;
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
//        if($user==false){
//            $msg['code']=0;
//            $msg['msg']= "手机号错误";
//            return json($msg);
//        }
//        if($isVerifyWay==1){
//            $verify = VerifyController::verify($phone,$request->param("code"));
////            return json($verify);
//            //验证码验证成功,成功登录
//            if($verify['code']==404){
//                $msg['code']=0;
//                $msg['msg']= "不存在验证码或手机号";
//            }
//            else if($verify['code']==200){
//                $msg['code']=1;
//                $msg['id']=$user['id'];
//                $msg['name']=$user['name'];
//                Session::set('user_name',$user->name);
//                Session::set('user_id',$user->id);
//                $msg['msg']= "登录成功";
//            }
//            else{
//                $msg['code']=0;
//                $msg['msg']= "验证码错误";
//            }

                $msg['code']=1;
                $msg['id']=$user['id'];
                $msg['name']=$user['name'];
                Session::set('user_name',$user->name);
                Session::set('user_id',$user->id);
                $msg['msg']= "登录成功";
                return json($msg);
        }
//        else{
//            $pwd = $request->param("password");
//            $salt = $user->salt;
//            $rightPwd = $user->password;
//            $currentPwd = sha1(sha1($pwd).$salt);
//            $msg=array();
//            if($currentPwd==$rightPwd){
//                Session::set('user_name',$user->name);
//                Session::set('user_id',$user->id);
//                $msg['msg']='登录成功';
//                $msg['id']=$user->id;
//                $msg['name']=$user->name;
//                $msg['code']=1;
//            }
//            else{
//                $msg['msg']='用户名或密码错误';
//                $msg['code']=0;
//            }
//            return json($msg);
//
//        }
//    }

    public function test(Request $request){
        $name = $request->param("name");
        return $request->session('user_name');
    }

    public function logout(Request $request){
        if(Session::has('user_id')){
            Session::set('user_name',null);
            Session::set('user_id',null);
            return json('登出');
        }
        else{
            return json("账号未登录");
        }

    }

}

