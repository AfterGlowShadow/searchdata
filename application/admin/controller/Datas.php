<?php
namespace app\admin\controller;
use app\Controllers\BaseController;
use app\Models\Datas as DatasModel;
class Datas extends BaseController{
    public $Model;
    public function initialize(){
        parent::initialize();
        $this->Model=new DatasModel();
    }
    public function GetAllListlike(){
    	$res=$this->Model->GetAllListlike();
        Back($res,"查询成功",$this->Model->getError());
    }
    public function BulkData(){
        $res=$this->Model->BulkData();
        Back($res,"导出成功",$this->Model->getError());
    }
}