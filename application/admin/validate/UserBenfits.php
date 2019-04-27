<?php
namespace app\admin\validate;
use think\Validate;
class UserBenfits extends Validate
{
    // 验证规则
    protected $rule = [
        ['points', 'require| number'],
//        ['discount','require|between:1,100|unique:user_level'],
    ];
    //错误信息
    protected $message  = [
        'points.require'    => '提成点必填',
        'points.number'    => '提成点必须是数字',

    ];
    //验证场景
    protected $scene = [
        'edit'  =>  [
            'points'    =>'require|number',
        ],
    ];
}