<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/16
 * Time: 13:51
 */

namespace app\work\controller;
use app\topic\model\HotTopicService;
use think\Controller;
use think\Request;
use think\Db;
use app\user\model\service\UserService;
use app\work\model\WorkService;
use app\topic\model\TopicService;
use think\Session;
use app\work\model\Comment;


class WorkController extends Controller
{
    public function work()
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

    /**
     * @param Request $request 里面传递参数id，表示作品id
     * @return mixed
     * 进入某个作品详细信息页面
     *  view:workDetail.html
     */
    public function workDetail(Request $request){
        $id=$request->param('id');
        $work=WorkService::getWorkInfoById($id);//根据id得到作品信息
        $commentList =$this->getComments($id);
        $this->assign('commentList', $commentList);

        //如果是ajax请求，则返回分页的页面，实现局部刷新
        if($request->isAjax()){
            return $this->fetch('getComments');
        }

        $authorId=$work['authorId'];
        $userName=UserService::getUserById($authorId)['name'];//根据作品信息里用户id得到作者名称

        $this->assign('workId',$id);
        $this->assign('thumbUp',$work['thumbUp']);
        $this->assign('category',$work['category']);//摄影类别
        $this->assign('authorName',$userName);//作者名称
        $this->assign('title',$work['title']);//作品标题
        $this->assign('description',$work['description']);//作品简介
        if($work['topicId']!=0){
            $href="http://www.igallery.com/topic/id/".$work['topicId'];//话题界面
            $topicWithHref="<a href='".$href."'>";
            $topicWithHref=$topicWithHref."<span class=\"label label-primary\">".TopicService::getTopicNameById($work['topicId'])."</span>"."</a>";
            $this->assign('topic',$topicWithHref);//作品的话题
        }
        else{
            $this->assign('topic','无');
        }

        //右侧作者其他作品（按点赞数排名推荐）
        $workList=WorkService::getOtherWork($authorId,$id);
        $this->assign('workList',$workList);

        //右侧热门作品（按点赞数排名推荐）
        $hotWorkList=WorkService::getHotWork();
        $this->assign('hotWorkList',$hotWorkList);

        //右侧热门话题（按话题内作品数排名推荐）
        $hotTopicList=HotTopicService::hotTopics(5);
        $this->assign('hotTopicList',$hotTopicList);


        // 得到作品内所有图片的信息
        //信息为原图片路径，缩略图路径，缩略图高度和宽度
        $pictureList=Db::table('work_picture')
            ->field(['path','thumbPath','thumbWidth','thumbHeight','title'])
            ->where('articleId',$id)
            ->select();
        $this->assign('list',$pictureList);

        return $this->fetch();
    }

    /**
     * @param $id
     * @return \think\Paginator
     * 得到评论信息
     * 分页
     */
    private function getComments($id){
        $commentList = Db::name('comment')
            ->where('workId',$id)->paginate(5);
        $index=0;
        foreach ($commentList as $comment){
            $reviewerId=$comment['reviewerId'];
            $reviewerName=UserService::getUserById($reviewerId)['name'];
            $comment['reviewerName']=$reviewerName;
            $commentList[$index]=$comment;
            $index++;
        }
        return $commentList;
    }

    /*
     * 发表评论
     */
    public function comment(Request $request){
        $msg=array();
        $review=$request->param('text');
        if($review==""){
            $msg['msg']="评论不得为空！";
            return json($msg);
        }
        if(!Session::has('user_id')){
            $msg['msg']="请先登录！";
            return json($msg);
        }
        $id=Session::get('user_id');
        $returnedId=0;
        if($review[0]=='@'){
            $pos=stripos($review,'：');
            if($pos==false){
                $returnedId=0;
            }
            else{
                $name=substr($review,1,$pos-1);
                $user=Db::table('user')
                    ->where('name',$name)
                    ->select();
                if($user==false){
                    $returnedId=0;
                }
                else{
                    $returnedId=$user[0]['id'];
                }
            }
        }

        $comment=new Comment;
        $workId=$request->param('workId');
        $comment->workId=$workId;
        $comment->reviewerId=$id;
        $comment->review=$review;
        $comment->returnedId=$returnedId;
        $comment->time=date("Y-m-d h:ia");
        if($comment->save()){
            //作品评论数+1
            $work = WorkService::getWorkInfoById($workId);
            $commentNum=$work['commentNum']+1;
            Db::table('work')
                ->where('id',$workId)
                ->update(['commentNum'=>$commentNum]);

            $msg['msg']="评论成功！";
            $msg['code']=200;
            return json($msg);
        }
        else{
            $msg['msg']="抱歉，服务器好像发生错误了:(";
            return json($msg);
        }
    }

    /*
     * 根据作品id得到作者id
     */
    public function getAuthorIdByWorkId(Request $request){
        $workId=$request->param('workId');
        $work=WorkService::getWorkInfoById($workId);
        $result = array();
        $result['authorId']=$work['authorId'];
        return json($result);
    }
}