<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/12
 * Time: 15:21
 */

namespace app\index\controller;


use app\user\model\service\UserService;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class SearchController extends Controller
{
    public function searchResult(Request $request){
        $content=$request->param('content');
        $this->assign('content',$content);

        $users=Db::table('user')
            ->where('name|description','like','%'.$content.'%')
            ->select();
        $id=Session::get('user_id');
        $userInfos=array();
        foreach ($users as $user){
            $userInfo=UserService::userInfo($user['id']);
            if($id==null){
                $userInfo['follow']='关注';
            }
            else if(UserService::isFollow($user['id'],$id)){
                $userInfo['follow']='已关注';
            }
            else{
                $userInfo['follow']="关注";
            }
            $userInfos[]=$userInfo;
        }

        $works=Db::table('work')
            ->where('title|description','like','%'.$content.'%')
            ->select();
        $workInfos=array();
        foreach ($works as $work){
            $workId=$work['id'];
            $picture=Db::table('work_picture')
                ->where('articleId',$workId)
                ->find();
            $work['thumbPath']=$picture['thumbPath'];
            $work['path']=$picture['path'];
            $workInfos[]=$work;
        }
        $this->assign('users',$userInfos);
        $this->assign('works',$workInfos);
        return $this->fetch();
    }
}