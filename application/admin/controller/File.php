<?php
namespace app\admin\controller;
use app\Controllers\BaseController;
use PHPExcel_IOFactory;
use app\Models\Datas as DataModel;
class File extends BaseController{
    public function Upfile()
    {
        $file = request()->file('image');
        $info = $file->move( '../public/Upload/Pic');
        if($info){
            $fileres=explode("\\",$info->getSaveName());
            $res="/Upload/Pic/".$fileres[0]."/".$fileres[1];
            BackData("200","上传成功",$res);
        }else{
            BackData("400","上传失败",$file->getError());
        }
    }
    public function Video()
    {
        $file = request()->file('video');
        $info = $file->move( '../public/Upload/Video');
        if($info){
            $fileres=explode("\\",$info->getSaveName());
            $res="/Upload/Video/".$fileres[0]."/".$fileres[1];
            BackData("200","上传成功",$res);
        }else{
            BackData("400","上传失败",$file->getError());
        }
    }
    //数据批量保存 上传excel表保存
    public function Bulk()
    {
        $file = request()->file('file');
        $info = $file->move( '../public/Upload/DataFile');
        if($info){
            $fileres=explode("\\",$info->getSaveName());
            $path="../public/Upload/DataFile/".$fileres[0]."/".$fileres[1];
            $data=read_excel($path);
            $data=ExeclDataToSqlData($data);
            $datamodel=new DataModel();
            $res=$datamodel->Bulk($data);
            if($res){
                BackData("200","上传成功",$data);
            }else{
                BackData("400","上传失败");
            }
        }else{
            BackData("400","上传失败",$file->getError());
        }
    }
}