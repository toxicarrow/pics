<?php
namespace app\index\controller;
use app\topic\model\HotTopicService;
use app\user\model\service\UserService;
use app\work\model\CategoryService;
use app\work\model\WorkService;
use think\Controller;
use think\Db;
use think\Image;
use think\Request;

class IndexController extends Controller
{
    /**
     * @return mixed
     * 首页
     */
    public function index()
    {
        $list=Db::table('work_picture')
            ->limit(30)
            ->select();
        $this->assign('list',$list);
        return $this->fetch();
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
    public function topicIndex(Request $request){
        $topicList= Db::name('topic')
                ->paginate(10);
        $this->assign('topicList',$topicList);

//        //日期不一致，检查是否可以晋升正式话题或者被淘汰
//        if($judgeDate!=$today){
//            //日期标签更新
//            Db::table('pre_topic')
//                ->where('id',0)
//                ->update(['date'=>$today]);
//            $allPres=Db::table('pre_topic')
//                ->where('id','NEQ',0)
//                ->select();
//            foreach ($allPres as $pre){
//                $startDate=$pre['date'];
//                $day = $this->diffBetweenTwoDays($startDate,$today);
//                //超过10天
//                if($day>=10){
//                    //超过100赞，增加话题
//                    if($pre['thumbUp']>=100){
//                        Db::table('topic')
//                            ->insert([ 'topicName' => $pre['topicName'],'date'=>date("Y-m-d h:i:sa")]);
//                    }
//                    //删去
//                    Db::table('pre_topic')
//                        ->where('id',$pre['id'])
//                        ->delete();
//                }
//            }
//        }

        if($request->isAjax()){
            return $this->fetch('allTopic');
        }
//        $preTopics=Db::table('pre_topic')->where('id','NEQ',0)->page('1,8')->select();
//
//        $this->assign('preList',$preTopics);

        $HotTopics=HotTopicService::hotTopics(10);
        $this->assign('hotTopicList',$HotTopics);

        $NewTopics=HotTopicService::newTopics(10);
        $this->assign('newTopicList',$NewTopics);
        return $this->fetch();
    }

    /**
     * 求两个日期之间相差的天数
     * (针对1970年1月1日之后，求之前可以采用泰勒公式)
     * @param string $day1
     * @param string $day2
     * @return number
     */
    private function diffBetweenTwoDays ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
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