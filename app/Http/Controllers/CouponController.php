<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserModel;
use Illuminate\Support\Facades\Redis;
class CouponController extends Controller
{
    public function index(){
//        echo phpinfo();die;
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
}
