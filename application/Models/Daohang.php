<?php
namespace app\Models;
use think\facade\Request;
use Session;

class Daohang extends BaseModel
{
    protected $table = 'data_daohang';

    //查询单个数据
    public function GetDHData()
    {  
        // $where['stauts']=1;
        $where['id']=1;
        $res=$this->MgetOne($where);
        if($res){
            return $res['data'];
        }else{
            $this->error="没有数据";
            return false;
        }
    }
    //修改导航栏
    public function ChangeById(){
        $where['id']=1;
        $post=Request::post();
        if(!empty($post)||array_key_exists("data",$post)&&$post['data']!=""){
            $res=$this->MUpdate($post,$where);
            if($res){
                return $res;
            }else{
                $this->error="修改失败";
                return false;
            }            
        }else{
            $this->error="缺少必要参数data";
            return false;
        }
    }
}