<?php

namespace app\http\middleware;
use think\facade\Request;
class AdminCheck
{
    public function handle($request, \Closure $next)
    {
    	 // //下面这一句是 给控制器 传值
      //   $request->hello = 'ThinkPHP';
         
      //   if ($name == 'think') {
      //       return redirect('index/think');
      //   }
 		if(session('user')=="")
		{
			BackData(400,"请登录账号");
      exit;
		}else{
      $user=session('user');
      $param=Request::param();
      if(!$user['authKey']){
        BackData(400,"请登录账号");
        exit;
      }
    }
      return $next($request);
    }
}
