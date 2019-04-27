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

class Miniapp extends SaasModel
{

    public function userMiniapps()
    {
        return $this->hasMany('UserMiniapp', 'miniapp_id', 'miniapp_id');
    }

    public function appService()
    {
        return $this->belongsTo('appService', 'miniapp_id', 'miniapp_id');
    }

    public function user()
    {
        return $this->belongsTo('Users', 'user_id', 'user_id');
    }

    public function getDomainsAttr($value)
    {
        return json_decode($value, true);
    }

    public function setDomainsAttr($value)
    {
        $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        return strtolower($value);
    }

    public function getCategoriesAttr($value)
    {
        return json_decode($value, true);
    }

    public function setCategoriesAttr($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getTestersAttr($value)
    {
        if (!$value) {
            return [];
        }
        return explode(',', $value);
    }

    public function setTestersAttr($value)
    {
        if (!$value) {
            return '';
        }
        return implode(',', $value);
    }
}