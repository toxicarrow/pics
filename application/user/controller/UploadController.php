<?php
namespace app\user\controller;
use app\topic\model\Topic;
use think\Controller;
use think\Db;
use think\Image;
use app\work\model\WorkPicture;
use app\work\model\Work;
use think\Request;
use app\common\Util;
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/20
 * Time: 21:41
 */
class UploadController extends Controller {
    /**
     * 上传作品
     * @param Request $request
     * @return mixed
     */
    public function uploadWork(Request $request)
    {
        $files = $request->file('image');
        $id=$request->session("user_id");
        if($id==null){
            $msg=array();
            $msg['msg']="请先登录！";
            return json($msg);
        }

//        $pictureIds = [];
//        $firstPictureId=$pictureIds[0];
        $topicId=$request->param('topicId');
//        if($topicId==null){
//            $topicId=0;
//        }
        $title=$request->param('title');
        $category=$request->param('category');
        $description=$request->param('description');
        $date=date("Y-m-d h:i:sa");
        $work = new Work;
        $work->authorId=$id;
        $work->date=$date;
        $work->description=$description;
        $work->title=$title;
        $work->category=$category;
        $work->topicId=$topicId;
        if($work->save()){
            $articleId=$work->id;
            $title=$work->title;
            foreach($files as $file){
                $info = $file->validate(['size'=>9000000,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload');
                // 移动到框架应用根目录/public/upload/ 目录
                $pictureId = $this->addPicture($file,$info,$id,$articleId,$title);
//            $pictureIds[]=$pictureId;
            }

            //topic作品数量增加
            if($topicId!=null){
                $workNum = Topic::get($topicId)['workNum'];
                Db::table('topic')
                    ->where('id',$topicId)
                    ->update(['workNum'=>$workNum]);
            }

            $msg=array();
            $msg['msg']="保存成功！";
            return json($msg);
        }
        else{
            $msg=array();
            $msg['msg']="出错！";
            return json($msg);
        }

    }

    /**
     * @param $file 原图片
     * @param $info 原图片存储信息
     * @param $id 作者id
     * @param $articleId 作品Id
     * @param $title 作品标题
     * @return mixed|string
     * 图片制作等比缩放的高为300的缩略图并保存
     * 将图片信息保存
     */
    public function addPicture($file,$info,$id,$articleId,$title){
        $image = Image::open($file);
        //得到宽,高
        $width = $image->width();
        $height = $image->height();

        $thumbPath =(date('Ymd')).DS.'thumb_'.$info->getFileName();
        $originPath=(date('Ymd')).DS.$info->getFileName();
        //得到缩略图并保存
        $thumbHeight = 300;
        $thumbWidth = $width*$thumbHeight/$height;
        $thumbWidth = (int)$thumbWidth;
        $image->thumb($thumbWidth,$thumbHeight,Image::THUMB_FIXED);
        $image->save(ROOT_PATH . 'public' .DS.'upload'.DS. $thumbPath);

        $picture = new WorkPicture;
        $picture->width = $width;
        $picture->height = $height;
        $picture->path = Util::$website.DS.'upload'.DS. $originPath;
        $picture->thumbPath = Util::$website.DS.'upload'.DS. $thumbPath;
        $picture->thumbWidth=$thumbWidth;
        $picture->articleId=$articleId;
        $picture->title=$title;
        $picture->authorId=$id;
        if($picture->save()){
            return $picture->id;
        }
        else{
            return $picture.getError();
        }
    }
}

