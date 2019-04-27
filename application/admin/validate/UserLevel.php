<?php
namespace app\admin\validate;
use think\Validate;
class UserLevel extends Validate
{
    // 验证规则
    protected $rule = [
        ['level_name', 'require|unique:user_level'],
        ['amount','require|number|unique:user_level'],
//        ['discount','require|between:1,100|unique:user_level'],
    ];
    //错误信息
    protected $message  = [
        'level_name.require'    => '名称必填',
        'level_name.unique'     => '已存在相同等级名称',
        'amount.require'        => '消费额度必填',
        'amount.number'         => '消费额度必须是数字',
        'amount.unique'         => '已存在相同消费额度',
//        'discount.require'      => '折扣率必填',
//        'discount.between'      => '折扣率在1-100之间',
//        'discount.unique'       => '已存在相同折扣率',
    ];
    //验证场景
    protected $scene = [
        'edit'  =>  [
            'level_name'    =>'require|unique:user_level,level_name^level_id',
            'amount'        =>'require|number|unique:user_level,amount^level_id',
            'discount'    =>'require|between:1,100',
        ],
    ];
}