<?php

namespace App\Http\Controllers;
use App\Model\RegModel;
use Illuminate\Http\Request;

class RegController extends Controller
{
    public function reg(Request $request){
//        echo '123';
        $post=$request->only(['name','pwd','pwds','email']);
//        dd($post);
        if($post['pwd'] != $post['pwds']){
            echo '确认密码与密码不一致';die;
        }elseif($post['email']==''){
            echo '邮箱格式不正确';die;
        }else{
            $data = [

                'username'=>$post['name'],
                'pwd' => $post['pwd'],
                'email' => $post['email']
            ];
            $res = RegModel::insert($data);
            dd($res);
        }
    }
}
