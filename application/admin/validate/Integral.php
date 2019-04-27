<?php
namespace app\admin\validate;
use think\Validate;
use think\Db;
class Integral extends Validate
{
    /**
     * 检查每消费1元赠送的积分
     * @param $value|验证数据
     * @param $rule|验证规则
     * @param $data|全部数据
     * @return bool|string
     */
    public function checkIntegral($value, $rule ,$data,$field_name)
    {
        if(($field_name=='consume_integral' && $data['is_consume_integral'])
            || ($field_name=='reg_integral' && $data['is_reg_integral'])
            || ($field_name=='invite_integral' && $data['invite'])
            || ($field_name=='invitee_integral' && $data['invite'])
            || ($field_name=='point_min_limit' && $data['is_point_min_limit'])
            || ($field_name=='point_rate' && $data['is_point_rate'])
            || ($field_name=='point_use_percent' && $data['is_point_use_percent'])
        ){
            return $this->checkHandle($value,$field_name);
        }else{
            return true;
        }
    }

    /**
     * 检查每消费1元赠送的积分
     * @param $value|验证数据
     * @param $field_name|验证字段
     * @return bool|string
     */
    protected function checkHandle($value,$field_name){
        $fieldNameArr = [
            'consume_integral'=>'请输入每消费1元赠送的积分数！',
            'reg_integral'=>'请输入注册可获得的积分数！',
            'invite_integral'=>'请输入注册成功邀请人可获得的积分！',
            'invitee_integral'=>'请输入注册成功被邀请人可获得的积分！',
            'point_min_limit'=>'请输入小于的积分数！',
            'point_rate'=>'请选择积分兑换现金比列！',
            'point_use_percent'=>'请输入单笔订单最多可抵扣的百分比！'
        ];
        if ($value == '') {
            return $fieldNameArr["".$field_name.""];
        } else {
            if (!is_int($value+0) || $value <= 0) {
                if($field_name == 'point_use_percent'){
                    return '请输入正确的百分比格式1！';
                }else{
                    return '请输入正确的积分格式！';
                }
            }else{
                if($field_name == 'point_use_percent' && $value>100){
                    return '百分比范围不能超过100！';
                }elseif($field_name == 'point_rate' && $value>100){
                    return '积分兑换现金比不能超过100！';
                } {
                    return true;
                }
            }
        }
    }
}