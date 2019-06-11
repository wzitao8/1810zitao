<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserModel;
use Illuminate\Support\Facades\Redis;
class CouponController extends Controller
{
    public function index(){
        echo phpinfo();die;
        $key = 'tmp:1810';
        $val = rand(00000,99999);
//        var_dump($val);die;
//        Redis::set($key,$val);		//设置键值
//        Redis::get($key);

        $data = [
            'name'=>'lisi',
            'pwd'=>'123abc'
        ];
        $res = UserModel::insert($data);
        var_dump($res);
    }
    public function lists(){
//        echo '123';
        $url = 'https://www.baidu.com';
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
        curl_exec($ch);
        curl_close($ch);
    }

    public  function  curl(){
        $aiid='wx96a1b4d43f735bfb';
        $appsecret='14b905677c054dc3732ae2fc77499746';
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$aiid}&secret={$appsecret}";
//        初始化
        $ch=curl_init($url);
//        设置参数
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//        执行一个会话
        $data=curl_exec($ch);
//        关闭会话
        curl_close($ch);
        return $data;
    }



    public function curl2(){
       print_r($_POST);
    }

    public function curl3(){
        $post_data='
        {
                 "button":[
                 {    
                      "type":"view",
                      "name":"知乎",
                      "url":"https://www.zhihu.com/"
                  },
                  ]
         }
        ';
        $access_token = $this->curl();
        $data = json_decode($access_token,true);
        $access = $data['access_token'];
//        dd($access);
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access}";
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        $data =curl_exec($ch);
        curl_close($ch);
        print_r($data);
    }
}
