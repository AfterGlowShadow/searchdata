<?php
namespace app\Models;
use app\validate\LimitValidate;
use app\validate\DataAdd as DataAddValidate;
use app\validate\DataUpdate as DataUpdateValidate;
use think\facade\Request;
use Session;

class Datas extends BaseModel
{
    protected $table = 'data_data';

    //添加单个
    public function AddOne()
    {
        $post=Request::post();
        (new DataAddValidate())->goCheck($post);
        $where['bar_code']=$post['bar_code'];
//        $where['shop_name']=$post['shop_name'];
        $where['goods_number']=$post['goods_number'];
//        $where['supplier']=$post['supplier'];
        $where['status']=1;
        $find=$this->MgetOne($where);
        if(!$find){
            if(array_key_exists("id",$post)){
                unset($post['id']);
            }
            if(!empty($post['question_pic'])){
                foreach ($post['question_pic'] as $key => $value) {
                    if($key<=3){
                        $tempkey=$key+1;
                        $post['question_pic'.$tempkey]=$value['response']['data'];
                    }else{
                        break;
                    }
                }
            }else{
                $post['question_pic1']="";
                $post['question_pic2']="";
                $post['question_pic3']="";
                $post['question_pic4']="";
            }
             if(!empty($post['video'])){
                $post['video']=$post['video']['0']['response']['data'];
            }else{
               $post['video']="";
            }
            if(!empty($post['goods_pic'][0])){
                $post['goods_pic']=$post['goods_pic'][0]['response']['data'];
            }else{
                $post['goods_pic']="";
            }
            $res=$this->MSave($post);
            if($res){
                return $res;
            }else{
                $this->error="添加失败";
                return false;
            }
        }else{
            $res = $this->MUpdate($post, $where);
            $res=$this->MSave($post);
            if($res){
                return $res;
            }else{
                $this->error="添加失败";
                return false;
            }
//            $this->error="存在查询条件相同到数据";
        }
    }
    //删除单个根据id
    public function DeleteOne()
    {
        $post=Request::post();
        if(array_key_exists("id",$post)&&$post['id']!=""){
            $where['status']=1;
            $res=$this->MDelete($post);
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
                $this->error="没有这条数据";
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
        (new DataUpdateValidate())->goCheck($post);
        $where['id'] = $post['id'];
        $where['status'] = 1;
        $cont['bar_code'] = $post['bar_code'];
        $cont['shop_name'] = $post['shop_name'];
        $cont['goods_number'] = $post['goods_number'];
        $cont['supplier'] = $post['supplier'];
        $cont['status'] = 1;
        $find = $this->MgetOne($cont);
        if ($find&&$find['id']!=$post['id']) {
             $this->error="存在相同查询条件的数据";
        }else{
            if(is_array($post['goods_pic'])){
                $post['goods_pic']=$post['goods_pic'][0]['response']['data'];
            }
            if(!empty($post['question_pic'])&&$post['question_pic']!=""){
            foreach ($post['question_pic'] as $key => $value) {
                if($key<=3){
                    $tempkey=$key+1;
                    $post['question_pic'.$tempkey]=$value['response']['data'];
                }else{
                    break;
                }
            }
            }
             if(!empty($post['video'])&&is_array($post['video'])){
                $post['video']=$post['video'][0]['response']['data'];
            }
            $res = $this->MUpdate($post, $where);
            if ($res) {
                return $res;
            } else {
                $this->error = "修改失败";
                return false;
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
        (new LimitValidate())->goCheck($post);
        $mcont['status']=1;
        if(array_key_exists("bar_code",$post)&&$post['bar_code']!=""){
            $mcont['bar_code'] = $post['bar_code'];
        }
        if(array_key_exists("shop_name",$post)&&$post['shop_name']!=""){
            $mcont['shop_name'] = $post['shop_name'];
        }
        if(array_key_exists("goods_number",$post)&&$post['goods_number']!=""){
            $mcont['goods_number'] = $post['goods_number'];
        }
        if(array_key_exists("supplier",$post)&&$post['supplier']!=""){
            $mcont['supplier'] = $post['supplier'];
        }
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
    //批量上传方法
    public function Bulk($data){
        // $res=$this->MSaveList($data);
        // if($res){
        //     return $data;
        // }else{
        //     $this->error="上传失败";
        //     return false;
        // }
        $res3=false;
        $this->startTrans();
        foreach ($data as $key => $value) {
            $res1="";
            $temp['bar_code']=$value['bar_code'];
            $temp['shop_name']=$value['shop_name'];
            $temp['goods_number']=$value['goods_number'];
            $temp['supplier']=$value['supplier'];
            $temp['status']=1;
            $res1=$this->MgetOne($temp);
            if($res1){
                $res2=$this->MDBUpdate('data_data',$temp,$value);
                // $res2=$this->MUpdate($temp,$value);
                // unset($data[$key]);
            }else{
                $res2=$this->MDBAdd('data_data',$value);
                // $res2=$this->MSave($value);
            }
            if(!$res2){
               $res3=true;
               break;
            }
        }
        if($res3){ 
            $this->error="上传失败";
            $this->rollback();
            return false;
        }else{
            if(count($data)==0){
                return true;
            }else{
                // $res=$this->MSaveList($data);
                // if($res){
                    $this->commit();
                    return $data;
                // }else{
                //     $this->rollback();
                //     $this->error="上传失败";
                //     return false;
                // }
            }
        }
    }
    public function GetAllListlike(){
        $post=Request::post();
        (new LimitValidate())->goCheck($post);
        $mcont1[]=['status',"=","1"];
        $mcont[]=['bar_code','like','%'.$post['search'].'%'];
        $mcont[]=['shop_name','like','%'.$post['search'].'%'];
        $mcont[]=['goods_number','like','%'.$post['search'].'%'];
        $mcont[]=['supplier','like','%'.$post['search'].'%'];
//        $where=array_merge($mcont1,$mcont);
        $config['page']=$post['page'];
        $config['list_rows']=$post['list_rows'];
        $order="id desc";
        $table="data_data";

        $res=$this->GetDataQuery($table,$mcont1,$mcont,$config,$order);
        if($res){
            return $res;
        }else{
            $this->error="查询失败";
            return false;
        }
    }
    //导出excel
    public function BulkData(){
        $post=Request::get();
        if(array_key_exists("datalist",$post)&&$post["datalist"]!=""){
            $datalist=explode(",",$post['datalist']);
            $mcont['status']=1;
            $mcont['id']=$datalist;
            $res=$this->MgetSelect($mcont,"id desc");
            $path=DataOutExcel($res);
            if($path){
                return $path;
            }else{
                $this->error="导出失败";
                return false;
               
            }
            // print_r($res);
        }else{
            $this->error="缺少必要参数";
            return false;
        }
    }
}