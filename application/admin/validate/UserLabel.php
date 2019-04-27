<?php
namespace app\admin\validate;
use think\Validate;
class UserLabel extends Validate
{
    // 验证规则
    protected $rule = [
        ['label_name', 'require|unique:user_label'],
        ['label_order','require|number|between:0,99'],
        ['label_code','require'],
        ['is_recommend','require|between:0,1'],
        ['label_describe','max:255'],
    ];
    //错误信息
    protected $message  = [
        'label_name.require'    => '名称必填',
        'label_name.unique'     => '已存在相同标签名称',
        'label_order.require'        => '标签排序必填',
        'label_order.number'         => '标签排序必须是数字',
        'label_order.between'      => '标签排序在0-99之间',
        'label_code.require'       => '图片必填',
        'is_recommend.require'       => '标签推荐',
        'is_recommend.between'       => '标签推荐在0-1之间',
        'label_describe.max'       => '标签描述不超过255个字符',
    ];
    //验证场景
    protected $scene = [
        'edit'  =>  [
            'label_name'    =>'require',
            'label_order'        =>'require|number|between:0,99',
            'label_code'    =>'require',
            'is_recommend'    =>'require|between:0,1',
            'label_describe'    =>'max:255',
        ],
    ];
}