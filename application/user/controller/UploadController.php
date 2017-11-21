<?php
namespace app\user\controller;
use think\Controller;
use think\Image;
use app\user\model\Picture as PictureModel;
use think\Request;
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/20
 * Time: 21:41
 */
class UploadController extends Controller {
    /**
     * @param Request $request
     * @return mixed
     */
    public function picture(Request $request)
    {
        $files = $request->file('image');

//        $info = $files->validate(['size'=>9000000,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload');
//        if($info){
//                //保存路径
//                $item[] = $info->getRealPath();
//            }else{
//                // 上传失败获取错误信息
////                trace("失败");
//                $this->error($files);
//            }
        $item = [];
        $thumbItem = [];
        $id=1;
        foreach($files as $file){
            $info = $file->validate(['size'=>9000000,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload');
            // 移动到框架应用根目录/public/upload/ 目录
            if($info){
                //保存路径
                $item[] = $info->getRealPath();
            }else{
                // 上传失败获取错误信息
//                trace("失败");
                $this->error($file);
            }

            $pictureId = $this->addPicture($file,$info);

        }
        $this->success("文件上传成功".implode('<br/>',$item));
    }

    public function addPicture($file,$info){
        $image = Image::open($file);
        //得到宽,高
        $width = $image->width();
        $height = $image->height();

        $saveName =(date('Ymd')).DS.'thumb_'.$info->getFileName();
        //得到缩略图并保存
        $image->thumb(150,150,Image::THUMB_FIXED);
        $image->save(ROOT_PATH . 'public'.DS.'upload'.DS. $saveName);

        $picture = new PictureModel;
        $picture->width = $width;
        $picture->height = $height;
        $picture->path = $info->getRealPath();
        $picture->thumbPath = ROOT_PATH . 'public'.DS.'upload'.DS. $saveName;
        if($picture->save()){
            return $picture->id;
        }
        else{
            return $picture.getError();
        }
    }
}

