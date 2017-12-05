<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/4
 * Time: 18:40
 */

namespace app\work\model;


use think\Db;

class CategoryService
{
    public static function getCategoryWork($size,$category){
        $works=Db::table('work')
            ->where('category',$category)
            ->order('thumbUp desc')
            ->limit($size)
            ->select();
        $result=array();
        foreach ($works as $work){
            $pictures=Db::table('work_picture')
                ->where('articleId',$work['id'])
                ->select();
            foreach ($pictures as $picture){
                $result[]=$picture;
            }
        }
        return $result;
    }
}