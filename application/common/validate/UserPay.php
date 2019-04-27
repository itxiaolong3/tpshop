<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/23
 * Time: 9:26
 */


namespace app\common\validate;

use think\Validate;

class UserPay extends Validate
{
    //验证规则
    protected $rule = [
        // 'nickname' => 'require|checkName',
        'password' => 'require',
        'paypwd' => 'require|number|checkPassword',
        'paypwd2' => 'require|number',
    ];

    //错误消息
    protected $message = [
        'password.require'    => '登录密码不能为空',
        'paypwd.checkPassword'     => '两次密码不一致',
        'paypwd.require'    => '支付密码不能为空',
        'paypwd.number'    => '支付密码必须为数值',
        'paypwd2.require'    => '确认密码不能为空',
        'paypwd2.number'     => '确认密码必须为数值',
    ];

    //错误消息
    protected $scene= [
        'set_paypwd' => ['password'],
        'reg'     => ['nickname','password'],
    ];
    /**
     * 验证两次密码
     * @param $value
     * @param $rule
     * @param $data
     * @return string
     */
    protected function checkPassword($value, $rule ,$data){
        if($value != $data['paypwd2']){
            return false;
        }
        return true;
    }
//    /**
//     * 验证密码长度
//     * @param $value
//     * @param $rule
//     * @param $data
//     * @return string
//     */
//    protected function checkStrlen($value, $rule ,$data){
//        if(strlen($value)<6 || strlen($value)>18){
//            return false;
//        }
//        return true;
//    }
    /**
     * 验证是否存在用户名
     * @param $value
     * @return string
     */
    protected function checkName($value){
        if(get_user_info($value,1)||get_user_info($value,2)){
            return '账号已存在';
        }
        return true;
    }
}
