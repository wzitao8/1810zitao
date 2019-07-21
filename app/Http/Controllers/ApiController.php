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
}
