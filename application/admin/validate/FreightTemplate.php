<?php
namespace app\admin\validate;
use think\Validate;
class FreightTemplate extends Validate
{
    // 验证规则
    protected $rule = [
        ['template_name', 'require|max:40unique:freight_template'],
        ['type', 'require'],
        ['is_enable_default', 'require'],
        ['config_list','require|checkConfigList']
    ];
    //错误信息
    protected $message  = [
        'template_name.require'         => '请填写模板名称',
        'is_enable_default.require'     => '请选择是否启用默认配送配置',
        'template_name.max'             => '一个字母占一个字节，一个中文占三个字节,模板名称不能超过四十个字节',
        'template_name.unique'          => '已有重名的模板名称',
        'type.require'                  => '请选择计价方式',
        'config_list.require'           => '请添加配送区域',
    ];
    /**
     * 检查用户使用时间
     * @param $value
     * @param $rule
     * @param $data
     * @return bool
     */
    protected function checkConfigList($value,$rule,$data){
        $config_list_length = count($value);
        if($data['type'] == 0){
            for ($i = 0; $i < $config_list_length; $i++) {
                if(((int)$value[$i]['first_unit']) == 0 || ((int)$value[$i]['continue_unit']) == 0){
                    return "件数必须大于0";
                }
            }
        }
        $arr_recursive = [];
        for ($i = 0; $i < $config_list_length; $i++) {
            if(!empty($value[$i]['area_ids'])){
                $temp = explode(',',$value[$i]['area_ids']);
                $arr_recursive = array_merge($temp, $arr_recursive);
            }
        }
        $arr_recursive_length = count($arr_recursive);
        $arr_unique = array_unique($arr_recursive);
        $arr_unique_length = count($arr_unique);
        if($arr_recursive_length != $arr_unique_length){
            return '配送区域存在重复区域';
        }
        return true;
    }

}