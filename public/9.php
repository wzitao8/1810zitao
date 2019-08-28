<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class ApiBrush
{
    private $limit = 20;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $redis =  new Redis();
        //使用key来确认用户的身份
        $key = $request->ip();
        $expirekey = $key.'expire';
        if(Redis::exists($expirekey)){
            die(json_encode(Redis::get($expirekey),JSON_UNESCAPED_UNICODE));
        }
        if(Redis::exists($key)){
            Redis::incr($key);
            $count = Redis::get($key);
            if($count>$this->limit){
                Redis::set($expirekey,'too many request please wait 3 minutes');
                Redis::expire($expirekey,10);
                die(json_encode(Redis::get($expirekey),JSON_UNESCAPED_UNICODE));
            }else{
                return $next($request);
            }
        }else{
            Redis::incr($key);
            Redis::expire($key,60);
            return $next($request);
//            return $next($request);
        }

    }
}
