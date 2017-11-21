<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/21
 * Time: 15:47
 */

namespace app\user\model\service;


interface UserService
{
    public function getNameById($id);
    public function addUser($name,$phone,$password);
    public function deleteUser($id);
    public function updateUser($info);
}