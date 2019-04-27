<?php
namespace app\admin\validate;

use think\Validate;
use think\Db;

class GoodsType extends Validate
{

    // 验证规则
    protected $rule = [
        'name' => 'require|max:60|unique:goods_type',
    ];
    //错误信息
    protected $message = [
        'name.require' => '模型名称必须',
        'name.max' => '一个字母占一个字符，一个中文占三个字符，模型名称不能大于六十个字符',
        'name.unique' => '已存在相同模型名称',
    ];

}