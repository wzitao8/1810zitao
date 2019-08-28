<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DemeterChain\C;
use Illuminate\Support\Facades\Redis;
use Illuminate\Session;

class ApiController extends Controller
{
    public function login(){
        return view('reg.login');
    }
    public function loginDo(){
        $username = $_POST['username'];
        $pwd = $_POST['pwd'];
//        echo $username;
        $str=[
            'username'=>$username,
            'pwd'=>$pwd,
        ];
        $data=json_encode($str,JSON_UNESCAPED_UNICODE);
//        dd($data);
        $key = 'passpwd';
        $iv = 'qweqweqweqweqwe1';
        $cipher = "AES-128-CBC";
        $env_code = base64_encode(openssl_encrypt($data,$cipher,$key,OPENSSL_RAW_DATA,$iv));
//        var_dump($env_code);die;
        $url = "http://pass.1810shop.com/reg/login";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);//设为 TRUE ，将在启用 CURLOPT_RETURNTRANSFER 时，返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_POSTFIELDS, $env_code);//数据
        curl_setopt($ch, CURLOPT_URL, 'http://pass.1810shop.com/reg/login');
        //3执行会话
        $info = curl_exec($ch);
        //4结束会话
        curl_close($ch);
    }
    public function list(Request $request){
        $id = $request->get('id');
//        dd($id);
        return view('reg.list',['id'=>$id]);
    }
    public function ppp($id){
        $uid = $id;
        $redis_key = 'u:token:'.$uid.'';
        Redis::del($redis_key);
        $a  = Redis::get($redis_key);
        if($a==''){
            echo "<script>alert('成功退出-->正在前往登录页面');location.href='http://www.1810api.com/user/login'</script>";
        }
    }

    public function lol(){
        $key = "password";
        $method = "AES-128-CBC";//密码学方式
        $iv = "adminadminadmin1";//非 NULL 的初始化向量
        $re = $_GET['url']; //路由拼接的数据

        $data = file_get_contents('php://input');
//        dd($data);
        $data_post = openssl_decrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
//        dump($data_post);
        $asymm = openssl_pkey_get_public("file://" . storage_path('rsa_public_key.pem'));//从证书中解析公钥，以供使用
//        dd($asymm);
        $result = openssl_verify($data_post, $re, $asymm);//验证签名

        if ($result == 1) {
            echo "验证签名成功，";
            $key = "passwords";
            $method = "AES-128-CBC";//密码学方式
            $iv = "1212121212121212";//非 NULL 的初始化向量
            $a = openssl_get_privatekey("file://" . storage_path('rsa_private_key.pem')); //获取秘钥
//            dd($a);
            openssl_sign($data_post, $exer, $a);//生成签名
//            dd($exer);
            $url = "http://pass.1810shop.com/loginsee?url=".urlencode($exer);//签名拼接到路由  发送到服务端
            $app = openssl_encrypt($data_post, $method, $key, OPENSSL_RAW_DATA, $iv);// 对称加密
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);//设为 TRUE ，将在启用 CURLOPT_RETURNTRANSFER 时，返回原生的（Raw）输出
            curl_setopt($ch, CURLOPT_POSTFIELDS, $app);//数据
            curl_setopt($ch, CURLOPT_URL, 'http://pass.1810shop.com/loginsee?url='.urlencode($exer));
            //3执行会话
            $info = curl_exec($ch);
            //4结束会话
            curl_close($ch);
//        print_r($info);

        } else {
            echo "对不齐，签名验证失败";
        }

    }
}
