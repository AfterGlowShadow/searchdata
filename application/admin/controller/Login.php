<?php
namespace app\admin\controller;
use app\Models\Admin as AdminModel;
class Login extends Base
{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new AdminModel();
    }
    //判断登录
    public function login(){
        $res=$this->Model->login();
        if($res){
            $data['code']=200;
            $data['msg']="登录成功";
            $data['token']=$res['authKey'];
            $data['username']=$res['userInfo']['username'];
        }else{
            $data['code']=400;
            $data['msg']="登录失败";
            $data['token']="";
            $data['username']="";
            // echo json_encode($data);
        }
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
        // Back($res,"登陆成功",$this->Model->getError());
    }
    //退出登录
    public function logout(){
        $res=$this->Model->logout();
        Back($res,"退出成功",$this->Model->getError());
    }
    //修改登录密码与账号
    public function UpPwd(){
    	$res=$this->Model->UpPwd();
        Back($res,"修改成功",$this->Model->getError());
    }
}
