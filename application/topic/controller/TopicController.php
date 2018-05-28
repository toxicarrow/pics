<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/4
 * Time: 15:21
 */

namespace app\topic\controller;


use think\Controller;
use think\Db;
use think\Request;

class TopicController extends Controller
{
    public function topic($topicId){
//        return $this->fetch("video_index@video_index/header");
        $topicInfo=Db::table('topic')
            ->where('id',$topicId)
            ->select();

        $this->assign('tagName',$topicInfo[0]['topicName']);
        $this->assign('workNum',$topicInfo[0]['workNum']);
        $works=Db::table('work')
            ->where('topicId',$topicId)
            ->page(1,8)
            ->select();
        $pictures=array();
        foreach ($works as $work) {
            $workPics=Db::table('work_picture')
                ->where('articleId',$work['id'])
                ->select();
            foreach ($workPics as $workPic){
                $pictures[]=$workPic;
            }
        }
        $this->assign('tagPictures',$pictures);
        return $this->fetch('detailTag');
    }
    public function  moreTopic(Request $request,$topicId,$page){
        $topicId=$request->param('topicId');
        $page=$request->param('page');
        $works=Db::table('work')
            ->where('topicId',$topicId)
            ->page($page,8)
            ->select();
        return $works;
    }

}