<?php


namespace app\validate;


class ChangeUser extends BaseValidate
{
    protected $rule = [
        //require是内置规则，而tp5并没有正整数的规则，所以下面这个positiveInt使用自定义的规则
        'id' => ['require', 'IsInt'],
        'name' => ['require'],
        'pwd' => ['require'],
    ];
    protected $message = [
        'id.require' => '缺少必要参数',
        'id.IsInt' => 'id必须为正整数',
        'name.require' => '缺少必要参数',
        'pwd.require' => '缺少必要参数',
    ];
}