<?php
namespace app\user\controller;
use think\Controller;
/**
 * Created by PhpStorm.
 * UserInfo: 涵
 * Date: 2017/10/24
 * Time: 12:35
 */
class Login extends Controller{
    public function login(){
        return $this->fetch();
    }
}
?>