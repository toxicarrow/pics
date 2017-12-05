<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/21
 * Time: 14:34
 */

namespace app\work\model;
use think\Model;

class Work extends Model
{
    protected function getDateAttr($date)
    {
        return date('Y-m-d h:m', $date);
    }

}