<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/12/6
 * Time: 22:16
 */

namespace app\user\controller;


use app\user\model\Album;
use app\user\model\AlbumPicture;
use think\Controller;
use think\Db;
use app\common\Util;
use think\Request;
use think\Session;

class AlbumController extends Controller
{
    public function album($id){
        $albumOrigins=Db::table('album')
            ->where('ownerId',$id)
            ->select();

        $albums=array();
        foreach ($albumOrigins as $albumOrigin){
            $albumId=$albumOrigin['id'];
            $albumPics=Db::table('album_picture')
                ->where('albumId',$albumId)
                ->select();
            $albumOrigin['pictures']=$albumPics;
            $albums[]=$albumOrigin;
        }
        $this->assign('allAlbum',$albums);
//        return json($albums);
        if($id==Session::get('user_id')){
            return $this->fetch('myAlbum');
        }
        return $this->fetch();
    }

    public function addAlbumPicture(Request $request){
        $albumId=$request->param('albumId');
        $userId=Session::get('user_id');
        $albumInfo=Db::table('album')
            ->where('id',$albumId)
            ->select();
        if($userId!=$albumInfo[0]['ownerId']){
            return json("你没有该操作权限！");
        }

        $files = $request->file('image');
        foreach($files as $file){
            $info = $file->validate(['size'=>9000000,'ext'=>'jpg,png,gif,jpeg'])->move(ROOT_PATH . 'public' . DS . 'upload');
            $path=Util::$website.DS.'upload'.DS. (date('Ymd')).DS.$info->getFileName();
           $picture=new AlbumPicture;
           $picture->albumId=$albumId;
           $picture->picturePath=$path;
           $picture->save();
        }
        return json("上传成功");
    }
    public function addAlbum(Request $request){
        $albumName=$request->param('albumName');
        $userId=Session::get('user_id');
        if($userId==null){
            return json("请先登录!");
        }
        $album=new Album;
        $album->albumName=$albumName;
        $album->ownerId=$userId;
        $album->date=date("Y-m-d");
        $album->save();
        return json("成功！");
    }
    public function deletePicture(Request $request){
        $pictureId=$request->param('id');
        $userId=Session::get('user_id');
        $pictureInfo=Db::table('album_picture')
            ->where('id',$pictureId)
            ->select();
        $albumId=$pictureInfo[0]['albumId'];
        $albumInfo=Db::table('album')
            ->where('id',$albumId)
            ->select();
        $albumOwner=$albumInfo[0]['ownerId'];
        //发送请求的id和图册图片的用户id不一致
        if($albumOwner!=$userId){
            return json("请不要搞事情");
        }

        Db::table('album_picture')
            ->where('id',$pictureId)
            ->delete();
        return json("ok");
    }

    public function deleteAlbum(Request $request){
        $albumId=$request->param('id');
        $userId=Session::get('user_id');
        $albumInfo=Db::table('album')
            ->where('id',$albumId)
            ->select();
        $albumOwner=$albumInfo[0]['ownerId'];
        //发送请求的id和图册图片的用户id不一致
        if($albumOwner!=$userId){
            return json("请不要搞事情");
        }

        Db::table('album')
            ->where('id',$albumId)
            ->delete();
        return json("ok");
    }

}