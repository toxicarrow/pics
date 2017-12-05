<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/21
 * Time: 15:49
 */

namespace app\user\model\service;
use app\user\model\User;
use think\Db;

class UserService
{
//    private static $_instance = null;
//    //私有构造函数，防止外界实例化对象
//    private function __construct() {
//    }
//    //私有克隆函数，防止外办克隆对象
//    private function __clone() {
//    }
//    //静态方法，单例统一访问入口
//    static public function getInstance() {
//        if (is_null ( self::$_instance ) || isset ( self::$_instance )) {
//            self::$_instance = new self ();
//        }
//        return self::$_instance;
//    }
    public static function getUserById($id)
    {
        $user = User::get($id);
        return $user;

    }
    public static function getUserByPhone($phone){
        $user = User::getByPhone($phone);
        return $user;
    }
    public static function addUser($name, $phone, $password,$salt)
    {
        $user = new User;
        $user->name=$name;
        $user->phone=$phone;
        $user->password=$password;
        $user->salt=$salt;
        $user->time=date("Y-m-d h:i:sa");
        if($user->save()){
            $result = array(
                'id'=>$user->id,
                'name'=>$user->name,
            );
            return $result;
        }
        else{
            return $user.getError();
        }

    }

    public static function deleteUser($id)
    {

    }

    public static function updateUser($info)
    {

    }

    /**
     * 得到用户信息
     * 除了基本信息外，还有粉丝人数，关注人数，收获赞数
     */
    public static function userInfo($id){
        $user = User::get($id);

        $followers= Db::table('follower')
            ->where('id',$id)
            ->select();

        $following=Db::table('follower')
            ->where('follower',$id)
            ->select();

        $works=Db::table('work')
            ->where('authorId',$id)
            ->select();
        $num=0;
        foreach ($works as $work){
            $num = $num+$work['thumbUp'];
        }

        $user['followers']=sizeof($followers);
        $user['followings']=sizeof($following);
        $user['totalThumbUp']=$num;

        return $user;

    }
}