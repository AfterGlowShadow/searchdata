<?php
namespace app\home\controller;
use app\Models\Daohang as DaohangModel;
use app\Controllers\BaseController;
class Daohang extends BaseController
{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new DaohangModel();
    }
    //判断获取导航信息
    public function getdata(){
        $res=$this->Model->GetDHData();
        Back($res,"查询成功",$this->Model->getError());
    }
    
}

