<?php

namespace app\http\middleware;
use think\facade\Request;
class UserCheck
{
    public function handle($request, \Closure $next)
    {
    	 // //下面这一句是 给控制器 传值
      //   $request->hello = 'ThinkPHP';
         
      //   if ($name == 'think') {
      //       return redirect('index/think');
      //   }
      print(session('admin'));
      print(session('user'));
      echo 'ta';
      exit;
 		if(session('admin')=="")
		{
			BackData(400,"请登录账号");
      exit;
		}else{
      $user=session('admin');
      $param=Request::param();
      if(!$user['authKey']){
        BackData(400,"请登录账号");
        exit;
      }
    }
      return $next($request);
    }
}
