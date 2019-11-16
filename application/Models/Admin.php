<?php
namespace app\Models;
use app\validate\LoginValidate;
use app\validate\LoginChange as LoginChangeValidate;
use think\facade\Request;

class Admin extends BaseModel
{
    protected $table = 'data_administrator';
    public  function login(){
        $post=Request::post();
        (new LoginValidate())->goCheck($post);
    	$acont['name']=$post['name'];
    	$admin=$this->MgetOne($acont);
    	if(!$admin){
    		$this->error="帐号不存在";
    		return false;	
    	}else{
    		if(md5($post['pwd'])!==$admin['pwd']){
    			$this->error="密码错误";
    			return false;
    		}else{
                $cache['userInfo']=$admin;
                $cache['time']=time();
                $cache['authKey']=md5($admin['id'].$admin['name'].$admin['pwd'].$cache['time']);
                session('admin',$cache,'think');
                return $cache;
    		}
    	}
    }
    //退出登录
    public function logout(){
        Session("adminid","");
        return 1;
    }
    //修改登录密码与账号
    public function UpPwd(){
        $post=Request::post();
        (new LoginChangeValidate())->goCheck($post);
        $acont['id']=Session("adminid");
        $admin=$this->MgetOne($acont);
        if($admin){
            if($admin['name']==$post['name']&&$admin['pwd']==$post['oldpwd']){
                $this->error="密码错误";
                return false;
            }else{
                $data['name']=$post['name'];
                $data['pwd']=$post['pwd'];
                $res=$this->MUpdate($data,$acont);
                if($res){
//                    Session::set("admin","");
                    return $res;
                }else{
                    $this->error="修改失败";
                    return false;
                }
            }
        }else{
            $this->error="网络错误,请重新登录";
            return false;
        }
    }
}