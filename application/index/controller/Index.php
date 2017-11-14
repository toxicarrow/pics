<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Index extends Controller
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

    public function hello($name = 'Ye'){
        return 'hello,' . $name . '!';
    }
}
