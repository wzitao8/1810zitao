<?php

namespace App\Http\Controllers;
use App\Model\RegModel;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Mail;
class RegController extends Controller
{
    public function reg(Request $request){
//        echo '123';
        $post=$request->only(['name','pwd','pwds','email']);
//        dd($post);
        $email = $request->input('email');
        $u = RegModel::where(['email'=>$email])->first();
        if($u!=''){
            echo '邮箱已存在';die;
        }
        if($post['pwd'] != $post['pwds']){
            echo '确认密码与密码不一致';
        }else{
            $pwd = password_hash($post['pwd'],PASSWORD_BCRYPT);
//            var_dump($pwd);die;
            $data = [
                'username'=>$post['name'],
                'pwd' => $pwd,
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
//        header("Access-Control-Allow-Origin:*");
//        echo '123';
        $name = $request->input('name');
        $pwd = $request->input('pwd');

//        dd($post);
        $a = RegModel::where(['username'=>$name])->first();
//        dd($a);
        if($a){
            if(password_verify($pwd,$a->pwd)){
//                echo 'ok';
                $token = substr(md5($a->id.Str::random(8).mt_rand(11,99999)),10,10);
//                var_dump($token);
                $redis_key = 'u:token:'.$a->id.'';
//                echo $redis_key;
                Redis::set($redis_key,$token);
                Redis::setTimeout($redis_key,10);

                 $respoer = [
                    'error' =>0,
                     'msg' =>'ok',
                     'data'=> [
                         'token'=>$token,
                         'id'=>$a->id
                     ]
                 ];
                 return $respoer;
            }else{
                echo '登陆失败';
            }
        }else{
            echo '用户名不存在';
        }
    }
    public function locat(){
//        echo '123';
//        header("Access-Control-Allow-Origin:*");
        $key = 'username';
        $a = Redis::get($key);
//        dd($a);
        return view('admin/admin',compact('a'));
    }
    public function server(Request $request){
        $token = $request ->input('token');
        $id = $request -> input('id');
//        echo $token;
        $redis_key = 'u:token:'.$id;
//        echo $redis_key;
        $num = Redis::incr($redis_key);
//        echo $num;die;

        $cache_token = Redis::get($redis_key);
//        echo $cache_token;
        if($cache_token !=$token){
            $respoer = [
                'error' =>40001,
                'msg' =>'taken验证有误',
            ];
            return $respoer;
        }
        $u = RegModel::where(['id'=>$id])->first();
        if($u){
            $respoer = [
                'error' =>0,
                'msg' =>'ok',
                'data'=> [
                    'u'=>$u
                ]
            ];
            return $respoer;
        }else{
            $respoer = [
                'error' =>50006,
                'msg' =>'无法获取用户信息',
            ];
            return $respoer;
        }
    }
    public function send(Request $request){

//        $email = $request ->input('email');
        $email = "994754467@qq.com";
        $code=rand(1111,9999);
        Mail::send('reg/Password',['code'=>$code,'name'=>$email],function($message)use($email){
            $message->subject('找回密码');
            $message->to($email);
        });
        //echo $code;
        return $code;
//        $u = RegModel::where(['email'=>$email])->first();
//        if($u){
//           // echo $email;die;
//
//        }else{
//            $respoer = [
//                'error' =>50007,
//                'msg' =>'获取不到邮箱',
//            ];
//            return $respoer;
//        }
//
    }


}
