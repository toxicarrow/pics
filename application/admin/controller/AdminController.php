<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/11
 * Time: 13:51
 */

namespace app\admin\controller;


use think\Controller;
use think\Request;
use think\Db;
use think\Session;

class AdminController extends Controller
{
    public function login(){
        return $this->fetch();
    }

    public function doLogin(Request $request){
        $msg=array();
        $name=$request->param('username');
        $password=$request->param('password');
        $admin=Db::table("admin")
            ->where([
                'name'=>$name,
                'password'=>$password,
            ])
            ->select();
        if(sizeof($admin)==0){
            $msg['code']=0;
            return json($msg);
        }
        $msg['code']=1;
        Session::set('admin',$name);
        return json($msg);
    }

    public function index(Request $request){
        if(Session::get('admin')==null){
            return $this->error('请先登录！');
        }
        else
            return $this->fetch();
    }



}