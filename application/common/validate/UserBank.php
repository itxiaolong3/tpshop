<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/21
 * Time: 18:24
 */

namespace app\common\validate;
use think\Validate;
class UserBank extends Validate
{
    // 验证规则
    protected $rule = [
        ['bankname', 'require'],
        ['banknum','require|number'],
        ['bankname2','require|number'],
        ['bankplace','require'],
        ['place','require'],
    ];
    //错误信息
    protected $message  = [
        'bankname.require'    => '银行卡名称必填',
        'banknum.require'     => '银行卡账号必填',
        'banknum.number'        => '银行卡必须为数字',
        'banknum2.require'     => '银行卡账号必填',
        'banknum2.number'        => '银行卡必须为数字',
        'bankplace.require'         => '支行必填',
        'place.require'       => '支行所在地必填',
    ];
    //验证场景
    protected $scene = [
        'add'  =>  [
            'bankname'    =>'require',
            'banknum'        =>'require|number',
            'bankname2'    =>'require|number',
            'bankplace'    =>'require',
            'place'    =>'require',
        ],
    ];
}