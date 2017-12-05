<?php
namespace app\index\controller;
use app\user\model\service\UserService;
use app\work\model\CategoryService;
use app\work\model\WorkService;
use think\Controller;
use think\Db;
use think\Image;

class IndexController extends Controller
{
    /**
     * @return mixed
     * 首页
     */
    public function index()
    {
        $list=Db::table('work_picture')
            ->field(['articleId','thumbPath','thumbWidth','thumbHeight','title'])
            ->limit(30)
            ->select();
        $this->assign('list',$list);
        return $this->fetch('Index/index');

    }

    /**
     * 发现 界面
     * 发现界面有不同摄影类别的内容推荐
     * 用户推荐
     */
    public function exploreIndex(){
        $categorys=array('人物','宠物','风景','生态','艺术','概念','商业','纪实');
        $listNames=array('peoplePictures','petPictures','scenePictures','naturePictures',
            'artPictures','conceptPictures','commercialPictures','recordPictures');
        $index=0;
        foreach ($categorys as $category) {
            $temp=CategoryService::getCategoryWork(5,$category);
            $this->assign($listNames[$index],$temp);
            $index++;
        }

        $users=Db::table('user')
            ->where('workNum','>',0)
            ->select();
        $size=sizeof($users);
        $max=$size-5;
        $rand=rand(0,$max);
        $userInfos = array();
        for(;$rand<$size;$rand++){
            $userInfos[]=UserService::userInfo($users[$rand]['id']);
        }
        $this->assign('userList',$userInfos);

        return $this->fetch();
    }

    /**
     * 话题 界面
     * 话题搜索，细分
     * 申请添加话题
     */
    public function topicIndex(){
        return $this->fetch();
    }

    /**
     * 活动 界面
     * 活动搜索
     *
     */
    public function eventIndex(){
        return $this->fetch();
    }
}