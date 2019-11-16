<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
// 应用公共文件
function BackData($code,$msg,$data="",$type=JSON_UNESCAPED_UNICODE){
    $res['code']=$code;
    if($code==200){
    	$res['msg']=$msg;
    }else{
    	$res['msg']=$msg;
    }
    $res['data']=$data;
    echo json_encode($res,$type);
    exit;
}
//判断是否为手机
function isMobile(){
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
	    return true;
	//此条摘自TPM智能切换模板引擎，适合TPM开发
	if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
	    return true;
	//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ($_SERVER['HTTP_VIA']))
	    //找不到为flase,否则为true
	    return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
	//判断手机发送的客户端标志,兼容性有待提高
	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
	    $clientkeywords = array(
	        'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
	    );
	    //从HTTP_USER_AGENT中查找手机浏览器的关键字
	    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
	        return true;
	    }
	}
	//协议法，因为有可能不准确，放到最后判断
	if (isset ($_SERVER['HTTP_ACCEPT'])) {
	    // 如果只支持wml并且不支持html那一定是移动设备
	    // 如果支持wml和html但是wml在html之前则是移动设备
	    if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
	        return true;
        }
    }
    return false;
}
//加密方法
function Encryption($str,$type="md5", $auth_key = '')
{
  if($type=='md5'){
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
  }
}
function list_to_tree($list, $pk='id', $pid = 'pid', $child = 'child', $root = 0, $strict = true,$filter = array()){
    // 创建Tree
    $tree = array();
    if(is_array($list)){
        // 创建基于主键的数组引用
        $refer = array();
        foreach($list as $key => $data){
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach($list as $key => $data){
            // 判断是否存在parent
            $parent_id = $data[$pid];
            if($parent_id === null || (int)$root === $parent_id){
                $tree[] =& $list[$key];
            }else{
                if(isset($refer[$parent_id])){
                    $parent =& $refer[$parent_id];
                    $parent[$child][] =& $list[$key];
                }else{
                    if($strict === false){
                        $tree[] =& $list[$key];
                    }
                }
            }
        }
        //剔除数据
        if(count($filter) > 0){
            foreach($refer as $key => $data){
                foreach ($data as $k => $v) {
                    if(in_array($k, $filter)) unset($refer[$key][$k]);
                }
            }
        }
    }
    return $tree;
}
//统一返回函数
function Back($res,$successmessage,$failmessage){
    if($res){
//        if($res==1){
//            BackData("200",$successmessage,'success');
//        }else{
            BackData("200",$successmessage,$res);
//        }
    }else{
        BackData("400",$failmessage);
    }
}
//excel表格处理函数
function read_excel($filename)
{
    $reader = PHPExcel_IOFactory::createReader('Excel2007');
    //载入excel文件
    $excel = $reader->load($filename);
    //读取第一张表
    $sheet = $excel->getSheet(0);
    //获取总行数
    $row_num = $sheet->getHighestRow();
    //获取总列数
    $col_num = $sheet->getHighestColumn();

    $data = []; //数组形式获取表格数据
    for($col='A';$col<=$col_num;$col++)
    {
        //从第二行开始，去除表头（若无表头则从第一行开始）
        for($row=2;$row<=$row_num;$row++)
        {
            $data[$row-2][$col] = $sheet->getCell($col.$row)->getValue();
        }
    }
    foreach ($excel->getSheet(0)->getDrawingCollection() as $k => $drawing) {

        $codata = $drawing->getCoordinates(); //得到单元数据 比如G2单元
        $row=substr($codata,1,1);
        $col=substr($codata,0,1);
        $filename = $drawing->getIndexedFilename();  //文件名
        ob_start();
        if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
            call_user_func(
                $drawing->getRenderingFunction(),
                $drawing->getImageResource()
            );
            $imageContents = ob_get_contents();
            ob_end_clean();
            switch ($drawing->getMimeType()) {
                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_PNG :
                    $extension = 'png';
                    break;
                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_GIF:
                    $extension = 'gif';
                    break;
                case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_JPEG :
                    $extension = 'jpg';
                    break;
            }
            $myFileName = '/ExcelPic/'.md5(time()).'.'.$extension;
            file_put_contents('./'.$myFileName,$imageContents);
            $data[$row-2][$col]=$myFileName;
        }else{
            $zipReader = fopen($drawing->getPath(),'r');
            $imageContents = '';
            while (!feof($zipReader)) {
                $imageContents .= fread($zipReader,1024);
            }
            fclose($zipReader);
            $extension = $drawing->getExtension();
            $myFileName = '/ExcelPic/'.md5(time().$codata).'.'.$extension;
            $data[$row-2][$col]=$myFileName;
            file_put_contents('./'.$myFileName,$imageContents);
        }
    }
    return $data;
}
//对excel导出的数据进行处理 为searchdata
function ExeclDataToSqlData($data){
    $resarray=array();
    foreach ($data as $key => $value){
        $temp=array();
        if($value['B']!=""&&$value['C']!=""&&$value['D']!=""&&$value['E']!=""){
            if(array_key_exists('A',$value)&&$value['A']!=""){
                $temp['goods_pic']=$value['A'];
            }
            $temp['bar_code']=$value['B'];
            $temp['shop_name']=$value['C'];
            $temp['goods_number']=$value['D'];
            $temp['supplier']=$value['E'];
            $temp['question']=$value['F'];
            $temp['question_analysis']=$value['G'];
            $temp['question_solutions']=$value['H'];
            $n = intval(($value['I'] - 25569) * 3600 * 24); //转换成1970年以来的秒数
            $temp['arrival_date']=gmdate('Y-m-d H:i:s',$n);
            $n = intval(($value['J'] - 25569) * 3600 * 24); //转换成1970年以来的秒数
            $temp['feedback_date']=gmdate('Y-m-d H:i:s',$n);
            $temp['customer_id']=$value['K'];
            if(array_key_exists('L',$value)&&$value['L']!=""){
                $temp['question_pic1']=$value['L'];
            }
            if(array_key_exists('M',$value)&&$value['M']!=""){
                $temp['question_pic2']=$value['M'];
            }
            if(array_key_exists('N',$value)&&$value['N']!=""){
                $temp['question_pic3']=$value['N'];
            }
            if(array_key_exists('O',$value)&&$value['O']!=""){
                $temp['question_pic4']=$value['O'];
            }
            $temp['remark1']=$value['P'];
            $temp['remark2']=$value['Q'];
            $temp['remark3']=$value['R'];
            $temp['remark4']=$value['S'];
        }
        $resarray[]=$temp;
    }
    return $resarray;
}
