<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/3
 * Time: 20:52
 */

namespace app\work\model;
use app\work\model\Work;
use think\Db;

class WorkService
{
    public static function getWorkInfoById($id){
        $work = Work::get($id);
        return $work;
    }
    public static function getOtherWork($authorId,$currentWorkId){
        $result=Db::table('work')
            ->where('authorId',$authorId)
            ->where('id','NEQ',$currentWorkId)
            ->order('thumbUp desc')
            ->limit(5)
            ->select();
        return $result;
    }

    public static function getHotWork(){
        $result=Db::table('work')
            ->order('thumbUp desc')
            ->limit(5)
            ->select();
        return $result;
    }

}