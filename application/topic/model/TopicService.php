<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/2
 * Time: 22:51
 */

namespace app\topic\model;
use think\Db;


class TopicService
{
    /**
     * @param $id
     * @return mixed
     * 根据话题id得到话题名称
     */
    public static function getTopicNameById($id){
        $topicName=Db::table("topic")
            ->field(['topicName'])
            ->where('id',$id)
            ->select();
        return $topicName[0]['topicName'];
    }

    /**
     * @param $name
     * @return mixed
     * 根据话题id得到话题名称
     */
    public static function getTopicIdByName($name){
        $topicId=Db::table("topic")
            ->field(['id'])
            ->where('topicName',$name)
            ->select();
        return $topicId[0]['topicName'];
    }
}