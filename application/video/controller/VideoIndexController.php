<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/8
 * Time: 11:20
 */

namespace app\video\controller;


use app\user\model\service\UserService;
use app\user\model\VideoLike;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class VideoIndexController extends  Controller
{
    public function videoIndex(){
        $videos=Db::table('video_work')
            ->order('thumbUp desc')
            ->page(1,5)
            ->select();
        $videoList=array();
        $currentId=Session::get('user_id');
        foreach ($videos as $video){
            if($currentId==null){
                $video['likeStyle']="glyphicon-heart-empty";
            }
            else if($this->isLike($video['id'],$currentId)==0){
                $video['likeStyle']="glyphicon-heart-empty";
            }
            else{
                $video['likeStyle']="glyphicon-heart";
            }
            $authorId=$video['authorId'];
            $user=UserService::getUserById($authorId);
            $video['authorName']=$user['name'];
            $video['profilePhoto']=$user['profilePhoto'];
            $videoList[]=$video;
        }
        $this->assign('videoList',$videoList);
        return $this->fetch('videoIndex');
    }

    public function userVideo(Request $request){
        $currentId=Session::get('user_id');
        $id=$request->param('id');
        $videos=Db::table('video_work')
            ->where('authorId',$id)
            ->select();
        $videoList=array();
        foreach ($videos as $video){
            if($this->isLike($video['id'],$currentId)==0){
                $video['likeStyle']="glyphicon-heart-empty";
            }
            else{
                $video['likeStyle']="glyphicon-heart";
            }
            $videoList[]=$video;
        }
        $this->assign('videoList',$videoList);
        if($currentId==null||$currentId!=$id)
            return $this->fetch('userVideo');
        else
            return $this->fetch('myVideo');
    }
    public function videoPage(Request $request){
        $page = $request->param('page');
        $videos=Db::table('video_work')
            ->order('thumbUp desc')
            ->page($page,5)
            ->select();
    }

    private function isLike($videoId,$userId){
        $results=Db::table('video_like')
            ->where([
                'videoId'=>$videoId,
                'userId'=>$userId,
            ])->select();
        if(sizeof($results)==0){
            return 0;
        }
        else
            return 1;
    }

    public function deleteVideo(Request $request){
        $videoId=$request->param('id');
        Db::table('video_work')
            ->where('id',$videoId)
            ->delete();
    }
}