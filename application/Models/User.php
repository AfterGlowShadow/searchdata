<?php
namespace app\Models;
use app\validate\LimitValidate;
use app\validate\LoginValidate;
use app\validate\ChangeUser as ChangeUserValidate;
use think\facade\Request;
use Session;

class User extends BaseModel
{
    protected $table = 'data_user';

    //添加单个
    public function AddOne()
    {
        $post=Request::post();
        (new LoginValidate())->goCheck($post);
        $post['pwd']=$post['pwd'];
        $where['name']=$post['name'];
        $where['status']=1;
        $find=$this->MgetOne($where);
        if(!$find){
            $post['createtime']=date("Y-m-d H:i:s",time());
            if(array_key_exists("id",$post)){
                unset($post['id']);
            }
            $res=$this->MSave($post);
            if($res){
                return $res;
            }else{
                $this->error="添加失败";
                return false;
            }
        }else{
            $this->error="存在同名用户";
        }
    }
    //删除单个根据id
    public function DeleteOne()
    {
        $post=Request::post();
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $where['status']=1;
            $res=$this->MFDelete($post);
            if($res){
                return $res;
            }else{
                $this->error="删除失败";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //查询单个数据
    public function GetById()
    {
        $post=Request::post();
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $post['status']=1;
            $res=$this->MgetOne($post);
            if($res){
                return $res;
            }else{
                $this->error="没有这个用户";
                return false;
            }
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
    //修改单个数据
    public function ChangeById()
    {
        $post = Request::post();
        (new ChangeUserValidate())->goCheck($post);
        $post['pwd'] = $post['pwd'];
        $where['id'] = $post['id'];
        $where['status'] = 1;
        $cont['name'] = $post['name'];
        $cont['status'] = 1;
        $find = $this->MgetOne($cont);
        if (!$find) {
            $res = $this->MUpdate($post, $where);
            if ($res) {
                return $res;
            } else {
                $this->error = "修改失败";
                return false;
            }
        }else{
            if($find['id']!=$post['id']){
                $this->error="存在同名用户";
                return "";
            }else{
                $res = $this->MUpdate($post, $where);
                if ($res) {
                    return $res;
                } else {
                    $this->error = "修改失败";
                    return false;
                }
            }
        }
    }
    //有序查询所有数据
    public function GetSort()
    {
        $mcont['status']=1;
        $res=$this->MgetSelect(array($mcont),"id desc");
        if($res){
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //分页获取数据
    public function GetAllList()
    {
        $post=Request::post();
        // echo "post:";
        // print_r($post);
        // $post=Request::get();
        // echo "get:";
        // print_r($post);
        // $post=Request::param();
        // echo "param:";
        // print_r($post);
        // exit;
        (new LimitValidate())->goCheck($post);
        $mcont['status']=1;
        $config['page']=$post['page'];
        $config['list_rows']=$post['list_rows'];
        $order="id desc";
        $res=$this->MgetAll($mcont,$config,$order);
        if($res){
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    public  function login(){
        $post=Request::post();
        (new LoginValidate())->goCheck($post);
        $acont['name']=$post['name'];
        $acont['status']=1;
        $admin=$this->MgetOne($acont);
        if(!$admin){
            $this->error="帐号不存在";
            return false;   
        }else{
            if(md5($post['pwd'])!=$admin['pwd']){
                $this->error="密码错误";
                return false;
            }else{
                // session("userid",$admin['id'],'think');
                $cache['userInfo']=$admin;
                $cache['time']=time();
                $cache['authKey']=md5($admin['id'].$admin['name'].$admin['pwd'].$cache['time']);
                session('user',$cache,'think');
                $resdata['token']=$cache['authKey'];
                $resdata['username']=$admin['username'];
                return $resdata;
            }
        }
    }
    //退出登录
    public function logout(){
        Session("userid","");
        return 1;
    }
    //修改登录密码与账号
    public function UpPwd(){
        $post=Request::post();
        (new LoginChangeValidate())->goCheck($post);
        $acont['id']=Session("userid");
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