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

class Users extends SaasModel
{
    //saas的内部客户账号，用于保存基础应用实例的配置，不用于测试
    const ADMIN_ID = 1;

    //用户类型
    const TYPE_GENERAL = 0; //普通用户类型
    const TYPE_INTERNAL = 1; //内部用户类型，可使用禁止使用的域名前缀等

    static public function getAllUserTypes()
    {
        return [
            self::TYPE_GENERAL => '普通用户',
            self::TYPE_INTERNAL => '内部用户',
        ];
    }

    public function getUserTypeName($value, $data)
    {
        $types = self::getAllUserTypes();
        if (key_exists($data['user_type'], $types)) {
            return $types[$data['user_type']];
        }
        return '未知类型';
    }

    public function miniapps()
    {
        return $this->hasMany('Miniapp', 'user_id', 'user_id');
    }

    public function appOrders()
    {
        return $this->hasMany('AppOrder', 'user_id', 'user_id');
    }

    public function moduleOrders()
    {
        return $this->hasMany('ExtendOrder', 'user_id', 'user_id')->where('extend_type', EXTEND_MODULE);
    }
}