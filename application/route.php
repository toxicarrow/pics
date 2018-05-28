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

    'searchResult'=>'index/search/searchResult',
    //话题和分类界面
    'topic/:topicId'=>'topic/topic/topic',
    'moreTopic'=>'topic/topic/moreTopic',
    'category/:name'=>'work/work/category',
    //主页
    'index'=>'index/index/index',//首页
    'exploreIndex'=>'index/index/exploreIndex',//发现
    'topicIndex'=>'index/index/topicIndex',//话题
    'followingWork'=>'user/following_work/followingWork',//我的关注
    'videoIndex'=>'video/video_index/videoIndex',//短视频

    //活动
    'activeEvent'=>'event/event/activeEvent',//进行中活动
    'endEvent'=>'event/event/endEvent',//已结束活动
    'eventDetail'=>'event/event/eventDetail',//活动界面
    'eventPicture'=>'event/event/eventPicture',//上传活动图片

    'userVideo'=>'video/video_index/userVideo',
    'uploadVideo'=>'video/upload/upload',
    //用户主页
    'user/:id'=>'user/index/user',
    'allWork'=>'user/index/allWork',
    'following'=>'user/index/followings',
    'follower'=>'user/index/followers',
    'editInfo'=>'user/user_info/editInfo',
    'album/:id'=>'user/album/album',
    'deleteAlbumPic'=>'user/album/deletePicture',
    'deleteAlbum'=>'user/album/deleteAlbum',
    'myEventWork'=>'event/event/myEventWork',
    'albumPicture'=>'user/album/addAlbumPicture',
    'newAlbum'=>'user/album/addAlbum',
    'userPhoto'=>'user/user_info/userPhoto',
//    'work'=>'work/Work/work',

    'picInfo/:name'=>'index/Index/getPicInfo',

    //用户上传摄影作品
    'uploadWork'=>'user/Upload/uploadWork',

    //手机验证码控制器
    'sendVerify/'=>'user/Verify/sendVerification',
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

    //点赞功能
    'likeWork'=>'user/like/likeWork',
    'dislikeWork'=>'user/like/dislikeWork',
    'isLikeWork'=>'user/like/isLikeWork',
    'likeVideo'=>'user/like/likeVideo',
    'disLikeVideo'=>'user/like/disLikeVideo',
    'likeEventPic'=>'user/like/likeEventPic',
    'disLikeEventPic'=>'user/like/disLikeEventPic',
    //作品浏览功能
    'workDetail'=>'work/Work/workDetail',
    'getComments'=>'work/Work/getComments',
    'comment'=>'work/Work/comment',
    'getAuthorIdByWork'=>'work/Work/getAuthorIdByWorkId',

    'searchTopic'=>'topic/topic_search/findTopics',

    'preTopic'=>'index/index/preTopic',

    'changeProfileHeader'=>'user/user_info/changeProfileHeader',
    'uploadProfileHead'=>'user/user_info/uploadProfileHead',
    'bgPreview'=>'user/user_info/bgPreview',
    'uploadBg'=>'user/user_info/uploadBg',
    'changePwd'=>'user/user_info/changePwd',
    'changeBaseInfo'=>'user/user_info/changeBaseInfo',

    //后台路由
    'addUser'=>'admin/user/addUser',
    'deleteUser'=>'admin/user/deleteUser',
    'addEvent'=>'admin/event/addEvent',
        'delayEvent'=>'admin/event/delayEvent',
    'deleteEvent'=>'admin/event/deleteEvent',
    'addTopic'=>'admin/topic/addTopic',
    'deleteTopic'=>'admin/topic/deleteTopic',


    'deleteVideo'=>'video/videoIndex/deleteVideo',
];
