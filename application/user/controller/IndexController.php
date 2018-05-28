<?php
namespace app\user\controller;
use app\user\model\service\UserService;
use app\user\model\User;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class IndexController extends  Controller
{

    public function user(Request $request)
    {
        $id=$request->param('id');
        $currentId=Session::get('user_id');
        $button="<button style=\"margin-left:5px\" data-id='".$id."' class=\"btn btn-default followBtn\">关注</button>";
        if($currentId!=$id){
            $this->assign('changeBg',"");
            if($id==null||!UserService::isFollow($id,$currentId)){
                $this->assign('button',$button);
            }
            else{
                $button="<button style=\"margin-left:5px\" data-id='".$id."' class=\"btn btn-default followBtn\">已关注</button>";
            }
            $this->assign('button',$button);
        }
        else{
            $this->assign('button','');
            $this->assign('changeBg',"<a href='/bgPreview' style='color: white' >点击改变背景</a>");
        }

        $user=UserService::userInfo($id);
        $this->assign('bgImg',$user['bgImg']);
        $this->assign('description',$user['description']);
        $this->assign('profilePhoto',$user['profilePhoto']);
        $this->assign('userId',$user['id']);
        $this->assign('userName',$user['name']);
        $this->assign('followers',$user['followers']);
        $this->assign('followings',$user['followings']);
        $this->assign('workNum',$user['workNum']);

        $allPictures=Db::table('work_picture')
            ->where('authorId',$id)
            ->select();
        $this->assign('allPictures',$allPictures);
        return $this->fetch();
    }

    /**
     * @param Request $request
     * @return mixed
     * 所有作品模板渲染返回
     */
    public function allWork(Request $request){
        $id=$request->param('id');
        $user=UserService::userInfo($id);
        $allPictures=Db::table('work_picture')
            ->where('authorId',$id)
            ->select();
        $this->assign('allPictures',$allPictures);
        return $this->fetch();
    }

    /**
     * @param Request $request
     * @return mixed
     * 关注模板渲染返回
     */
    public function followings(Request $request){
        $id=$request->param('id');
        $followings=UserService::allFollowings($id);
        $this->assign('allUsers',$followings);
        return $this->fetch('followPage');
    }

    /**
     * @param Request $request
     * 粉丝模板渲染返回
     */
    public function followers(Request $request){
        $id=$request->param('id');
        $followers=UserService::allFollowers($id);
        $this->assign('allUsers',$followers);
        return $this->fetch('followPage');
    }
}
