<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    'hello/[:name]'=>'index/Index/hello',
    'login'=>'user/Login/login',

    //主页
    'index'=>'index/index/index',
    'exploreIndex'=>'index/index/exploreIndex',
    'topicIndex'=>'index/index/topicIndex',
    'eventIndex'=>'index/index/eventIndex',

    //用户主页
    'userIndex'=>'user/user_info/userInfo',
    //流览其他用户主页
    'user'=>'user/index/user',

//    'work'=>'work/Work/work',

    'picInfo/:name'=>'index/Index/getPicInfo',

    //用户上传摄影作品
    'uploadWork'=>'user/Upload/uploadWork',

    //手机验证码控制器
    'sendVerify/:phone'=>'user/Verify/sendVerification',
    'phoneVerify'=>'user/Verify/verify',

    //注册和登录
    'register'=>'user/User/register',
    'login'=>'user/User/login',
    'logout'=>'user/User/logout',

    'testLogin'=>'user/User/test',

    //关注功能
    'follow'=>'user/user_info/follow',
    'unfollow'=>'user/user_info/unfollow',
    'addGroup'=>'user/user_info/addGroup',
    'changeGroup'=>'user/user_info/changeGroup',
    'deleteGroup'=>'user/user_info/deleteGroup',
    'changeAll'=>'user/user_info/changeAll',
    'followMe'=>'user/user_info/followMe',
    'isFollow'=>'user/user_info/isFollow',

    //作品浏览功能
    'workDetail'=>'work/Work/workDetail',
    'getComments'=>'work/Work/getComments',
    'comment'=>'work/Work/comment',
    'getAuthorIdByWork'=>'work/Work/getAuthorIdByWorkId',

    'searchTopic'=>'topic/topic_search/findTopics',
];
