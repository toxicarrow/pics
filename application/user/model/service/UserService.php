<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/21
 * Time: 15:49
 */

namespace app\user\model\service;
use app\user\model\User;
use app\work\model\WorkService;
use think\Db;
use think\Session;

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

    public static function isLikeWork($id,$workId){
        $results=Db::table('work_like')
            ->where([
                'workId'=>$workId,
                'userId'=>$id,
            ])
            ->select();
        if(sizeof($results)==0)
            return 0;
        else
            return 1;

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

    public static function isFollow($id,$currentId){
        $isFollow=Db::table('follower')
            ->where([
                'id'=>$id,
                'follower'=>$currentId,
                ])
            ->select();
        if(sizeof($isFollow)==0){
            return false;
        }
        else{
            return true;
        }
    }
    /**
     * @param $id
     * 我关注的所有人
     */
    public static function allFollowings($id){
        $followings = Db::table('follower')
            ->where('follower',$id)
            ->select();

        $result=array();
        $currentId=Session::get('user_id');
        foreach ($followings as $following){
            $user=self::userInfo($following['id']);
            if($currentId==null){
                $user['follow']='关注';
            }
            else if(self::isFollow($following['id'],$currentId)){
                $user['follow']='已关注';
            }
            else{
                $user['follow']='关注';
            }
            $result[]=$user;
        }
        return $result;
    }

    /**
     * @param $id
     * 所有关注我的人
     * 数据里包含该用户的头像，背景图片，关注人数，粉丝人数，作品数量以及用户基本信息
     */
    public static function allFollowers($id){
        $followings = Db::table('follower')
            ->where('id',$id)
            ->select();

        $result=array();
        $currentId=Session::get('user_id');
        foreach ($followings as $following){
            $user=self::userInfo($following['follower']);
            if($currentId==null){
                $user['follow']='关注';
            }
            else if(self::isFollow($user['id'],$currentId)){
                $user['follow']='已关注';
            }
            else{
                $user['follow']='关注';
            }
            $result[]=$user;
        }
        return $result;
    }
}