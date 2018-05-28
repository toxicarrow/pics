<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/11
 * Time: 16:53
 */

namespace app\admin\controller;

use app\admin\model\WinPicture;
use app\common\Util;
use app\event\model\Event;
use think\Controller;
use think\Db;
use think\Request;
use think\Image;

class EventController extends Controller
{
    public function eventList(){
        $data = Db::name('event')
            ->order('id asc')
            ->paginate(12);
        $this->assign('events', $data);
        return $this->fetch();
    }

    public function showAddEvent(){
        return $this->fetch('addEvent');
    }

    public function addEvent(Request $request){
        $startDate=$request->param('startDate');
        $endDate=$request->param('endDate');
        $image=$request->file('postPicture');
//        $image=Image::open('$imageP');
//        $info=$image->validate(['size'=>9000000,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload');
//        $path=(date('Ymd')).DS.$info->getFileName();
//        $path=Util::$website.DS.'upload'.DS.$path;
//        $start=date_create_from_format("Y-m-d",$startDate);
//        $end=date_create_from_format("Y-m-d",$endDate);
        $path='http://www.igallery.com/upload'.DS.'46fd42cc2ad8d96fa1cabeae8b93a512.jpg';
        $name=$request->param('name');
        $description=$request->param('description');
        $event=new Event;
        $event->startDate=$startDate;
        $event->endDate=$endDate;
        $event->eventPost=$path;
        $event->name=$name;
        $event->description=$description;
        $event->save();
        return $this->success("保存成功！");
    }

    public function delayTime($id){
        $this->assign('id',$id);
        return $this->fetch();
    }
    public function delayEvent(Request $request){
        $num=$request->param('day');
        $id=$request->param('id');
        $event=Event::get($id);
        $endDate=$event['endDate'];
//        return $this->success(' +'.$num.' day');
        $d = date('Y-m-d', strtotime($endDate.' +'.$num.' day'));
//        date_add($endDate,date_interval_create_from_date_string($num." days"));
        $event->endDate=$d;
        $event->save();
        return $this->success("延期成功！");
    }

    public function deleteEvent($id){
        $event=Event::get($id);
        $event->delete();
        return $this->success('删除活动成功！');
    }
    public function endEvent($id){
        $event=Event::get($id);
        $event->endDate=date('Y-m-d');
        $event->status=0;
        $event->save();
        $pictures=Db::table('event_picture')
            ->where('eventId',$id)
            ->order('thumbUp desc')
            ->limit(3)
            ->select();
        $index=1;
        foreach ($pictures as $picture){
            $winPic=new WinPicture;
            $winPic->eventId=$id;
            $winPic->prize=$index;
            $winPic->authorId=$picture['authorId'];
            $winPic->pictureId=$picture['id'];
            $winPic->save();
            $index++;
        }
        return $this->success('结束活动成功！');
    }


}