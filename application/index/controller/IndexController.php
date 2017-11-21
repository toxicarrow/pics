<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Image;

class IndexController extends Controller
{
    public function index()
    {
        // Db::execute('insert into user (NAME,ADDRESS,PHONE,AGE) values(?,?,?,?)',["tony","yangzhou","123456",17]);
        //$row = Db::query('select * from user where ID=1');
        //$this->assign("NAME",$row[0]['NAME']);
        //$this->assign("ADDRESS",$row[0]['ADDRESS']);
        //$this->assign("PHONE",$row[0]['PHONE']);
        //$this->assign("AGE",$row[0]['AGE']);
        //dump($row);
        // return "hello";
        return $this->fetch('Index/index');

    }

    public function hello($name = 'Ye')
    {
        return 'hello,' . $name . '!';
    }

    public function getPicInfo($name)
    {
        $img = Image::open("static/img/".$name.".jpg");//实例化图片类对象
        //若当前php文件在Thinkphp的中APP_PATH路径中
        //因为APP_PATH是通过index.php定义和加载的。
        $width = $img->width();
       $height = $img->height();
       $result = $width / $height;
        return $result;
    }
}