<?php
namespace app\user\controller;
use app\user\model\User;
class IndexController
{
    public function index()
    {
        $user = User::get(1);
        echo $user->ID . '<br/>';
        echo $user->NAME . '<br/>';
        echo $user->ADDRESS . '<br/>';
        var_dump($user);

    }
}
