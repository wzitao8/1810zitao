<?php

namespace App\Http\Controllers;

use DemeterChain\C;
use Illuminate\Http\Request;
use App\UserModel;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;
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


    public function md5()
    {
        $name = 'qweqwe';
        $date = base64_encode(serialize($name));
//        var_dump($date);die;
        $url = 'http://www.1810blog.com/md5';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);//设为 TRUE ，将在启用 CURLOPT_RETURNTRANSFER 时，返回原生的（Raw）输出
        curl_setopt($ch, CURLOPT_POSTFIELDS, $date);//数据
        curl_setopt($ch, CURLOPT_URL, 'http://www.1810blog.com/md5');
        //3执行会话
        $info = curl_exec($ch);
        //4结束会话
        curl_close($ch);
//        print_r($info);
    }

    public function pass(){
        $str = "wangzitao";
        $key = 'passpwd';
        $iv = 'qweqweqweqweqwe1';
        $cipher = "AES-128-CBC";
        $env_code = base64_encode(openssl_encrypt($str,$cipher,$key,OPENSSL_RAW_DATA,$iv));
//        var_dump($env_code);die;
        $client = new Client();
        $url = "http://www.1810blog.com/passwork";
        $response = $client->request('post',$url,[
           'body' => $env_code
        ]);
        echo '<hr>';
        echo $response->getBody();
    }

    //非对称加密2
    public function asymm2(){
        $data=[
            'name'=>'王梓韬',
            'sex'=>'男'
        ];
        $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        //获取私钥
        $private=openssl_get_privatekey("file://".storage_path('rsa_private_key.pem'));
        $url="http://www.1810blog.com/asypass";
        openssl_private_encrypt($data,$crypted,$private);
        var_dump($crypted);
        echo '<hr>';
        $crypted=base64_encode($crypted);

        //使用Guzzle传值
        $clinet = new Client();
        $response = $clinet ->request("POST",$url,[
            'body'=>$crypted
        ]);
        echo $response->getBody();
    }

//    练习
    public  function priv(){
        $data="活人禁忌";
        $key="password";
        $method="AES-128-CBC";//密码学方式
        $iv="adminadminadmin1";//非 NULL 的初始化向量
        $a=openssl_get_privatekey("file://".storage_path('rsa_private_key.pem')); //获取秘钥
        openssl_sign($data,$exer,$a);//生成签名
        $url="http://www.1810blog.com/exerci?url=".urlencode($exer);//签名拼接到路由  发送到服务端
        $app=openssl_encrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);// 对称加密
        $clinet= new Client();//实例化 Guzzle
//        Guzzle 发送
        $response=$clinet->request("POST",$url,[
            'body'=>$app
        ]);
        echo $response->getBody();
    }

    public function syntony()
    {
        $key="passwords";
        $method="AES-128-CBC";//密码学方式
        $iv="1212121212121212";//非 NULL 的初始化向量
        $re=$_GET['url']; //路由拼接的数据
        $data=file_get_contents('php://input');
        $data_post=openssl_decrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
        echo "传过来的值".$data_post;
        $asymm=openssl_pkey_get_public("file://".storage_path('rsa_public_key.pem'));//从证书中解析公钥，以供使用
        dump($asymm);
        $result = openssl_verify($data_post,$re,$asymm);//验证签名
        echo "签名验证".$result;
    }

    public function aliyun(){
        return view('/pay/alipay');
    }

    /**
     * 支付宝 手机网站支付
     */
    public function pay()
    {
        $appid = '2016092500595564';
        $ali_gateway = 'https://openapi.alipaydev.com/gateway.do';
        //请求参数;
        $biz_cont = [
            'subject'       => '测试订单'.mt_rand(11111,99999).time(),
            'out_trade_no'  => '1810_'.mt_rand(11111,99999).time(),
            'total_amount'  => mt_rand(1,100) / 100,
            'product_code'  => 'QUICK_WAP_WAY',
        ];
        //公共参数
        $data = [
            'app_id'    => $appid,
            'method'    => 'alipay.trade.app.pay',
            'charset'   => 'utf-8',
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s'),
            'version'   => '1.0',
            'biz_content'   => json_encode($biz_cont)
        ];
        // 1 排序参数
        ksort($data);
        // 2 拼接带签名字符串
        $str0 = "";
        foreach($data as $k=>$v){
            $str0 .= $k . '=' .$v .'&';
        }
        $str = rtrim($str0,'&');
        // 3 私钥签名
        $priv = openssl_get_privatekey("file://".storage_path('priva.pem'));;
        openssl_sign($str,$signature,$priv,OPENSSL_ALGO_SHA256);
        $data['sign'] = base64_encode($signature);
        // 4 urlencode
        $param_str = '?';
        foreach($data as $k=>$v){
            $param_str .= $k.'='.urlencode($v) . '&';
        }
        $param = rtrim($param_str,'&');
        $url = $ali_gateway . $param;
//        echo $url;die;
        //发送GET请求
        header("Location:".$url);
    }

    public function logindo(){
//        echo '123';
        return view('admin.login');
    }



}
