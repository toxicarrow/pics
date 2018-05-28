<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/11
 * Time: 15:47
 */

namespace app\admin\controller;
use app\common\Util;
use app\user\model\service\UserService;
use app\user\model\User;
use think\Db;
use think\Controller;
use think\Request;

class UserController extends Controller
{
    public function userList(){
        $data = Db::name('user')
            ->order('id asc')
            ->paginate(12);
        $this->assign('users', $data);
        return $this->fetch();
    }
    public function showAdd(){
        return $this->fetch('add');
    }
    public function addUser(Request $request){
        $phone=$request->param('phone');
        $name=$request->param('username');
        $password=$request->param('password');
        $check_password=$request->param('check_password');
        if($password!=$check_password){
            return $this->error('两次密码输入不一致！');
        }
        if(strlen($phone)!=11){
            return $this->error('手机号应为11位！');
        }
        $user=new User;
        $user->name=$name;
        $user->phone=$phone;
        $user->time=date('Y-m-d');
        $salt=Util::getRandChar(10);
        $password=sha1(sha1($password).$salt);
        $user->salt=$salt;
        $user->password=$password;
        $user->save();
        return $this->success('success');
    }

    public function deleteUser(Request $request){
        $id=$request->param('id');
        Db::table('user')
            ->where('id',$id)
            ->delete();
        $this->success('删除成功');
    }

    public function edit($id)
    {

        $data=UserService::getUserById($id);
        $this->assign('data', $data);
        return $this->fetch();
    }

    public function editUser(Request $request){
        $phone=$request->param('phone');
        $name=$request->param('username');
        $password=$request->param('password');
        $id=$request->param('id');
        $check_password=$request->param('check_password');
        if($password!=$check_password){
            return $this->error('两次密码输入不一致！');
        }
        if(strlen($phone)!=11){
            return $this->error('手机号应为11位！');
        }
        $user=UserService::getUserById($id);
        $user->name=$name;
        $user->phone=$phone;
        $user->time=date('Y-m-d');
        $salt=Util::getRandChar(10);
        if($password!=""){
            $password=sha1(sha1($password).$salt);
            $user->salt=$salt;
            $user->password=$password;
        }
        $user->save();
        return $this->success('success');
    }


}