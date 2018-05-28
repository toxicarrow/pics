<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/10
 * Time: 13:51
 */

namespace app\user\controller;
use app\user\model\EventLike;
use app\user\model\WorkLike;
use app\user\model\VideoLike;
use think\Controller;
use think\Db;
use think\Request;
use think\Session;

class LikeController extends Controller
{
    public function likeWork(Request $request){
        $workId=$request->param('work');
        $id=Session::get('user_id');
        if($id==null){
            return json("请先登录");
        }
        else{
            $workLike=new WorkLike;
            $workLike->workId=$workId;
            $workLike->userId=$id;
            $workLike->save();
            $work=Db::table('work')
                ->where('id',$workId)
                ->find();

            Db::table('work')
                ->where('id',$workId)
                ->update(['thumbUp'=>($work['thumbUp']+1)]);
            return json("成功");
        }
    }

    public function disLikeWork(Request $request){
        $workId=$request->param('work');
        $id=Session::get('user_id');
        if($id==null){
            return json("请先登录");
        }
        else{
            Db::table('work_like')
                ->where([
                    'workId'=>$workId,
                    'userId'=>$id,
                    ])
                ->delete();
            $work=Db::table('work')
                ->where('id',$workId)
                ->find();

            Db::table('work')
                ->where('id',$workId)
                ->update(['thumbUp'=>($work['thumbUp']-1)]);
            return json("成功");
        }
    }
    public function isLikeWork(Request $request){
        $workId=$request->param('work');
        $id=Session::get('user_id');
        $msg=array();
        if($id==null){
            $msg['code']=0;
        }
        else{
            $results=Db::table('work_like')
                ->where([
                    'workId'=>$workId,
                    'userId'=>$id,
                ])
                ->select();
            if(sizeof($results)==0)
               $msg['code']=0;
            else
                $msg['code']=1;
        }
        return json($msg);
    }

    public function likeVideo(Request $request){
        $videoId=$request->param('video');
        $id=Session::get('user_id');
        if($id==null){
            return json("请先登录");
        }
        else{
            $videoLike=new VideoLike;
            $videoLike->videoId=$videoId;
            $videoLike->userId=$id;
            $videoLike->save();
            $video=Db::table('video_work')
                ->where('id',$videoId)
                ->find();

            Db::table('video_work')
                ->where('id',$videoId)
                ->update(['thumbUp'=>($video['thumbUp']+1)]);
            return json("成功");
        }
    }
    public function disLikeVideo(Request $request){
        $videoId=$request->param('video');
        $id=Session::get('user_id');
        if($id==null){
            return json("请先登录");
        }

            Db::table('video_like')
                ->where([
                    'videoId'=>$videoId,
                    'userId'=>$id,
                ])
                ->delete();

            $video=Db::table('video_work')
                ->where('id',$videoId)
                ->find();

            Db::table('video_work')
                ->where('id',$videoId)
                ->update(['thumbUp'=>($video['thumbUp']-1)]);
            return json("成功");

    }

    public function likeEventPic(Request $request){
        $pictureId=$request->param('picture');
        $id=Session::get('user_id');
        if($id==null){
            return json("请先登录");
        }
        $eventLike=new EventLike;
        $eventLike->pictureId=$pictureId;
        $eventLike->userId=$id;
        $eventLike->save();

        $picture=Db::table('event_picture')
            ->where('id',$pictureId)
            ->find();

        Db::table('event_picture')
            ->where('id',$pictureId)
            ->update(['thumbUp'=>($picture['thumbUp']+1)]);
        return json("成功");
    }

    public function disLikeEventPic(Request $request){
        $pictureId=$request->param('picture');
        $id=Session::get('user_id');
        if($id==null){
            return json("请先登录");
        }
        Db::table('event_like')
            ->where([
                'pictureId'=>$pictureId,
                'userId'=>$id,
            ])
            ->delete();

        $picture=Db::table('event_picture')
            ->where('id',$pictureId)
            ->find();

        Db::table('event_picture')
            ->where('id',$pictureId)
            ->update(['thumbUp'=>($picture['thumbUp']-1)]);
        return json("成功");
    }

}