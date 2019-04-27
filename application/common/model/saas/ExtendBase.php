<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 15:55
 */

namespace app\common\model\saas;

use think\Model;

abstract class ExtendBase extends SaasModel
{
    public function getAmountData($mouths)
    {
        $pk = $this->getPk();
        return [
            'id'     => $this->$pk,
            'name'   => $this['name'],
            'amount' => $this->price * $mouths,
            'use_time' => $mouths,
            'use_time_unit' => TIME_MOUTH
        ];
    }

    static public function getAllExtendType()
    {
        return [
            EXTEND_MODULE => '增值模块',
            EXTEND_ANDROID => '安卓应用',
            EXTEND_IOS => 'iOS应用',
            EXTEND_ENTRUST => '委托服务',
            EXTEND_MINIAPP => '小程序模板',
        ];
    }
}