<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/6
 * Time: 10:41
 */

namespace app\user\controller;


use app\common\Util;
use app\user\model\service\UserService;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class FollowingWorkController extends Controller
{
    public function followingWork(Request $request){
        $id=Session::get('user_id');
        if($id==null){
            return "请先登录";
        }
        if($request->isAjax()){
            $page=$request->param('page');
            $followingWorks=$this->getFollowingWork($id,$page);
            $this->assign('followingWorks',$followingWorks);
            if(sizeof($followingWorks)==0){
                return json("空");
            }
            return $this->fetch('workBody');
        }
        $followingWorks=$this->getFollowingWork($id,1);
        $this->assign('followingWorks',$followingWorks);

        $user=UserService::userInfo($id);
        $this->assign('userId',$user['id']);
        $this->assign('profilePhoto',$user['profilePhoto']);
        $this->assign('bgImg',$user['bgImg']);
        $this->assign('name',$user['name']);
        $this->assign('following',$user['followings']);
        $this->assign('follower',$user['followers']);
        $this->assign('workNum',$user['workNum']);
        return $this->fetch();
    }

    private function getFollowingWork($id,$page){
        //获取所有关注的用户id
        $followings=Db::table('follower')
            ->where('follower',$id)
            ->select();
        $followingsId=array();
        foreach ($followings as $following){
            $followingsId[]=$following['id'];
        }

        //获取特定页数据，每页数量由Util内的PageWork指定
        $works=Db::table('work')
            ->where('authorId','in',$followingsId)
            ->order('date desc')
            ->page($page,Util::$pageWork)
            ->select();
        $followingWorks=array();
        foreach ($works as $work){
            $workId=$work['id'];
            if(UserService::isLikeWork($id,$workId)==0){
                $work['likeStyle']='glyphicon-heart-empty';
            }
            else{
                $work['likeStyle']='glyphicon-heart';
            }

            $user=UserService::getUserById($work['authorId']);
            $work['profilePhoto']=$user['profilePhoto'];
            $work['name']=$user['name'];
            $pictures=Db::table('work_picture')
                ->where('articleId',$work['id'])
                ->select();
            $work['pictures']=$pictures;
            $date=date_create($work['date']);
            $work['date']=date_format($date,'Y-m-d');
            $followingWorks[]=$work;
        }
        return $followingWorks;
    }


}