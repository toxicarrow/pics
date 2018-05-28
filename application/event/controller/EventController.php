<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/9
 * Time: 13:59
 */

namespace app\event\controller;


use app\event\model\Event;
use app\event\model\EventPicture;
use think\Controller;
use think\Db;
use think\Image;
use think\Request;
use think\Session;
use app\common\Util;

class eventController extends Controller
{
    public function activeEvent(){
        $events=Db::table('event')
            ->where('status',1)
            ->select();
        $this->assign('allEvent',$events);
        return $this->fetch();
    }

    public function endEvent(){
        $events=Db::table('event')
            ->where('status',0)
            ->select();
        $this->assign('allEvent',$events);
        return $this->fetch();
    }

    public function  eventDetail(Request $request){
        $eventId=$request->param('event');
        $eventInfo=Db::table('event')
            ->where('id',$eventId)
            ->select();
        $eventInfo=$eventInfo[0];
        $eventStatus=$eventInfo['status'];
        if($eventStatus==1){
            return $this->activeEventDetail($eventInfo);
        }
        else{
            return $this->endEventDetail($eventInfo);
        }
    }

    private function activeEventDetail($eventInfo){
        $id=Session::get('user_id');
        $event=array();
        $event[]=$eventInfo;
        $this->assign('event',$event);
        $pictures=Db::table('event_picture')
            ->where('eventId',$eventInfo['id'])
            ->select();
        $pictureList=array();
        foreach ($pictures as $picture){
            if($id==null){
                $picture['likeStyle']='glyphicon-heart-empty';
            }
            else if($this->isLike($picture['id'],$id)==0){
                $picture['likeStyle']='glyphicon-heart-empty';
            }
            else{
                $picture['likeStyle']='glyphicon-heart';
            }
            $pictureList[]=$picture;
        }
        $this->assign('pictures',$pictureList);
        return $this->fetch('activeDetail');
    }

    private function isLike($pictureId,$id){
        $results=Db::table('event_like')
            ->where([
                'pictureId'=>$pictureId,
                'userId'=>$id,
            ])
            ->select();
        if(sizeof($results)==0)
            return 0;
        else
            return 1;
    }
    private function endEventDetail($eventInfo){
        $id=Session::get('user_id');
        $event=array();
        $event[]=$eventInfo;
        $this->assign('event',$event);
        $pictures=Db::table('event_picture')
            ->where('eventId',$eventInfo['id'])
            ->select();
        $pictureList=array();
        //设置Like空心还是实心
        foreach ($pictures as $picture){
            if($id==null){
                $picture['likeStyle']='glyphicon-heart-empty';
            }
            else if($this->isLike($picture['id'],$id)==0){
                $picture['likeStyle']='glyphicon-heart-empty';
            }
            else{
                $picture['likeStyle']='glyphicon-heart';
            }
            $pictureList[]=$picture;
        }
        $this->assign('pictures',$pictureList);

        $winInfos=Db::table("win_picture")
            ->where('eventId',$eventInfo['id'])
            ->order("prize asc")
            ->select();
        $winners=array();
        foreach ($winInfos as $winInfo){
            $pictureInfo=Db::table('event_picture')
                ->where('id',$winInfo['pictureId'])
                ->select();
            $winInfo['path']=$pictureInfo[0]['path'];
            $userInfo=Db::table('user')
                ->where('id',$winInfo['authorId'])
                ->select();
            $winInfo['userName']=$userInfo[0]['name'];
            $winInfo['profilePhoto']=$userInfo[0]['profilePhoto'];
            $winners[]=$winInfo;

        }
        $this->assign('winners',$winners);
        return $this->fetch('endDetail');
    }

    public function eventPicture(Request $request){
        $user_id=Session::get('user_id');
        $image = $request->file('image');

        $info = $image->move(ROOT_PATH . 'public' . DS . 'upload');

        $eventId=$request->param('eventId');
        $title=$request->param('title');
        $path=Util::$website.DS.'upload'.DS. (date('Ymd')).DS.$info->getFileName();

        $imageFile = Image::open($image);
        //得到宽,高
        $width = $imageFile->width();
        $height = $imageFile->height();

        $thumbPath =(date('Ymd')).DS.'thumb_'.$info->getFileName();
        //得到缩略图并保存
        $thumbHeight = 300;
        $thumbWidth = $width*$thumbHeight/$height;
        $thumbWidth = (int)$thumbWidth;
        $imageFile->thumb($thumbWidth,$thumbHeight,Image::THUMB_FIXED);
        $imageFile->save(ROOT_PATH . 'public' .DS.'upload'.DS. $thumbPath);

        $picture=new EventPicture;
        $picture->eventId=$eventId;
        $picture->title=$title;
        $picture->path=$path;
        $picture->authorId=$user_id;
        $picture->thumbPath=Util::$website.DS.'upload'.DS. $thumbPath;
        $picture->save();
        $event=Event::get($eventId);
        Db::table('event')
            ->where('id',$eventId)
            ->update(['pictureNum'=>($event['pictureNum']+1)]);
        return json("success");
    }

    public function myEventWork(Request $request){
        $userId=$request->param('id');
        $pictures=Db::table('event_picture')
            ->where('authorId',$userId)
            ->select();
        $works=array();
        foreach ($pictures as $picture){
            $event=Event::get($picture['eventId']);
            $picture['eventName']=$event['name'];
            $works[]=$picture;
        }
        $this->assign('works',$works);
        return $this->fetch('myEventWork');
    }

}