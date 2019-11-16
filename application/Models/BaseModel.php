<?php
namespace app\Models;

use think\Db;
use think\Model;
class BaseModel extends Model{
	protected $autoWriteTimestamp = true;     //开启自动写入时间戳
    protected $createTime = "create_time";            //数据添加的时候，create_time 这个字段不自动写入时间戳
    protected $updateTime = "update_time"; 
	//根据传过来的数组查询一个数据
	public function MgetOne($mcont=array(),$order="id asc",$field=array()){
		$find=$this->where($mcont)->order($order)->field($field)->find();
		if($find){
		    $find=$find->toArray();
			return $find;
		}else{
			return "";
		}
    }  
    //无序查找所有数据
    public function MgetSelect($mcont=array(),$order="id asc",$field=array()){
		$find=$this->where($mcont)->order($order)->field($field)->select();
		if($find){
		    $find=$find->toArray();
			return $find;
		}else{
			return "";
		}
    }
    //强制删除
    public function MFDelete($mcont)
    {
        $res=$this->where($mcont)->delete();
        if($res){
            return $res;
        }else{
            return "";
        }
    }
    //删除单个数据
    public function MDelete($mcont){
        $res=$this->where($mcont)->setField("status",2);
	    if($res){
	        return 1;
	    }else{
	        return "8";
	    }
    }
    //批量删除
    public function MDeleteByArray($mcont)
    {
       $res= $this->where($mcont)->setField("status",2);
       if($res){
           return $res;
       }else{
           return "";
       }
    }
    //分页查找数据
    public function MgetAll($mcont,$config,$order="id asc",$field=array()){
    	$messages=$this->where($mcont)->order($order)->field($field)->paginate($config['list_rows'],false,$config);
    	if($messages){
			$res['listRows']=$messages->listRows();
			$res['currentPage']=$config['page'];
			$res['total']=$messages->total();
			$quan_to_array = $messages->toArray();
			$res['data'] = $quan_to_array['data'];
			return $res;
    	}else{
    		return "";
    	}
    }

    public function MgetAllOr($mcont,$mcontor,$config,$order="id asc",$field=array())
    {
        $messages=$this->where($mcont)->whereOr($mcontor)->order($order)->field($field)->paginate($config['list_rows'],false,$config);
    	if($messages){
			$res['listRows']=$messages->listRows();
			$res['currentPage']=$config['page'];
			$res['total']=$messages->total();
			$quan_to_array = $messages->toArray();
			$res['data'] = $quan_to_array['data'];
			return $res;
    	}else{
            return "";
        }
    }
    public function GetDataQuery($table,$where=array(),$whereor=array(),$config=array(),$order="id asc",$field=array()){
	    if(!empty($where)){
            $wherestr="";
            foreach ($where as $k => $v){
                $wherestr.=$v[0]." ".$v[1]." ".$v[2]." and";
            }
            $wherestr=substr($wherestr,0,strlen($wherestr)-4);
        }else{
            $wherestr="where 1=1";
        }
        if(!empty($whereor)){
            $whereortr="";
            foreach ($whereor as $k => $v){
                $whereortr.=$v[0]." ".$v[1]." '".$v[2]."' or ";
            }
            $whereortr=substr($whereortr,0,strlen($whereortr)-3);
        }else{
            $whereortr="where 1=1";
        }
        $config['page'] = $config['page'] - 1;
        $field=" count(*) ";
        $sql="SELECT ".$field." FROM ".$table." WHERE  ".$wherestr." And (".$whereortr.") ORDER BY ".$order." LIMIT 0,".$config['list_rows'];
        $count=Db::query($sql);
        if(empty($count)){
            $res['data']="";
            $res['total']=0;
        }else {
            if(!empty($filed)){
                $field=implode(",", $field);
            }else{
                $field="*";
            }
            $sql = "SELECT " . $field . " FROM " . $table . " WHERE  " . $wherestr . " And (" . $whereortr . ") ORDER BY " . $order . " LIMIT " . $config['page'] * $config['list_rows']. "," . $config['list_rows'];
            $res['data'] = Db::query($sql);
            $res['total']=$count[0]['count(*)'];
        }
        $res['listRows']=$config['list_rows'];
        $res['currentPage']=$config['page'];

	    if($res){
            return $res;
        }else{
            return $res;
        }
    }
    //跟新单个数据
    public function MUpdate($data,$where){

	    foreach ($data as $key=>$vlaue){
	        if($key!='id'){
                $this->$key=$vlaue;
            }
        }
    	$res=$this->allowField(true)->save($data,$where);
    	return $res;
    }
     //修改单个
    public function MDBUpdate($table,$where,$data)
    {
        return Db::table($table)->where($where)->data($data)->update();
    }
    //添加单个数据
    public function MDBAdd($table,$data)
    {
        return Db::table($table)->strict(false)->insertGetId($data);
    }
    //保存单个数据
    public function MSave($data){
    	$res=$this->allowField(true)->save($data);
    	return $res;
    }
    //添加一组数据
    public function MSaveList($dataarray){
        $res=$this->saveAll($dataarray);
        if($res){
            return $res;
        }else{
            return 0;
        }
    }
    //带时间的查询一个数据
    public function MgetOneByTime($mcont,$time,$field="")
    {
        $find=$this->where($time[0],$time[1],$time[2],'and')->where($mcont[0],$mcont[1])->field($field)->find();
        if($find){
            $find=$find->toArray();
            return $find;
        }else{
            return "";
        }
    }
    //关联查询
    public function MJoin($table1,$table2,$table1n,$table2n,$where,$page,$list_row,$order=array('id','asc'),$field=array()){
        $res['data']=Db::name($table1)
            ->where($where)
            ->alias('a')
            ->join($table2.' w','a.'.$table1n.' = w.'.$table2n)
            ->order($order[0],$order[1])
            ->limit($page,$list_row)
            ->field($field)
            ->select();
        $cont=Db::name($table1)
            ->where($where)
            ->alias('a')
            ->join($table2.' w','a.'.$table1n.' = w.'.$table2n)
            ->count();
        foreach ($res['data'] as $key => $value){
            if(array_key_exists("create_time",$value)){
                $res['data'][$key]['create_time']=date("Y-m-d",$value['create_time']);
            }
        }
        $res['count']=$cont;
        $res['list_row']=$list_row;
//        if($res['count']>0){
            $res['total']=ceil($cont/$list_row);
//        }else{
//            $res['total']=0;
//        }
        if($res){
            return $res;
        }else{
            return "";
        }
    }
}
?>