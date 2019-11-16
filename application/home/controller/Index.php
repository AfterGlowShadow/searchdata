<?php
namespace app\home\controller;
use app\Controllers\BaseController;
class Index extends BaseController
{
    //判断获取导航信息
    public function index(){
        if(isMobile()){
            return redirect("http://www.norhoraftersales.com/h5");
        }else{
            return redirect("http://www.norhoraftersales.com/pc");
        }
    }
    
}

