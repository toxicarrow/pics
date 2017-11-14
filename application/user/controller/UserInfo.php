<?php
namespace app\user\controller;
use think\Controller;
class UserInfo extends Controller{
    //进入用户界面
    public function userInfo(){
        return $this->fetch();
}
}
/**
 * Created by PhpStorm.
 * UserInfo: 涵
 * Date: 2017/11/10
 * Time: 15:58
 */
