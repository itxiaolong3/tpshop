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
 * Author: yhj
 * Date: 2018-6-27
 */
namespace app\common\model;

use think\Db;
use think\Model;

class UserMsgTpl extends Model
{
    public function getEditButtonAttr($value, $data)
    {
        if (strpos($data['mmt_code'], 'activity')){
            return false;
        }
        $return_flag = true;
        switch ($data['mmt_code']) {
            case 'coupon_will_expire_notice':
            case 'coupon_use_notice':
            case 'coupon_get_notice':
            case 'deliver_goods_logistics':
            case 'evaluate_logistics':
            case 'virtual_order_logistics':
                $return_flag = false;
                break;
            default:
                $return_flag = true;
                break;
        }
        return $return_flag;

    }
}
