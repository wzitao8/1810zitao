<?php

namespace App\Http\Controllers;
use App\Model\RegModel;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
class RegController extends Controller
{
    public function reg(Request $request){
//        echo '123';
        $post=$request->only(['name','pwd','pwds','email']);
//        dd($post);
        if($post['pwd'] != $post['pwds']){
            echo '确认密码与密码不一致';
        }elseif($post['email']==''){
            echo '邮箱格式不正确';
        }else{
            $data = [
                'username'=>$post['name'],
                'pwd' => $post['pwd'],
                'email' => $post['email']
            ];
            $res = RegModel::insert($data);
            if($res){
                echo '1';
            }else{
                echo '2';
            }
        }
    }

    public function login(Request $request){
        header("Access-Control-Allow-Origin:*");
//        echo '123';
        $post=$request->only(['name','pwd']);
//        dd($post);
        $where = [
            'username'=>$post['name'],
            'pwd'=>$post['pwd']
        ];
        $res = DB::table('reguser')->where($where)->first();
        if($res==''){
            echo '1';
        }else{
            $key = 'username';
            $username = $post['name'];
            Redis::set($key,$username);		//设置键值
            $a = Redis::get($key);
//            dd($a);
        }
    }
    public function locat(){
//        echo '123';
//        header("Access-Control-Allow-Origin:*");
        return view('admin/admin');
    }
}
