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
 * @author: lhb
 */

namespace app\common\model\saas;

use think\Model;

abstract class ServiceBase extends SaasModel
{
    //服务状态
    const STATUS_NORMAL     = 0;
    const STATUS_EXPIRED    = 1;
    const STATUS_FROZEN     = 2;

    /**
     * 获取所有订单状态
     * @return array
     */
    static public function getAllStatus()
    {
        return [
            static::STATUS_NORMAL   => '正常',
            static::STATUS_EXPIRED  => '已到期',
            static::STATUS_FROZEN   => '冻结中'
        ];
    }

    public function getStatusNameAttr($value, $data)
    {
        $statuses = static::getAllStatus();
        if (key_exists($data['status'], $statuses)) {
            return $statuses[$data['status']];
        }

        return '未知状态';
    }

    public function getExtendTypeNameAttr($value, $data)
    {
        $types = ExtendBase::getAllExtendType();
        if (key_exists($data['extend_type'], $types)) {
            return $types[$data['extend_type']];
        }

        return '未知类型';
    }
}