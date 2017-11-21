<?php
namespace app\user\controller;
use think\Controller;
class UserInfoController extends Controller{
    //进入用户界面
    public function userInfo(){
        trace("用户界面");
        return $this->fetch();
}
}
/**
 * Created by PhpStorm.
 * UserInfo: 涵
 * Date: 2017/11/10
 * Time: 15:58
 */
