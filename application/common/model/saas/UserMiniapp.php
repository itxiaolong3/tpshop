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

class UserMiniapp extends SaasModel
{
    //小程序状态值
    const STATUS_TEST = 0;
    const STATUS_AUDITING = 1;
    const STATUS_AUDIT_DONG = 2;
    const STATUS_AUDIT_FAIL = 3;
    const STATUS_ON_RELEASE = 4;
    const STATUS_INVALID = 5;
    const STATUS_ABANDON = 6;


    static public function getAllStatus()
    {
        return [
            self::STATUS_TEST       => '体验版',
            self::STATUS_AUDITING   => '正在审核',
            self::STATUS_AUDIT_DONG => '审核通过',
            self::STATUS_AUDIT_FAIL => '审核失败',
            self::STATUS_ON_RELEASE => '已上线',
            self::STATUS_INVALID    => '已失效',
            self::STATUS_ABANDON    => '已废弃',
        ];
    }

    public function getStatusNameAttr($value, $data)
    {
        $statusName = self::getAllStatus();
        if (key_exists($data['status'], $statusName)) {
            return $statusName[$data['status']];
        }
        return '未知状态';
    }
}