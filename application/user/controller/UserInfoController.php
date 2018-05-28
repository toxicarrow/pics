<?php
namespace app\user\controller;
use app\common\Util;
use app\user\model\service\UserService;
use think\Controller;
use think\Request;
use app\user\model\Follower;
use app\user\model\FollowGroup;
use think\Db;
use think\Session;
use think\Image;
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
        $follow->date = date("Y-m-d h:i:sa");
        if($follow->save()){
            return json(true);
        }
        else{
            return json(false);
        }
    }

    public function changeProfileHeader(Request $request){
        $id=Session::get('user_id');
        $user=UserService::getUserById($id);
        $this->assign('id',$id);
        $this->assign('path',$user['profilePhoto']);
        return $this->fetch('changeHead');
    }

    public function bgPreview(Request $request){
        $id=Session::get('user_id');
        $user=UserService::getUserById($id);
        $this->assign('id',$id);
        $this->assign('path',$user['bgImg']);
        return $this->fetch('bgUpload');
    }
    public function uploadBg(Request $request){
        $id=Session::get('user_id');
        $msg=array();
        $msg['state']=200;
        $file=$request->file('avatar_file');
        $data=$request->param('avatar_data');
        $image = Image::open($file);
        $data = json_decode(stripslashes($data));
        $tmp_img_w = $data-> width;
        $tmp_img_h = $data -> height;
        $src_x = $data -> x;
        $src_y = $data -> y;

        $image->crop($tmp_img_w,$tmp_img_h,$src_x,$src_y);
        $saveName = $request->time() . '.png';
        $image->save(ROOT_PATH . 'public/upload/' . $saveName);
        Db::table('user')
            ->where('id',$id)
            ->update(['bgImg'=>Util::$website.'/upload/'. $saveName]);
        return json($msg);
    }
    public function uploadProfileHead(Request $request){
        $id=Session::get('user_id');
        $msg=array();
        $msg['state']=200;
        $file=$request->file('avatar_file');
        $data=$request->param('avatar_data');
        $src=$request->param('avatar_src');
////        $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
        $image = Image::open($file);
        $data = json_decode(stripslashes($data));
        $src_img_w=$image->width();
        $src_img_h=$image->height();
        $tmp_img_w = $data-> width;
        $tmp_img_h = $data -> height;
        $src_x = $data -> x;
        $src_y = $data -> y;
        $dst_img_w = 220;
        $dst_img_h = 220;

//$msg['obj']=$data;
        $image->crop($tmp_img_w,$tmp_img_h,$src_x,$src_y);
        $saveName = $request->time() . '.png';
        $image->save(ROOT_PATH . 'public/upload/' . $saveName);
        Db::table('user')
            ->where('id',$id)
            ->update(['profilePhoto'=>Util::$website.'/upload/'. $saveName]);
        return json($msg);
    }
    public function editInfo($id){
        $currentId=Session::get('user_id');
        if($currentId==null||$currentId!=$id){
            return "你没有访问的权限！";
        }
        $user=UserService::getUserById($id);
        $this->assign('userName',$user['name']);
        $this->assign('userDescription',$user['description']);
        $this->assign('profileHead',$user['profilePhoto']);
        return $this->fetch();
    }
    public function userPhoto(Request $request){
        $id=Session::get('user_id');
        $user=UserService::getUserById($id);
        $msg=array();
        $msg['path']=$user['profilePhoto'];
        return json($msg);
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

    public function changeBaseInfo(Request $request){
        $msg=array();
        $id=Session::get('user_id');
        if($id==null){
            $msg['code']=0;
            $msg['msg']="请先登录！";
            return json($msg);
        }
        $name=$request->param('name');
        if($name==""){
            $msg['code']=0;
            $msg['msg']="昵称不得为空！";
            return json($msg);
        }
        $description=$request->param('description');
        Db::table('user')
            ->where('id',$id)
            ->update([
                'name'=>$name,
                'description'=>$description,
            ]);
        $msg['code']=1;
        $msg['msg']="修改成功！";
        return json($msg);
    }

    public function changePwd(Request $request){
        $msg=array();
        $id=Session::get('user_id');
        if($id==null){
            $msg['code']=0;
            $msg['msg']="请先登录！";
            return json($msg);
        }
        $originPwd=$request->param('originPwd');
        $newPwd=$request->param('newPwd');
        $confirmPwd=$request->param('confirmPwd');
        if($originPwd==""){
            $msg['code']=0;
            $msg['msg']="请输入原密码！";
            return json($msg);
        }

        if($newPwd!=$confirmPwd){
            $msg['code']=0;
            $msg['msg']="两次密码输入不一致！";
            return json($msg);
        }

        $user=UserService::getUserById($id);
        $salt = $user['salt'];
        $rightPwd = $user['password'];
        $currentPwd = sha1(sha1($originPwd).$salt);
        if($rightPwd==$currentPwd){
            $newSalt=Util::getRandChar(10);
            $newPassword=sha1(sha1($newPwd).$newSalt);
            Db::table('user')
                ->where('id',$id)
                ->update([
                    'salt'=>$newSalt,
                    'password'=>$newPassword,
                ]);
            $msg['code']=1;
            $msg['msg']="修改成功！";
            return json($msg);
        }
        $msg['code']=0;
        $msg['msg']="原密码错误！";
        return json($msg);
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
