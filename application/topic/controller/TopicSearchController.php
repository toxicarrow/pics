<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/2
 * Time: 14:48
 */
namespace app\topic\controller;
use think\Controller;
use think\Request;
use think\Db;

class TopicSearchController
{
    public function findTopics(Request $request){
        $term = $request->param('q');
        $search=Db::table('topic')
            ->field(['id','topicName'])
            ->where('topicName','Like','%'.$term.'%')
            ->select();
        $result=array();
        foreach ($search as $item){
            $singleResult = array();
            $singleResult['id']=$item['id'];
            $singleResult['text']=$item['topicName'];
            $result[]=$singleResult;
        }
        return json($result);
    }
}