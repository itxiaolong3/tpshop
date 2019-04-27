<?php
namespace app\admin\validate;
use think\Validate;
class StarLevel extends Validate
{
    // 验证规则
    protected $rule = [
        ['team_total', 'require|number'],
        ['star_level', 'require|number|unique'],
        ['first_lower_level', 'require|number'],
    ];
    //错误信息
    protected $message  = [
        'team_total.require'    => '团队总人数必填',
        'team_total.number'    => '团队总人数必须是数字',
        'star_level.require'    => '星级必填',
        'star_level.unique'    => '星级唯一',
        'star_level.number'    => '星级必须是数字',
        'first_lower_level.require'     => '直推总人数必填',
        'first_lower_level.number'     => '直推总人数必须是数字',
        //'second_achievement.require'        => '第二',
      //  'amount.number'         => '消费额度必须是数字',
       // 'amount.unique'         => '已存在相同消费额度',
//        'discount.require'      => '折扣率必填',
//        'discount.between'      => '折扣率在1-100之间',
//        'discount.unique'       => '已存在相同折扣率',
    ];
    //验证场景
    protected $scene = [
        'edit'  =>  [
            'team_total'    =>'require',
            'first_lower_level'        =>'require',
           // 'discount'    =>'require|between:1,100',
        ],
    ];
}