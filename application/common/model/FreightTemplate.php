<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */
namespace app\common\model;

use think\Db;
use think\Model;

class FreightTemplate extends Model
{
    //自定义初始化
    protected static function init()
    {
        //TODO:自定义的初始化
    }
    public function freightConfig()
    {
        return $this->hasMany('FreightConfig', 'template_id', 'template_id');
    }

    public function getTypeDescAttr($value, $data)
    {
        $type = config('FREIGHT_TYPE');
        return $type[$data['type']];
    }

    public function getUnitDescAttr($value, $data)
    {
        if($data['type'] == 0){
            return '件';
        }else if($data['type'] == 1){
            return '克';
        }else{
            return '立方米';
        }
    }

}
