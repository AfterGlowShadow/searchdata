<?php
namespace app\validate;
class LoginChange extends BaseValidate{
    protected $rule = [
        //require是内置规则，而tp5并没有正整数的规则，所以下面这个positiveInt使用自定义的规则
        'name' => ['require'],
        'pwd' => ['require'],
        'oldpwd'=>['require']
    ];
    protected $message = [
        'name.require' => '用户名不能为空',
        'pwd.require' => '新密码不能为空',
        'oldpwd.require'=>'密码不能为空'
        // 'yzm.require'=>'验证码不能为空',
    ];

}
?>