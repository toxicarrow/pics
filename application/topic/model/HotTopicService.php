<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/4
 * Time: 15:21
 */

namespace app\topic\model;
use think\Db;

class HotTopicService
{
    public static function hotTopics($size){
        $result=Db::table('topic')
            ->order('workNum desc')
            ->limit($size)
            ->select();
        return $result;
    }

    public static function newTopics($size){
        $result=Db::table('topic')
            ->order('date desc')
            ->limit($size)
            ->select();
        return $result;
    }
}