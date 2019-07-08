<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KeyController extends Controller
{
    public function b(){
        $api_key = '1810a_2019';
        echo '<pre>';print_r($_GET);echo '</pre>';
        //验签 sign字段不参与验签
        $data = $_GET;
        $sign = $data['sign'];
        unset($data['sign']);

        //验签
        // 1 排序
        ksort($data);

        // 2 拼字符串 key=value...
        $stra = '';
        foreach ($data as $k=>$v){
            $stra .= $k . '=' .$v . '&';
        }
        $stra = rtrim($stra,'&');
        $stringSignTemp = $stra . '&key=' . $api_key;
        echo '带签名字符串： '.$stringSignTemp;echo '</br>';

        // 计算签名 strtoupper(md5($stringSignTemp))
        $sign2 = strtoupper(md5($stringSignTemp));
        echo '签名： '.$sign2;
        if($sign==$sign2){
            echo '验签通过';
        }else{
            echo "验签失败";
        }
    }
}
