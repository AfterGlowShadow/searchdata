<?php


namespace app\validate;


class DataAdd extends BaseValidate
{
    protected $rule = [
        //require是内置规则，而tp5并没有正整数的规则，所以下面这个positiveInt使用自定义的规则
        'bar_code' => ['require'],
        'shop_name' => ['require'],
        'goods_number' => ['require'],
        'supplier' => ['require'],
    ];
    protected $message = [
        'bar_code.require' => '条形码不能为空',
        'shop_name.IsInt' => '网店名称不能为空',
        'goods_number.require' => '厂家货号不能为空',
        'supplier.require' => '供应商不能为空',
    ];
}