<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/11
 * Time: 17:34
 */

namespace app\admin\controller;

use app\common\Util;
use app\event\model\Event;
use app\topic\model\Topic;
use think\Controller;
use think\Db;
use think\Request;
use think\Image;


class TopicController extends Controller
{
    public function showAddTopic(){
        return $this->fetch('addTopic');
    }
    public function deleteTopic($id){
        $topic=Topic::get($id);
        $topic->delete();
        return $this->success('删除话题成功！');
    }
    public function topicList(){
        $data = Db::name('topic')
            ->order('id asc')
            ->paginate(12);
        $this->assign('topics', $data);
        return $this->fetch();
    }
    public function addTopic($name){
        $topic=new Topic;
        $topic->topicName=$name;
        $topic->date=date('Y-m-d');
        $topic->save();
        return $this->success('添加话题成功！');
    }
}