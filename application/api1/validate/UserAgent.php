<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: dyr
 * Date: 2016-08-23
 */

namespace app\api\validate;

use think\Validate;

/**
 * 用户分销验证器
 * Class Distribut
 * @package app\mobile\validate
 */
class UserAgent extends Validate
{
    //验证规则
    protected $rule = [
        'realname'     =>'require|max:25',
        'idcard'            =>'require|checkIdcard',
        'mobile'        =>'require|checkMobile',
        'province' => 'require|gt:0',
        'city' => 'require|gt:0',
        'district' => 'require|gt:0',
        'address' => 'require|max:200',
    ];

    //错误信息
    protected $message  = [
        'realname.require'     => '真实姓名必须填写',
        'realname.max'         => '真实姓名不得超过25个字符',
        'mobile.require'        => '手机号码必须填写',
        'mobile.checkMobile'          => '手机号码格式错误',
        'idcard.require'        => '身份证号码必须填写',
        'idcard.checkMobile'          => '身份证号码格式错误',
        'province.require' => '请选择省份',
        'city.require' => '请选择城市',
        'district.require' => '请选择区域',
        'level_id.require' => '请选择级别',
        'province.gt' => '请选择省份',
        'city.gt' => '请选择城市',
        'district.gt' => '请选择区域',
        'level_id.gt' => '请选择级别',
        'address.require' => '详细地址必须',
        'address.max' => '详细地址长度不得超过200字符',
    ];

    /**
     * 检查手机格式
     * @param $value|验证数据
     * @param $rule|验证规则
     * @param $data|全部数据
     * @return bool|string
     */
    protected function checkMobile($value, $rule ,$data)
    {
        return check_mobile($value);
    }

    /**
     * 检查身份证格式
     * @param $value|验证数据
     * @return bool|string
     */
    protected function checkIdcard($value){
        return check_idCard($value);
    }
}