<?php

namespace app\http\middleware;

class Check
{
    public function handle($request, \Closure $next)
    {
      if(isMobile()){
        // $url=explode(".",$_SERVER['REQUEST_URI']);
        return redirect($_SERVER['SERVER_NAME']."/h5".$_SERVER['REQUEST_URI']);
      }else{
        return redirect($_SERVER['SERVER_NAME']."/pc".$_SERVER['REQUEST_URI']);
      }
    }
}
