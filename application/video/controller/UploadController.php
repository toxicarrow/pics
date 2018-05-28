<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/8
 * Time: 15:02
 */

namespace app\video\controller;

use app\common\Util;
use app\video\model\VideoWork;
use think\Request;

class UploadController
{
    public function upload(Request $request){
        $video = $request->file('video');
        $id=$request->session("user_id");
        if($id==null){
            $msg=array();
            $msg['msg']="请先登录！";
            return json($msg);
        }

        $title=$request->param('title');
        $description=$request->param('description');
        $date=date("Y-m-d h:ia");
        //保存
        $info = $video->move(ROOT_PATH . 'public' . DS . 'upload');
        //储存url地址
        $path=Util::$website.DS.'upload'.DS. (date('Ymd')).DS.$info->getFileName();
        $videoWork=new VideoWork;
        $videoWork->authorId=$id;
        $videoWork->title=$title;
        $videoWork->description=$description;
        $videoWork->path=$path;
        $videoWork->date=$date;

        $msg=array();
        if($videoWork->save()){
            $mag['msg']='success';
            return json($mag);
        }
        else{
            $mag['msg']='fail';
            return json($mag);
        }
    }
}