<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/16
 * Time: 13:51
 */

namespace app\article\controller;
use think\Controller;


class Article extends Controller
{
    public function article()
    {
        $this->assign("name","叶涵");
        $this->assign("description","最近刚拍的噢");
        $this->assign("userPhotoRoute","__img__/userDefault.jpg");
        $this->assign("imgRoute","__img__/testPic01.jpg");
        $this->assign("commentNum","250");

        // Db::execute('insert into user (NAME,ADDRESS,PHONE,AGE) values(?,?,?,?)',["tony","yangzhou","123456",17]);
        //$row = Db::query('select * from user where ID=1');
        //$this->assign("NAME",$row[0]['NAME']);
        //$this->assign("ADDRESS",$row[0]['ADDRESS']);
        //$this->assign("PHONE",$row[0]['PHONE']);
        //$this->assign("AGE",$row[0]['AGE']);
        //dump($row);
        // return "hello";
        return $this->fetch();

    }
}