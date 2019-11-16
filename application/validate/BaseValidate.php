<?php
namespace app\validate;
use think\Exception;
use Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck($data='')
    {
        //实例化请求对象
        $requestObj=Request::instance();
        //如果传入为空则获取请求里的参数
        // exit;
        // empty($data)&&$data=$requestObj->param();
        if ($this->check($data)) {
            //如果验证通过了
            return true;
        }else{
            //如果验证没通过
            // $error=$this->getError();
            //抛出异常
            // throw new Exception($error);
            json(['code'=>400,'msg'=>$this->getError()])->code(400)->send();
            exit();
        }
    }
     //系统会自动传入几个参数 第一个是 要验证的值，第二个是规则，自己可以规定规则内容或者不写，第三个是最初传入的data。其实不只这三个参数，想了解详细的可以看看文档
   protected function IsInt($value,$rule,$data,$field){
        if(is_numeric($value) && is_int($value+0) && ($value+0) > 0){
          return true;
        }else{
          return false;
        }
      }
}
?>