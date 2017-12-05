<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/23
 * Time: 23:09
 */
namespace app\common;
class Util
{
    public static $website="http://www.igallery.com";
    static public function getRandChar($length){
        $str = null;
        $strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

}