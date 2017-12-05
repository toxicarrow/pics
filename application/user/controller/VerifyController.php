<?php
/**
 * Created by PhpStorm.
 * User: 涵
 * Date: 2017/11/23
 * Time: 23:02
 */

namespace app\user\controller;

class VerifyController
{
    public function __construct()
    {

    }

    function sendVerification($phone){
        $ch = curl_init();
        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, 'https://api.netease.im/sms/sendcode.action');
        //设置头文件的信息作为数据流输出
        curl_setopt($ch, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($ch, CURLOPT_POST, 1);
        //请求头设置
        $headers = $this->getHead();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //设置post数据
        $post_data = array(
            'mobile' => $phone,
        );
        $post_data = http_build_query($post_data);//记住！！！
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        //return $post_data;

        //return $headers;
        //执行命令
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        //trace($data);
        //$err_code = curl_errno($ch);
        //关闭URL请求
        curl_close($ch);
        return $data;
        //显示获得的数据
        // print_r($data);
        //if($data["code"]==200){
//            return array("成功发送");
//        }
//        else{
//            return "CURL Error:";
//        }
    }
    private static function getHead(){
        $key='51bd993b824327d76a0c8567c7280cde';
        $nonce = VerifyController::getRandChar(20);
        $cTime = time();
        $secret = 'c442263efe1d';
        $checkSum = sha1($secret.$nonce.$cTime);
        $headers = array(
            'Content-Type:application/x-www-form-urlencoded',
            'AppKey:'.$key,
            'Nonce:'.$nonce,
            'CurTime:'.$cTime,
            'CheckSum:'.$checkSum
        );
        return $headers;
    }
    private static function getRandChar($length){
        $str = null;
        $strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    public static function verify($mobile,$code ){
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, 'https://api.netease.im/sms/verifycode.action');
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        $post_data = array(
            "mobile" => $mobile,
            "code"=>$code,
        );
        $post_data = http_build_query($post_data);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

        //请求头设置
        $headers = VerifyController::getHead();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $data;
    }
}