<?php
namespace app\user\controller;
use app\user\model\service\UserService;
use think\Controller;
use think\Request;
use app\user\model\Follower;
use app\user\model\FollowGroup;
use think\Db;
class UserInfoController extends Controller{
    //进入用户界面
    public function userInfo(){
        trace("用户界面");
        return $this->fetch();
}

    /**
     * 关注;默认分组为其他
     * @param Request $request
     * @return \think\response\Json
     */
    public function follow(Request $request){
        $follower = $request->session("user_id");
        if($follower==null){
            return json("请先登录");
        }
        $targetId = $request->param("target");//关注目标

        $follow = new Follower;
        $follow->id = $targetId;
        $follow->follower = $follower;
        if($request->param("group")!=null){
            $follow->groupName = $request->param("group");
        }
        $follow->date = date("Y-m-d h:i:sa");
        if($follow->save()){
            return json(true);
        }
        else{
            return json(false);
        }
    }

    /**
     * 是否为关注状态
     */
    public function isFollow(Request $request){
        $msg=array();
        $follower = $request->session("user_id");
        $msg['code']=0;
        if($follower==null){
            return json($msg);
        }
        $targetId = $request->param("target");
        $result=Db::table('follower')
            ->where([
                'follower'=>$follower,
                'id'=>$targetId,
                ])
            ->select();
        if($result!=false){
            $msg['code']=1;
        }
//        foreach ($result as $info){
//            if($info['id']==$targetId){
//                $msg['code']=1;
//                break;
//            }
//        }
        return json($msg);
    }
    /**
     * 取消关注
     * @param Request $request
     */
    public function unfollow(Request $request){
        $follower = $request->session("user_id");
        if($follower==null){
            return json("请先登录");
        }
        $targetId = $request->param("target");//取消关注目标
        Db::table('follower')
            ->where([
                'id'=> $targetId,
                'follower'=>$follower,
            ])->delete();

    }

    /**
     * 添加关注分组
     */
    public function addGroup(Request $request){
        $userId = $request->session("user_id");
        if($userId==null){
            return json("请先登录");
        }
        $group = new FollowGroup;
        $groupName = $request->param("groupName");
        $group->groupName = $groupName;
        $group->userId = $userId;
        if($group->save()){
            $this->success("成功");
            return json("成功");
        }
        else{
            return json("失败");
        }
    }


    /**
     * 删除分组
     *
     */
    public function deleteGroup(Request $request){
        $userId = $request->session("user_id");
        if($userId==null){
            return json("请先登录");
        }
        $group = new FollowGroup;
        $groupName = $request->param("groupName");
        $result = Db::table('follow_group')
            ->where([
                'userId'=> $userId,
                'groupName'=>$groupName,
            ])->delete();

        //TODO 测试删除分组内所有关注
        Db::table('follower')
            ->where([
                'id'=>$userId,
                'groupName'=> $groupName,
            ])->delete();
        return json($result);
    }


    /**
     * 改变一个关注的分组 TODO测试
     */
    public function changeGroup(Request $request){
        $follower = $request->session("user_id");
        if($follower==null){
            return json("请先登录");
        }
        $follow = $request->param("follow");
        $group = $request->param("group");
        $result = Db::table('follower')
            ->where([
                'id'=>$follow,
                'follower'=> $follower,
            ])
            ->update(['groupName' => $group]);
        return json($result);
    }

    /**
     * 将一个分组的关注全部转移到另一个
     */
    public function changeAll(Request $request){
        $follower = $request->session("user_id");
        if($follower==null){
            return json("请先登录");
        }
        $origin = $request->param("origin");
        $target = $request->param("target");
        $result = Db::table('follower')
            ->where([
                'follower'=>$follower,
                'groupName'=> $origin,
            ])
            ->update(['groupName' => $target]);
        return json($result);

    }
    /**
     * 得到所有关注自己的人的信息
     */
    public function followMe(Request $request){
        $id= $request->session("user_id");
        if($id==null){
            return json("请先登录");
        }
        $result = Db::table('follower')
            ->where([
                'id'=>$id,
            ])
            ->field('follower')
            ->select();
        $users = array();
        foreach ($result as $item){
            array_push($users,UserService::getUserById($item['follower']));
        }
        return json($users);
    }

    /**
     * 得到一个分组的所有关注
     */

    /**
     * 得到所有分组
     */
}
/**
 * Created by PhpStorm.
 * UserInfo: 涵
 * Date: 2017/11/10
 * Time: 15:58
 */
